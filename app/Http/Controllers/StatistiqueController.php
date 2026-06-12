<?php

namespace App\Http\Controllers;

use App\Models\Mesure;
use App\Models\Patient;
use App\Models\Rappel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        $pharmacyId = session('pharmacy_id');
        $now = Carbon::now();
        $debutMois = $now->copy()->startOfMonth();

        // ════════════════════════════════════════════════════════════
        // 1. CALCUL DES KPIS (Filtre par pharmacie)
        // ════════════════════════════════════════════════════════════

        // Patients actifs
        $patientsActifs = Patient::where('pharmacy_id', $pharmacyId)->where('active', 'ACTIVE')->count();
        $nouveauxPatientsCeMois = Patient::where('pharmacy_id', $pharmacyId)
            ->where('created_at', '>=', $debutMois)
            ->count();

        // Taux de renouvellement (Simulé sur base des traitements actifs terminés/renouvelés ou calcul exact)
        $totalTraitementsCeMois = DB::table('traitements')
            ->join('patients', 'traitements.patient_id', '=', 'patients.id_patient')
            ->where('patients.pharmacy_id', $pharmacyId)
            ->where('traitements.dispensed_at', '>=', $debutMois)
            ->count();
        $traitementsRenouveles = DB::table('traitements')
            ->join('patients', 'traitements.patient_id', '=', 'patients.id_patient')
            ->where('patients.pharmacy_id', $pharmacyId)
            ->where('traitements.dispensed_at', '>=', $debutMois)
            ->where('traitements.status', 'RENOUVELÉ')
            ->count();
        $tauxRenouvellement = $totalTraitementsCeMois > 0 ? round(($traitementsRenouveles / $totalTraitementsCeMois) * 100) : 0;

        // Retards critiques
        $retardsCritiques = Patient::where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->where('status', 'CRITIQUE')
            ->count();

        // Rappels envoyés ce mois-ci
        $rappelsEnvoyesMois = Rappel::join('patients', 'rappels.patient_id', '=', 'patients.id_patient')
            ->where('patients.pharmacy_id', $pharmacyId)
            ->where('rappels.created_at', '>=', $debutMois)
            ->count();

        // Mini cards complémentaires
        $totalRappelsCeMois = Rappel::join('patients', 'rappels.patient_id', '=', 'patients.id_patient')
            ->where('patients.pharmacy_id', $pharmacyId)
            ->where('rappels.created_at', '>=', $debutMois)
            ->count();
        $rappelsLusCeMois = Rappel::join('patients', 'rappels.patient_id', '=', 'patients.id_patient')
            ->where('patients.pharmacy_id', $pharmacyId)
            ->where('rappels.created_at', '>=', $debutMois)
            ->where('rappels.status', 'LU')
            ->count();
        $tauxLectureRappels = $totalRappelsCeMois > 0 ? round(($rappelsLusCeMois / $totalRappelsCeMois) * 100) : 0;

        // Patients sans mesures depuis plus de 30 jours
        $patientsSansMesure30j = Patient::where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->whereNotExists(function ($query) use ($now) {
                $query->select(DB::raw(1))
                      ->from('mesures')
                      ->whereColumn('mesures.patient_id', 'patients.id_patient')
                      ->where('mesures.created_at', '>=', Carbon::now()->subDays(30));
            })->count();

        // ════════════════════════════════════════════════════════════
        // 2. DONNÉES HISTORIQUES (6 derniers mois)
        // ════════════════════════════════════════════════════════════
        $monthsLabels = [];
        $datasetPatients = [];
        $datasetMesures = [];
        $datasetRappels = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthObj = Carbon::now()->subMonths($i);
            $monthsLabels[] = $monthObj->translatedFormat('M'); // 'oct.', 'nov.', etc.

            $start = $monthObj->copy()->startOfMonth();
            $end = $monthObj->copy()->endOfMonth();

            $datasetPatients[] = Patient::where('pharmacy_id', $pharmacyId)->where('created_at', '<=', $end)->count();
            $datasetMesures[] = Mesure::join('patients', 'mesures.patient_id', '=', 'patients.id_patient')
                ->where('patients.pharmacy_id', $pharmacyId)->whereBetween('mesures.created_at', [$start, $end])->count();
            $datasetRappels[] = Rappel::join('patients', 'rappels.patient_id', '=', 'patients.id_patient')
                ->where('patients.pharmacy_id', $pharmacyId)->whereBetween('rappels.created_at', [$start, $end])->count();
        }

        // ════════════════════════════════════════════════════════════
        // 3. RÉPARTITION PAR PATHOLOGIE
        // ════════════════════════════════════════════════════════════
        $pathologiesData = DB::table('pathologies')
            ->join('patient_pathologies', 'pathologies.id_pathologie', '=', 'patient_pathologies.pathologie_id')
            ->join('patients', 'patient_pathologies.patient_id', '=', 'patients.id_patient')
            ->where('patients.pharmacy_id', $pharmacyId)
            ->where('patient_pathologies.status', 'ACTIF')
            ->select('pathologies.name', 'pathologies.code', DB::raw('count(distinct patients.id_patient) as total'))
            ->groupBy('pathologies.id_pathologie', 'pathologies.name', 'pathologies.code')
            ->get();

        // ════════════════════════════════════════════════════════════
        // 4. OBSERVANCE GLOBALE (Données Donut)
        // ════════════════════════════════════════════════════════════
        $observanceCounts = Patient::where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusesDef = [
            'A_JOUR'         => ['label' => 'À jour', 'color' => '#16a34a', 'count' => $observanceCounts['A_JOUR'] ?? 0],
            'BIENTOT_RETARD' => ['label' => 'Bientôt retard', 'color' => '#f59e0b', 'count' => $observanceCounts['BIENTOT_RETARD'] ?? 0],
            'EN_RETARD'      => ['label' => 'En retard', 'color' => '#f97316', 'count' => $observanceCounts['EN_RETARD'] ?? 0],
            'CRITIQUE'       => ['label' => 'Critique', 'color' => '#dc2626', 'count' => $observanceCounts['CRITIQUE'] ?? 0],
        ];

        $totalPatientsObs = array_sum(array_column($statusesDef, 'count'));

        // ════════════════════════════════════════════════════════════
        // 5. LISTE DES PATIENTS AVEC RELATIONS
        // ════════════════════════════════════════════════════════════
        $patients = Patient::where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->with(['pathologies', 'mesures' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->get();

        return view('statistics.statistiques', compact(
            'patientsActifs', 'nouveauxPatientsCeMois', 'tauxRenouvellement', 'retardsCritiques',
            'rappelsEnvoyesMois', 'tauxLectureRappels', 'rappelsLusCeMois', 'totalRappelsCeMois',
            'patientsSansMesure30j', 'monthsLabels', 'datasetPatients', 'datasetMesures',
            'datasetRappels', 'pathologiesData', 'statusesDef', 'totalPatientsObs', 'patients'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
