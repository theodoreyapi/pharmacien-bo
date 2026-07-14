<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Planning;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        $pharmacyId = session('pharmacy_id');

        /* ── KPIs ── */
        $aujourdHui = RendezVous::where('pharmacy_id', $pharmacyId)
            ->whereDate('date', Carbon::today())
            ->where('status', 'ATTENTE')
            ->count();

        $programmes = RendezVous::where('pharmacy_id', $pharmacyId)
            ->where('status', 'ATTENTE')
            ->where('date', '>=', Carbon::today())
            ->count();

        $manques = RendezVous::where('pharmacy_id', $pharmacyId)
            ->where('status', 'MANQUE')
            ->count();

        /* ── Alerte patients absents (les plus récents) ── */
        $patientsAbsents = RendezVous::where('pharmacy_id', $pharmacyId)
            ->where('status', 'MANQUE')
            ->with('patient')
            ->orderByDesc('date')
            ->limit(6)
            ->get();

        /* ── Onglet Aujourd'hui ── */
        $rdvAujourdHui = RendezVous::where('pharmacy_id', $pharmacyId)
            ->whereDate('date', Carbon::today())
            ->where('status', 'ATTENTE')
            ->with('patient')
            ->orderBy('heure')
            ->get();

        /* ── Onglet À venir ── */
        $rdvAVenir = RendezVous::where('pharmacy_id', $pharmacyId)
            ->where('status', 'ATTENTE')
            ->whereDate('date', '>', Carbon::today())
            ->with('patient')
            ->orderBy('date')->orderBy('heure')
            ->get();

        /* ── Onglet Manqués ── */
        $rdvManques = RendezVous::where('pharmacy_id', $pharmacyId)
            ->where('status', 'MANQUE')
            ->with('patient')
            ->orderByDesc('date')
            ->get();

        /* ── Calendrier : mois affiché ── */
        $mois  = (int) $request->get('mois', Carbon::today()->month);
        $annee = (int) $request->get('annee', Carbon::today()->year);
        $moisCourant = Carbon::createFromDate($annee, $mois, 1);

        $rdvDuMois = RendezVous::where('pharmacy_id', $pharmacyId)
            ->whereYear('date', $moisCourant->year)
            ->whereMonth('date', $moisCourant->month)
            ->get()
            ->groupBy(fn($rdv) => Carbon::parse($rdv->date)->format('Y-m-d'));

        // Grille du calendrier (dimanche → samedi, complète les semaines hors mois)
        $debutGrille = $moisCourant->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $finGrille   = $moisCourant->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $grilleCalendrier = [];
        $curseur = $debutGrille->copy();
        while ($curseur->lte($finGrille)) {
            $key = $curseur->format('Y-m-d');
            $joursRdv = $rdvDuMois->get($key, collect());

            $grilleCalendrier[] = [
                'date'        => $curseur->copy(),
                'in_month'    => $curseur->month === $moisCourant->month,
                'is_today'    => $curseur->isToday(),
                'has_attente' => $joursRdv->contains(fn($r) => $r->status === 'ATTENTE'),
                'has_manque'  => $joursRdv->contains(fn($r) => $r->status === 'MANQUE'),
            ];
            $curseur->addDay();
        }
        $semainesCalendrier = array_chunk($grilleCalendrier, 7);

        /* ── Date sélectionnée pour l'onglet Calendrier ── */
        $dateSelectionnee = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        $rdvDateSelectionnee = RendezVous::where('pharmacy_id', $pharmacyId)
            ->whereDate('date', $dateSelectionnee)
            ->with('patient')
            ->orderBy('heure')
            ->get();

        $tabActif = $request->get('tab', $request->filled('date') ? 'calendrier' : 'aujourdhui');

        /* ── Plannings actifs (sidebar) ── */
        $planningsActifs = Planning::where('pharmacy_id', $pharmacyId)
            ->where('status', 'ACTIF')
            ->with('patient')
            ->orderByDesc('created_at')
            ->get();

        /* ── Patients pour le select du modal ── */
        $patients = Patient::where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->orderBy('first_name')
            ->get();

        return view('rendezvous.index', compact(
            'aujourdHui', 'programmes', 'manques',
            'patientsAbsents',
            'rdvAujourdHui', 'rdvAVenir', 'rdvManques',
            'moisCourant', 'semainesCalendrier',
            'dateSelectionnee', 'rdvDateSelectionnee',
            'tabActif',
            'planningsActifs',
            'patients'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'    => 'required|exists:patients,id_patient',
            'date'          => 'required|date',
            'heure'         => 'required',
            'mesures_types' => 'required|array|min:1',
            'rappel_avant'  => 'nullable|in:24H,2H,1H,AUCUN',
            'canal'         => 'nullable|in:WHATSAPP,SMS',
            'notes'         => 'nullable|string',
        ], [
            'mesures_types.required' => 'Veuillez sélectionner au moins une mesure à effectuer.',
        ]);

        $pharmacyId = session('pharmacy_id');

        $patient = Patient::where('id_patient', $request->patient_id)
            ->where('pharmacy_id', $pharmacyId)
            ->first();
        abort_if(!$patient, 404);

        RendezVous::create([
            'patient_id'    => $request->patient_id,
            'pharmacy_id'   => $pharmacyId,
            'planning_id'   => null,
            'date'          => $request->date,
            'heure'         => $request->heure,
            'mesures_types' => $request->mesures_types,
            'status'        => 'ATTENTE',
            'notes'         => $request->notes,
        ]);

        return back()->with('success', 'Rendez-vous créé avec succès.');
    }

    public function marquer(Request $request, string $rdvId)
    {
        $request->validate(['status' => 'required|in:EFFECTUE,ABSENT']);

        $pharmacyId = session('pharmacy_id');

        $rdv = RendezVous::where('id_rendez_vous', $rdvId)
            ->where('pharmacy_id', $pharmacyId)
            ->first();
        abort_if(!$rdv, 404);

        $rdv->status = $request->status === 'EFFECTUE' ? 'EFFECTUE' : 'MANQUE';
        $rdv->save();

        $label = $rdv->status === 'EFFECTUE' ? 'marqué comme effectué' : 'marqué comme absent';

        return back()->with('success', "RDV $label.");
    }
}
