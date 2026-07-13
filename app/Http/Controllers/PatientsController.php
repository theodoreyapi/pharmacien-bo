<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Planning;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PatientsController extends Controller
{
    /**
     * Liste des patients avec KPIs, filtres et recherche
     */
    public function index(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        $pharmacyId = session('pharmacy_id');

        /* ── KPI ── */
        $totalPatients = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->count();

        $aJour = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->where('status', 'A_JOUR')
            ->count();

        $enRetard = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->whereIn('status', ['EN_RETARD', 'CRITIQUE'])
            ->count();

        /* ── Requête principale ── */
        $query = DB::table('patients as p')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('p.active', 'ACTIVE')
            // Pathologies (codes concaténés)
            ->leftJoin('patient_pathologies as pp', function ($join) {
                $join->on('pp.patient_id', '=', 'p.id_patient')
                    ->where('pp.status', 'ACTIF');
            })
            ->leftJoin('pathologies as path', 'path.id_pathologie', '=', 'pp.pathologie_id')
            // Dernière mesure
            ->leftJoin(DB::raw('(
                SELECT patient_id, MAX(created_at) as last_measure_date
                FROM mesures
                GROUP BY patient_id
            ) as last_m'), 'last_m.patient_id', '=', 'p.id_patient')
            ->select(
                'p.id_patient',
                'p.first_name',
                'p.last_name',
                'p.phone_number',
                'p.birth_date',
                'p.status',
                DB::raw("GROUP_CONCAT(DISTINCT path.code ORDER BY path.code SEPARATOR ' ') as pathologies"),
                DB::raw("GROUP_CONCAT(DISTINCT path.code ORDER BY path.code SEPARATOR ',') as pathologies_list"),
                'last_m.last_measure_date'
            )
            ->groupBy(
                'p.id_patient',
                'p.first_name',
                'p.last_name',
                'p.phone_number',
                'p.birth_date',
                'p.status',
                'last_m.last_measure_date'
            );

        /* ── Filtres URL ── */
        if ($request->filled('patho')) {
            $query->having(DB::raw("GROUP_CONCAT(DISTINCT path.code)"), 'LIKE', '%' . $request->patho . '%');
        }

        if ($request->filled('obs')) {
            $obsMap = [
                'ajour'    => 'A_JOUR',
                'retard'   => 'EN_RETARD',
                'critique' => 'CRITIQUE',
                'bientot'  => 'BIENTOT_RETARD',
            ];
            if (isset($obsMap[$request->obs])) {
                $query->where('p.status', $obsMap[$request->obs]);
            }
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('p.first_name', 'LIKE', "%$s%")
                    ->orWhere('p.last_name', 'LIKE', "%$s%")
                    ->orWhere('p.phone_number', 'LIKE', "%$s%");
            });
        }

        $patients = $query->orderBy('p.last_name')->get()
            ->map(function ($p) {
                // Calcul âge
                $p->age = $p->birth_date
                    ? Carbon::parse($p->birth_date)->age
                    : null;

                // Initiales
                $p->initials = strtoupper(mb_substr($p->first_name, 0, 1))
                    . strtoupper(mb_substr($p->last_name, 0, 1));

                // Couleur avatar selon 1ère lettre
                $p->avatar_bg    = $this->avatarBg($p->initials[0] ?? 'A');
                $p->avatar_color = $this->avatarColor($p->initials[0] ?? 'A');

                // Dernière mesure formatée
                $p->last_measure_label = $p->last_measure_date
                    ? Carbon::parse($p->last_measure_date)->translatedFormat('j M.')
                    : null;

                // Classe CSS statut
                $p->status_class = $this->statusClass($p->status);
                $p->status_label = $this->statusLabel($p->status);

                // Liste pathologies pour data-attr filtrage JS
                $p->patho_codes = $p->pathologies ?? '';

                return $p;
            });

        /* ── Pathologies disponibles pour les chips ── */
        $allPathologies = DB::table('pathologies')
            ->orderBy('code')
            ->get(['id_pathologie', 'code', 'name']);

        return view('patients.patients', compact(
            'patients',
            'totalPatients',
            'aJour',
            'enRetard',
            'allPathologies'
        ));
    }

    /**
     * Fiche de création (redirige vers add-patient multi-step)
     */
    public function create()
    {
        $communes = DB::table('commune')->orderBy('name')->get();
        return view('patients.add-patient', compact('communes'));
    }

    /**
     * Enregistrement du nouveau patient (étape 1 : identité + consentements + rattachement)
     */
    public function store(Request $request)
    {
        $roles = [
            'social' => 'required|string|unique:patients,qr_code',
            'phone_number' => 'required|string|unique:patients,phone_number',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
        ];
        $customMessages = [
            'social.required' => "Veuillez saisir le numéro de sécurité sociale.",
            'first_name.required' => "Veuillez saisir le nom du patient.",
            'last_name.required' => "Veuillez saisir le prénom du patient.",
            'phone_number.required' => "Veuillez saisir le numéro de téléphone du patient.",
        ];

        $request->validate($roles, $customMessages);

        $pharmacyId   = session('pharmacy_id');
        $pharmacienId = Auth::guard('pharmacien')->user()->id_pharmacien;

        $patientId = DB::table('patients')->insertGetId([
            'first_name'       => strtoupper(trim($request->first_name)),
            'last_name'        => ucfirst(strtolower(trim($request->last_name))),
            'phone_number'     => $request->phone_number,
            'email'            => $request->email,
            'gender'           => $request->gender ?? 'AUTRE',
            'birth_date'       => $request->birth_date ?? null,
            'city'             => $request->city ?? null,
            'commune'          => $request->commune ?? null,
            'qr_code'          => $request->social,
            'status'           => 'A_JOUR',
            'active'           => 'ACTIVE',
            'consent_suivi'    => $request->boolean('consent_suivi'),
            'consent_whatsapp' => $request->boolean('consent_whatsapp'),
            'consent_sms'      => $request->boolean('consent_sms'),
            'consent_reseau'   => $request->boolean('consent_reseau'),
            'pharmacy_id'      => $pharmacyId,
            'created_by'       => $pharmacienId,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Notification settings par défaut
        // (pas besoin ici, géré côté pharmacien)

        return redirect()->route('patients.show', $patientId)
            ->with('succes', 'Patient créé avec succès. Vous pouvez maintenant ajouter des pathologies et traitements.');
    }

    /**
     * Dossier patient complet
     */
    public function show(string $id)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        $pharmacyId = session('pharmacy_id');

        $patient = DB::table('patients as p')
            ->where('p.id_patient', $id)
            ->where('p.pharmacy_id', $pharmacyId)
            ->leftJoin(DB::raw('(
                SELECT patient_id, MAX(created_at) as last_measure_date
                FROM mesures GROUP BY patient_id
            ) as lm'), 'lm.patient_id', '=', 'p.id_patient')
            ->select('p.*', 'lm.last_measure_date')
            ->first();

        abort_if(!$patient, 404);

        $patient->age = $patient->birth_date
            ? Carbon::parse($patient->birth_date)->age
            : null;
        $patient->initials = strtoupper(mb_substr($patient->first_name, 0, 1))
            . strtoupper(mb_substr($patient->last_name, 0, 1));

        // Pathologies
        $pathologies = DB::table('patient_pathologies as pp')
            ->join('pathologies as path', 'path.id_pathologie', '=', 'pp.pathologie_id')
            ->where('pp.patient_id', $id)
            ->select('pp.*', 'path.code', 'path.name')
            ->get();

        // Traitements actifs
        $traitements = DB::table('traitements as t')
            ->leftJoin('pathologies as path', 'path.id_pathologie', '=', 't.pathologie_id')
            ->where('t.patient_id', $id)
            ->orderByDesc('t.dispensed_at')
            ->select('t.*', 'path.code as patho_code', 'path.name as patho_name')
            ->get()
            ->map(function ($t) {
                $t->days_late = $t->estimated_end_date && Carbon::parse($t->estimated_end_date)->isPast()
                    ? Carbon::parse($t->estimated_end_date)->diffInDays(now())
                    : 0;
                return $t;
            });

        // Mesures (toutes)
        $mesures = DB::table('mesures')
            ->where('patient_id', $id)
            ->orderByDesc('created_at')
            ->get();

        // Dernières mesures par type
        $derniereMesures = [];
        foreach (['PRESSION_ARTERIELLE', 'FREQUENCE_CARDIAQUE', 'GLYCEMIE', 'POIDS_IMC'] as $type) {
            $derniereMesures[$type] = $mesures->where('type', $type)->first();
        }

        // Messages / rappels
        $messages = DB::table('rappels')
            ->where('patient_id', $id)
            ->orderByDesc('created_at')
            ->get();

        // Historique réseau
        $networkLogs = DB::table('network_logs as nl')
            ->join('pharmacy as ph', 'ph.id_pharmacy', '=', 'nl.pharmacy_id')
            ->join('pharmacien as phcn', 'phcn.id_pharmacien', '=', 'nl.pharmacien_id')
            ->where('nl.patient_id', $id)
            ->orderByDesc('nl.created_at')
            ->select(
                'nl.action',
                'nl.created_at',
                'ph.name as pharmacy_name',
                DB::raw("CONCAT(phcn.first_name, ' ', phcn.last_name) as pharmacien_name")
            )
            ->get();

        // Pharmacies liées (réseau)
        $pharmaciesLiees = DB::table('pharmacy_network as pn')
            ->join('pharmacy as ph', 'ph.id_pharmacy', '=', 'pn.partner_pharmacy_id')
            ->where('pn.pharmacy_id', $pharmacyId)
            ->where('pn.status', 'ACTIF')
            ->select('ph.name', 'ph.address', 'pn.type')
            ->get()
            ->map(function ($ph) use ($id) {
                $ph->shared_patients = DB::table('network_logs')
                    ->where('patient_id', $id)
                    ->where('pharmacy_id', DB::table('pharmacy')->where('name', $ph->name)->value('id_pharmacy'))
                    ->count();
                return $ph;
            });

        // Toutes pathologies dispo pour le formulaire ajout
        $allPathologies = DB::table('pathologies')->orderBy('code')->get();

        // Tous les médicaments
        $medicaments = DB::table('medicaments')->orderBy('name')->get();

        // ── Plannings récurrents actifs ──
        $plannings = Planning::where('patient_id', $id)
            ->where('status', 'ACTIF')
            ->orderByDesc('created_at')
            ->get();

        // ── Prochains RDV (à venir, non traités) ──
        $prochainsRdv = RendezVous::where('patient_id', $id)
            ->where('status', 'ATTENTE')
            ->where('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('heure')
            ->get();

        // ── Historique RDV (passés ou déjà traités) ──
        $historiqueRdv = RendezVous::where('patient_id', $id)
            ->where(function ($q) {
                $q->where('status', '!=', 'ATTENTE')
                    ->orWhere('date', '<', Carbon::today());
            })
            ->orderByDesc('date')
            ->orderByDesc('heure')
            ->get();

        return view('patients.view-patient', compact(
            'patient',
            'pathologies',
            'traitements',
            'mesures',
            'derniereMesures',
            'messages',
            'networkLogs',
            'pharmaciesLiees',
            'allPathologies',
            'medicaments',
            'plannings',
            'prochainsRdv',
            'historiqueRdv',
        ));
    }

    /**
     * Créer un nouveau planning (récurrent ou ponctuel)
     */
    public function planningStore(Request $request, string $patientId)
    {
        $request->validate([
            'frequency_type' => 'required|in:1x,2x,perso',
            'heure'           => 'required',
            'mesures_types'   => 'required|array|min:1',
            'jours'           => 'required_if:frequency_type,1x,2x|array',
            'date_rdv'        => 'required_if:frequency_type,perso|nullable|date',
        ], [
            'jours.required_if'    => 'Veuillez sélectionner au moins un jour.',
            'date_rdv.required_if' => 'Veuillez indiquer la date du RDV personnalisé.',
            'mesures_types.required' => 'Veuillez sélectionner au moins une mesure à effectuer.',
        ]);

        $pharmacyId   = session('pharmacy_id');
        $pharmacienId = Auth::guard('pharmacien')->user()->id_pharmacien;

        $patient = DB::table('patients')
            ->where('id_patient', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->first();
        abort_if(!$patient, 404);

        if ($request->frequency_type === 'perso') {
            // RDV ponctuel : pas de planning récurrent, juste une entrée rendez_vous
            RendezVous::create([
                'patient_id'    => $patientId,
                'pharmacy_id'   => $pharmacyId,
                'planning_id'   => null,
                'date'          => $request->date_rdv,
                'heure'         => $request->heure,
                'mesures_types' => $request->mesures_types,
                'status'        => 'ATTENTE',
                'notes'         => $request->notes,
            ]);

            return back()->with('success', 'RDV personnalisé créé avec succès.');
        }

        // Planning récurrent (1x ou 2x par semaine)
        $planning = Planning::create([
            'patient_id'     => $patientId,
            'pharmacy_id'    => $pharmacyId,
            'created_by'     => $pharmacienId,
            'frequency_type' => $request->frequency_type,
            'jours'          => $request->jours,
            'heure'          => $request->heure,
            'mesures_types'  => $request->mesures_types,
            'rappel_avant'   => $request->rappel_avant ?? '24H',
            'canal'          => $request->canal ?? 'WHATSAPP',
            'notes'          => $request->notes,
            'status'         => 'ACTIF',
        ]);

        // Générer les prochaines occurrences (8 semaines à venir)
        $this->genererOccurrencesRdv($planning);

        return back()->with('success', 'Planning créé avec succès.');
    }

    /**
     * Désactiver / supprimer un planning récurrent
     */
    public function planningDestroy(string $patientId, string $planningId)
    {
        $pharmacyId = session('pharmacy_id');

        $planning = Planning::where('id', $planningId)
            ->where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->first();

        abort_if(!$planning, 404);

        // On désactive le planning et on supprime les RDV futurs non traités
        RendezVous::where('planning_id', $planning->id)
            ->where('status', 'ATTENTE')
            ->where('date', '>=', Carbon::today())
            ->delete();

        $planning->delete();

        return back()->with('success', 'Planning supprimé.');
    }

    /**
     * Marquer un RDV comme Effectué ou Absent (Manqué)
     */
    public function rdvMarquer(Request $request, string $patientId, string $rdvId)
    {
        $request->validate([
            'status' => 'required|in:EFFECTUE,ABSENT',
        ]);

        $pharmacyId = session('pharmacy_id');

        $rdv = RendezVous::where('id', $rdvId)
            ->where('patient_id', $patientId)
            ->where('pharmacy_id', $pharmacyId)
            ->first();

        abort_if(!$rdv, 404);

        $rdv->status = $request->status === 'EFFECTUE' ? 'EFFECTUE' : 'MANQUE';
        $rdv->save();

        $label = $rdv->status === 'EFFECTUE' ? 'marqué comme effectué' : 'marqué comme absent';

        return back()->with('success', "RDV $label.");
    }

    /**
     * Génère les occurrences futures d'un planning récurrent
     * (par défaut : 8 semaines à venir)
     */
    private function genererOccurrencesRdv(Planning $planning, int $semaines = 8): void
    {
        $joursMap = [
            'Dim' => 0,
            'Lun' => 1,
            'Mar' => 2,
            'Mer' => 3,
            'Jeu' => 4,
            'Ven' => 5,
            'Sam' => 6,
        ];

        $joursNumeros = collect($planning->jours)
            ->map(fn($j) => $joursMap[$j] ?? null)
            ->filter(fn($n) => $n !== null)
            ->values();

        if ($joursNumeros->isEmpty()) {
            return;
        }

        $debut = Carbon::today();
        $fin   = Carbon::today()->addWeeks($semaines);

        $date = $debut->copy();
        while ($date->lte($fin)) {
            if ($joursNumeros->contains($date->dayOfWeek)) {
                RendezVous::create([
                    'patient_id'    => $planning->patient_id,
                    'pharmacy_id'   => $planning->pharmacy_id,
                    'planning_id'   => $planning->id,
                    'date'          => $date->format('Y-m-d'),
                    'heure'         => $planning->heure,
                    'mesures_types' => $planning->mesures_types,
                    'status'        => 'ATTENTE',
                ]);
            }
            $date->addDay();
        }
    }

    /**
     * Formulaire édition (non utilisé — édition inline dans show)
     */
    public function edit(string $id)
    {
        return redirect()->route('patients.show', $id);
    }

    /**
     * Mise à jour infos patient
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone_number' => 'required|string|unique:patients,phone_number,' . $id . ',id_patient',
        ]);

        DB::table('patients')->where('id_patient', $id)->update([
            'first_name'       => strtoupper(trim($request->first_name)),
            'last_name'        => ucfirst(strtolower(trim($request->last_name))),
            'phone_number'     => $request->phone_number,
            'email'            => $request->email,
            'gender'           => $request->gender,
            'birth_date'       => $request->birth_date,
            'city'             => $request->city,
            'commune'          => $request->commune,
            'consent_suivi'    => $request->boolean('consent_suivi'),
            'consent_whatsapp' => $request->boolean('consent_whatsapp'),
            'consent_sms'      => $request->boolean('consent_sms'),
            'consent_reseau'   => $request->boolean('consent_reseau'),
            'updated_at'       => now(),
        ]);

        return back()->with('success', 'Patient mis à jour.');
    }

    /**
     * Suppression logique (désactivation)
     */
    public function destroy(string $id)
    {
        DB::table('patients')
            ->where('id_patient', $id)
            ->where('pharmacy_id', session('pharmacy_id'))
            ->update(['active' => 'INACTIVE', 'updated_at' => now()]);

        return redirect()->route('patients.index')
            ->with('success', 'Patient archivé.');
    }

    /* ══════════════════════════════════════
       HELPERS PRIVÉS
    ══════════════════════════════════════ */

    private function statusClass(string $status): string
    {
        return match ($status) {
            'A_JOUR'        => 'status-ok',
            'EN_RETARD'     => 'status-retard',
            'CRITIQUE'      => 'status-critique',
            'BIENTOT_RETARD' => 'status-bientot',
            default         => 'status-ok',
        };
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'A_JOUR'        => 'À jour',
            'EN_RETARD'     => 'En retard',
            'CRITIQUE'      => 'Critique',
            'BIENTOT_RETARD' => 'Bientôt retard',
            default         => 'À jour',
        };
    }

    private function statusObs(string $status): string
    {
        return match ($status) {
            'A_JOUR'        => 'ajour',
            'EN_RETARD'     => 'retard',
            'CRITIQUE'      => 'critique',
            'BIENTOT_RETARD' => 'bientot',
            default         => 'ajour',
        };
    }

    private function avatarBg(string $letter): string
    {
        $map = [
            'A' => '#fce4ec',
            'B' => '#e8eaf6',
            'C' => '#e0f2f1',
            'D' => '#fff3e0',
            'E' => '#fce4ec',
            'F' => '#fce4ec',
            'G' => '#e8eaf6',
            'H' => '#e0f2f1',
            'I' => '#e8eaf6',
            'J' => '#fff3e0',
            'K' => '#e0f2f1',
            'L' => '#fce4ec',
            'M' => '#fce4ec',
            'N' => '#e8eaf6',
            'O' => '#fff3e0',
            'P' => '#e0f2f1',
            'Q' => '#fce4ec',
            'R' => '#e8eaf6',
            'S' => '#fff3e0',
            'T' => '#e8eaf6',
            'U' => '#e0f2f1',
            'V' => '#fce4ec',
            'W' => '#fff3e0',
            'X' => '#e8eaf6',
            'Y' => '#e0f2f1',
            'Z' => '#fce4ec',
        ];
        return $map[$letter] ?? '#ecfdf5';
    }

    private function avatarColor(string $letter): string
    {
        $map = [
            'A' => '#c2185b',
            'B' => '#3949ab',
            'C' => '#00695c',
            'D' => '#e65100',
            'E' => '#c2185b',
            'F' => '#c2185b',
            'G' => '#3949ab',
            'H' => '#00695c',
            'I' => '#3949ab',
            'J' => '#e65100',
            'K' => '#00695c',
            'L' => '#c2185b',
            'M' => '#c2185b',
            'N' => '#3949ab',
            'O' => '#e65100',
            'P' => '#00695c',
            'Q' => '#c2185b',
            'R' => '#3949ab',
            'S' => '#e65100',
            'T' => '#3949ab',
            'U' => '#00695c',
            'V' => '#c2185b',
            'W' => '#e65100',
            'X' => '#3949ab',
            'Y' => '#00695c',
            'Z' => '#c2185b',
        ];
        return $map[$letter] ?? '#16a34a';
    }

    public function searchByQr(Request $request)
    {
        $qrCode = trim($request->query('qr_code'));

        if (!$qrCode) {
            return response()->json(['success' => false, 'message' => 'Code QR manquant.'], 400);
        }

        // Recherche du patient actif possédant ce numéro CMU/QR pour la pharmacie actuelle
        $patient = Patient::where('active', 'ACTIVE')
            ->where('qr_code', $qrCode)
            ->first();

        if ($patient) {

            $pharmacyId = Auth::guard('pharmacien')->user()->pharmacy_id;

            // Le patient refuse le partage réseau
            // et il n'appartient pas à la pharmacie connectée
            if (!$patient->consent_reseau && $patient->pharmacy_id != $pharmacyId) {
                return response()->json([
                    'success' => false,
                    'message' => "Ce patient n'a pas autorisé le partage de ses données avec les autres pharmacies du réseau."
                ]);
            }

            return response()->json([
                'success' => true,
                // Génération dynamique de l'URL cible vers le profil
                'redirect_url' => route('patients.show', $patient->id_patient)
            ]);
        }

        // 3. CODE DE DÉBOGUAGE (À vérifier dans vos logs 'storage/logs/laravel.log')
        // On cherche si le patient existe GLOBALEMENT sans contrainte de pharmacie ou de statut
        $patientGlobal = Patient::where('qr_code', $qrCode)->first();

        if ($patientGlobal) {
            // Le patient existe, mais un filtre bloque !
            Log::warning("Patient trouvé globalement mais bloqué par les filtres", [
                'qr_code_recherche'  => $qrCode,
                'patient_pharmacy_id' => $patientGlobal->pharmacy_id,
                'patient_statut'     => $patientGlobal->active
            ]);

            // if ($patientGlobal->pharmacy_id != $pharmacyId) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => "Le patient existe mais appartient à une autre pharmacie (ID: {$patientGlobal->pharmacy_id})."
            //     ]);
            // }

            if ($patientGlobal->active !== 'ACTIVE') {
                return response()->json([
                    'success' => false,
                    'message' => "Le patient existe mais son compte est marqué comme non actif (Statut: {$patientGlobal->active})."
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Aucun patient trouvé avec le numéro social ou QR : ' . $qrCode
        ]);
    }
}
