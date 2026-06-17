<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientActionsController extends Controller
{
    /* ══════════════════════════════════════════
       HELPERS PRIVÉS
    ══════════════════════════════════════════ */

    private function pharmacienId(): int
    {
        return Auth::guard('pharmacien')->user()->id_pharmacien;
    }

    private function pharmacyId(): int
    {
        return session('pharmacy_id');
    }

    /** Vérifie que le patient appartient bien à la pharmacie connectée */
    private function authorizePatient(int $patientId): object
    {
        $patient = DB::table('patients')
            ->where('id_patient', $patientId)
            ->where('pharmacy_id', $this->pharmacyId())
            ->first();

        abort_if(!$patient, 403, 'Patient introuvable.');

        return $patient;
    }

    /** Recalcule et met à jour le statut observance du patient */
    private function refreshPatientStatus(int $patientId): void
    {
        // Vérifie s'il y a des traitements en retard
        $lateTreatments = DB::table('traitements')
            ->where('patient_id', $patientId)
            ->where('status', 'ACTIF')
            ->where('estimated_end_date', '<', Carbon::now())
            ->count();

        // Vérifie la dernière mesure
        $lastMeasure = DB::table('mesures')
            ->where('patient_id', $patientId)
            ->orderByDesc('created_at')
            ->value('created_at');

        $daysSinceLastMeasure = $lastMeasure
            ? Carbon::parse($lastMeasure)->diffInDays(now())
            : 999;

        // Vérifie les tensions critiques récentes
        $criticalTension = DB::table('mesures')
            ->where('patient_id', $patientId)
            ->where('type', 'PRESSION_ARTERIELLE')
            ->where(fn($q) => $q->where('systolic', '>', 160)->orWhere('diastolic', '>', 100))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->exists();

        $status = 'A_JOUR';

        if ($criticalTension) {
            $status = 'CRITIQUE';
        } elseif ($lateTreatments > 0 || $daysSinceLastMeasure > 45) {
            $status = 'EN_RETARD';
        } elseif ($daysSinceLastMeasure > 25 || $lateTreatments > 0) {
            $status = 'BIENTOT_RETARD';
        }

        DB::table('patients')
            ->where('id_patient', $patientId)
            ->update(['status' => $status, 'updated_at' => now()]);
    }

    /* ══════════════════════════════════════════
       1. PATHOLOGIES
    ══════════════════════════════════════════ */

    public function storePathologie(Request $request, int $patientId)
    {
        $request->validate([
            'pathologie_id' => 'required|exists:pathologies,id_pathologie',
            'priority'      => 'required|in:FAIBLE,MOYENNE,HAUTE,ELEVEE',
            'start_date'    => 'nullable|date',
            'doctor_name'   => 'nullable|string|max:100',
            'notes'         => 'nullable|string|max:500',
        ]);

        $this->authorizePatient($patientId);

        // Évite les doublons actifs
        $exists = DB::table('patient_pathologies')
            ->where('patient_id', $patientId)
            ->where('pathologie_id', $request->pathologie_id)
            ->where('status', 'ACTIF')
            ->exists();

        if ($exists) {
            return back()
                ->with('error', 'Cette pathologie est déjà active pour ce patient.')
                ->withInput();
        }

        // Récupérer l
        $pathologie = DB::table('pathologies')->where('id_pathologie', $request->pathologie_id)->first();

        DB::table('patient_pathologies')->insert([
            'patient_id'    => $patientId,
            'pathologie_id' => $request->pathologie_id,
            'priority'      => $request->priority,
            'status'        => 'ACTIF',
            'start_date'    => $request->start_date ?? now()->toDateString(),
            'doctor_name'   => $request->doctor_name,
            'notes'         => $request->notes,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Log réseau
        $this->logNetwork($patientId, 'Pathologie ' . $pathologie->name . ' ajoutée');

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', 'Pathologie ajoutée avec succès.')
            ->withFragment('tab-pathologies');
    }

    /* ══════════════════════════════════════════
       2. TRAITEMENTS
    ══════════════════════════════════════════ */

    public function storeTraitement(Request $request, int $patientId)
    {
        $request->validate([
            'medicament_id'      => 'required|exists:medicaments,id_medicament',
            'frequency_per_day'  => 'required|integer|min:1|max:10',
            'dose_per_take'      => 'required|integer|min:1', // Nouvelle validation
            'quantity_delivered' => 'required|integer|min:1',
            'duration_days'      => 'required|integer|min:1',
            'dispensed_at'       => 'required|date',
            'pathologie_id'      => 'nullable|exists:pathologies,id_pathologie',
        ]);

        $this->authorizePatient($patientId);

        // Récupérer le nom du médicament pour l'historique
        $medicament = DB::table('medicaments')->where('id_medicament', $request->medicament_id)->first();

        $durationDays = (int) $request->duration_days;
        $dispensedAt     = Carbon::parse($request->dispensed_at);
        $estimatedEndDate = $dispensedAt->copy()->addDays($durationDays);
        // $reminderDate    = $estimatedEndDate->copy()->subDays(5);

        DB::table('traitements')->insertGetId([
            'medicament_id'      => $request->medicament_id,
            'medication_name'    => $medicament->name, // Stockage du nom au moment de l'action
            'frequency_per_day'  => $request->frequency_per_day,
            'dose_per_take'      => $request->dose_per_take, // Nouveau champ
            'quantity_delivered' => $request->quantity_delivered,
            'duration_days'      => $durationDays,
            'dispensed_at'       => $dispensedAt->toDateString(),
            'estimated_end_date' => $estimatedEndDate->toDateString(),
            'status'             => 'ACTIF',
            'patient_id'         => $patientId,
            'pathologie_id'      => $request->pathologie_id,
            'pharmacien_id'      => $this->pharmacienId(),
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $this->refreshPatientStatus($patientId);
        $this->logNetwork($patientId, 'Traitement ' . $medicament->name . ' ajouté');

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', "Traitement ajouté. Fin estimée : {$estimatedEndDate->format('d/m/Y')}.")
            ->withFragment('tab-traitements');
    }

    /** Renouvellement d'un traitement */
    public function renouvelerTraitement(Request $request, int $patientId, int $traitementId)
    {
        $this->authorizePatient($patientId);

        $ancien = DB::table('traitements')
            ->where('id_traitement', $traitementId)
            ->where('patient_id', $patientId)
            ->first();

        abort_if(!$ancien, 404);

        // Archive l'ancien
        DB::table('traitements')
            ->where('id_traitement', $traitementId)
            ->update(['status' => 'RENOUVELE', 'updated_at' => now()]);

        // Crée un nouveau à partir d'aujourd'hui
        $dispensedAt      = Carbon::today();
        $estimatedEndDate = $dispensedAt->copy()->addDays($ancien->duration_days);

        DB::table('traitements')->insertGetId([
            'medicament_id'      => $ancien->medicament_id,
            'medication_name'    => $ancien->medication_name,
            'dose_per_take'      => $ancien->dose_per_take,
            'frequency_per_day'  => $ancien->frequency_per_day,
            'quantity_delivered' => $ancien->quantity_delivered,
            'duration_days'      => $ancien->duration_days,
            'dispensed_at'       => $dispensedAt->toDateString(),
            'estimated_end_date' => $estimatedEndDate->toDateString(),
            'status'             => 'ACTIF',
            'patient_id'         => $patientId,
            'pathologie_id'      => $ancien->pathologie_id,
            'pharmacien_id'      => $this->pharmacienId(),
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Mise à jour statut patient
        $this->refreshPatientStatus($patientId);

        // Log réseau
        $this->logNetwork($patientId, 'Traitement ' . $ancien->medicament_name . ' renouvelé');

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', "Traitement renouvelé jusqu'au {$estimatedEndDate->format('d/m/Y')}.")
            ->withFragment('tab-traitements');
    }

    /* ══════════════════════════════════════════
       3. MESURES CLINIQUES
    ══════════════════════════════════════════ */

    public function storeMesure(Request $request, int $patientId)
    {
        $this->authorizePatient($patientId);

        $pharmacienId = $this->pharmacienId();
        $mesuresToInsert = [];
        $now = now();

        // ══ 1. SÉCURITÉ & VALIDATION GÉNÉRALE ══
        // On valide de manière conditionnelle : si le premier champ d'un bloc est présent, les autres du même bloc deviennent requis.
        $request->validate([
            'systolic'       => 'nullable|required_with:diastolic|integer|min:50|max:300',
            'diastolic'      => 'nullable|required_with:systolic|integer|min:30|max:200',
            'heart_rate_bpm' => 'nullable|integer|min:30|max:250',
            'glycemia_mmol'  => 'nullable|numeric|min:1|max:50',
            'weight_kg'      => 'nullable|required_with:height_cm|numeric|min:10|max:300',
            'height_cm'      => 'nullable|required_with:weight_kg|numeric|min:50|max:250',
        ]);

        // Base commune pour chaque ligne
        $baseData = [
            'patient_id'    => $patientId,
            'pharmacien_id' => $pharmacienId,
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        // ══ 2. BOUCLE / VÉRIFICATION DES BLOCS DE DONNÉES ══

        // Bloc Pression Artérielle
        if ($request->filled('systolic') && $request->filled('diastolic')) {
            $mesuresToInsert[] = array_merge($baseData, [
                'type'         => 'PRESSION_ARTERIELLE',
                'systolic'     => $request->systolic,
                'diastolic'    => $request->diastolic,
                'status_label' => $this->labelTension($request->systolic, $request->diastolic),
                'comment'      => $request->comment_pa,
            ]);
        }

        // Bloc Fréquence Cardiaque
        if ($request->filled('heart_rate_bpm')) {
            $mesuresToInsert[] = array_merge($baseData, [
                'type'           => 'FREQUENCE_CARDIAQUE',
                'heart_rate_bpm' => $request->heart_rate_bpm,
                'status_label'   => $this->labelPouls($request->heart_rate_bpm),
                'comment'        => $request->comment_fc,
            ]);
        }

        // Bloc Glycémie
        if ($request->filled('glycemia_mmol')) {
            $mesuresToInsert[] = array_merge($baseData, [
                'type'          => 'GLYCEMIE',
                'glycemia_mmol' => $request->glycemia_mmol,
                'is_fasting'    => true,
                'status_label'  => $this->labelGlycemie($request->glycemia_mmol),
                'comment'       => $request->comment_glyc,
            ]);
        }

        // Bloc Poids & IMC
        if ($request->filled('weight_kg') && $request->filled('height_cm')) {
            $imc = round($request->weight_kg / pow($request->height_cm / 100, 2), 1);

            $mesuresToInsert[] = array_merge($baseData, [
                'type'         => 'POIDS_IMC',
                'weight_kg'    => $request->weight_kg,
                'height_cm'    => $request->height_cm,
                'imc'          => $imc,
                'status_label' => $this->labelImc($imc),
                'comment'      => $request->comment_poids,
            ]);

            // Mise à jour de la taille dans la table patients
            DB::table('patients')
                ->where('id_patient', $patientId)
                ->update(['height_cm' => $request->height_cm, 'updated_at' => $now]);
        }

        // ══ 3. INSERTION MULTIPLE VIA UNE BOUCLE ══
        if (empty($mesuresToInsert)) {
            return redirect()->back()->with('error', 'Veuillez remplir au moins une mesure avant d’enregistrer.');
        }

        foreach ($mesuresToInsert as $mesure) {
            DB::table('mesures')->insert($mesure);
        }

        $labels = [
            'PRESSION_ARTERIELLE' => 'Pression artérielle',
            'FREQUENCE_CARDIAQUE' => 'Fréquence cardiaque',
            'GLYCEMIE'            => 'Glycémie',
            'POIDS_IMC'           => 'Poids & IMC',
        ];

        $typesMesures = array_map(function ($mesure) use ($labels) {
            return $labels[$mesure['type']] ?? $mesure['type'];
        }, $mesuresToInsert);

        // Recalcule le statut observance & Logs
        $this->refreshPatientStatus($patientId);
        $this->logNetwork($patientId, count($mesuresToInsert) . ' mesure(s) ajoutée(s) : ' . implode(', ', $typesMesures));

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', 'Les mesures ont été enregistrées avec succès.')
            ->withFragment('tab-mesures');
    }

    /* ══════════════════════════════════════════
       4. RAPPELS / MESSAGES
    ══════════════════════════════════════════ */

    public function storeRappel(Request $request, int $patientId)
    {
        $request->validate([
            'channel' => 'required|in:WHATSAPP,SMS',
            'message' => 'required|string|min:5|max:1000',
        ]);

        $patient = $this->authorizePatient($patientId);

        // Vérifie le consentement selon le canal
        if ($request->channel === 'WHATSAPP' && !$patient->consent_whatsapp) {
            return back()->with('error', 'Le patient n\'a pas consenti aux rappels WhatsApp.');
        }
        if ($request->channel === 'SMS' && !$patient->consent_sms) {
            return back()->with('error', 'Le patient n\'a pas consenti aux rappels SMS.');
        }

        DB::table('rappels')->insert([
            'channel'       => $request->channel,
            'type'          => $request->type ?? 'PERSONNALISE',
            'message'       => $request->message,
            'status'        => 'ENVOYE',
            'sent_at'       => now(),
            'patient_id'    => $patientId,
            'pharmacien_id' => $this->pharmacienId(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Log réseau
        $this->logNetwork($patientId, 'Rappel envoyé via ' . $request->channel);

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', 'Message envoyé via ' . $request->channel . '.')
            ->withFragment('tab-messages');
    }

    /* ══════════════════════════════════════════
       HELPERS LABELS
    ══════════════════════════════════════════ */

    private function labelTension(int $sys, int $dia): string
    {
        if ($sys > 180 || $dia > 120) return 'Critique';
        if ($sys > 160 || $dia > 100) return 'Critique';
        if ($sys > 140 || $dia > 90)  return 'Élevé';
        if ($sys >= 120 && $sys <= 129 && $dia < 80) return 'Élevé normal';
        return 'Normal';
    }

    private function labelPouls(int $bpm): string
    {
        if ($bpm < 60) return 'Bradycardie';
        if ($bpm > 100) return 'Tachycardie';
        return 'Normal';
    }

    private function labelGlycemie(float $mmol): string
    {
        if ($mmol >= 7.0)  return 'Diabète';
        if ($mmol >= 6.1)  return 'Élevé';
        if ($mmol < 4.0)   return 'Hypoglycémie';
        return 'Normal';
    }

    private function labelImc(float $imc): string
    {
        if ($imc < 18.5)  return 'Insuffisance pondérale';
        if ($imc < 25.0)  return 'Normal';
        if ($imc < 30.0)  return 'Surpoids';
        if ($imc < 35.0)  return 'Obésité modérée';
        return 'Obésité sévère';
    }

    private function labelTypeMesure(string $type): string
    {
        return match ($type) {
            'PRESSION_ARTERIELLE' => 'tension',
            'FREQUENCE_CARDIAQUE' => 'pouls',
            'GLYCEMIE'            => 'glycémie',
            'POIDS_IMC'           => 'poids/IMC',
            default               => 'clinique',
        };
    }

    /** Enregistre une entrée dans le journal réseau */
    private function logNetwork(int $patientId, string $action): void
    {
        DB::table('network_logs')->insert([
            'action'        => $action,
            'patient_id'    => $patientId,
            'pharmacy_id'   => $this->pharmacyId(),
            'pharmacien_id' => $this->pharmacienId(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
