<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        $pharmacyId = session('pharmacy_id');
        $today      = Carbon::today();
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth   = Carbon::now()->endOfMonth();

        /* ══════════════════════════════════════
           KPI CARDS
        ══════════════════════════════════════ */

        // Patients suivis (actifs de la pharmacie)
        $patientsCount = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->count();

        // +N ce mois (nouveaux patients créés ce mois)
        $newThisMonth = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->where('active', 'ACTIVE')
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->count();

        // À relancer : patients dont le dernier rappel date de > 30 jours
        // OU dont un traitement est en retard
        $aRelancer = DB::table('patients as p')
            ->join('traitements as t', 't.patient_id', '=', 'p.id_patient')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('p.active', 'ACTIVE')
            ->where('t.status', 'ACTIF')
            ->where('t.estimated_end_date', '<', $today)
            ->distinct('p.id_patient')
            ->count('p.id_patient');

        // Renouvellements en retard
        $renouvellements = DB::table('traitements as t')
            ->join('patients as p', 'p.id_patient', '=', 't.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('t.status', 'ACTIF')
            ->where('t.estimated_end_date', '<', $today)
            ->count();

        // Critiques = patients avec status CRITIQUE
        $critiques = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', 'CRITIQUE')
            ->count();

        // Mesures aujourd'hui
        // $mesuresAujourdhui = DB::table('mesures as m')
        //     ->join('patients as p', 'p.id_patient', '=', 'm.patient_id')
        //     ->where('p.pharmacy_id', $pharmacyId)
        //     ->whereDate('m.created_at', $today)
        //     ->count();

        $mesuresAujourdhui = DB::table('mesures as m')
            ->join('patients as p', 'p.id_patient', '=', 'm.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->whereDate('m.created_at', $today)
            ->distinct('m.patient_id')
            ->count('m.patient_id');

        /* ══════════════════════════════════════
           PATIENTS À RELANCER (liste)
        ══════════════════════════════════════ */
        $patientsARelancer = DB::table('patients as p')
            ->join('traitements as t', 't.patient_id', '=', 'p.id_patient')
            ->join('patient_pathologies as pp', 'pp.patient_id', '=', 'p.id_patient')
            ->join('pathologies as path', 'path.id_pathologie', '=', 'pp.pathologie_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('p.active', 'ACTIVE')
            ->where('t.status', 'ACTIF')
            ->where('t.estimated_end_date', '<', $today)
            ->select(
                'p.id_patient',
                'p.first_name',
                'p.last_name',
                'p.status',
                DB::raw("CONCAT(UPPER(LEFT(p.first_name,1)), UPPER(LEFT(p.last_name,1))) as initials"),
                DB::raw("GROUP_CONCAT(DISTINCT path.code ORDER BY path.code SEPARATOR ',') as pathologies"),
                DB::raw("DATEDIFF(NOW(), t.estimated_end_date) as days_late")
            )
            ->groupBy('p.id_patient', 'p.first_name', 'p.last_name', 'p.status', 't.estimated_end_date')
            ->orderByDesc('days_late')
            ->limit(5)
            ->get();

        /* ══════════════════════════════════════
           ACTIVITÉ RÉCENTE
        ══════════════════════════════════════ */
        // Mesures récentes
        $recentMesures = DB::table('mesures as m')
            ->join('patients as p', 'p.id_patient', '=', 'm.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->select(
                'm.type',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                'm.created_at'
            )
            ->orderByDesc('m.created_at')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'icon'  => $this->getMesureIcon($item->type),
                    'bg'    => $this->getMesureBg($item->type),
                    'color' => $this->getMesureColor($item->type),
                    'text'  => $this->getMesureLabel($item->type),
                    'sub'   => $item->patient_name,
                    'time'  => Carbon::parse($item->created_at)->format('H:i'),
                ];
            });

        // Rappels récents
        $recentRappels = DB::table('rappels as r')
            ->join('patients as p', 'p.id_patient', '=', 'r.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->select(
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                'r.created_at'
            )
            ->orderByDesc('r.created_at')
            ->limit(2)
            ->get()
            ->map(function ($item) {
                return [
                    'icon'  => 'ph:bell-bold',
                    'bg'    => '#fff7ed',
                    'color' => '#d97706',
                    'text'  => 'Rappel envoyé',
                    'sub'   => $item->patient_name,
                    'time'  => Carbon::parse($item->created_at)->format('H:i'),
                ];
            });

        // Nouveaux patients récents
        $recentPatients = DB::table('patients')
            ->where('pharmacy_id', $pharmacyId)
            ->orderByDesc('created_at')
            ->limit(2)
            ->get()
            ->map(function ($item) {
                return [
                    'icon'  => 'ph:user-plus-bold',
                    'bg'    => '#ecfdf5',
                    'color' => '#16a34a',
                    'text'  => 'Nouveau patient',
                    'sub'   => $item->first_name . ' ' . $item->last_name,
                    'time'  => Carbon::parse($item->created_at)->format('H:i'),
                ];
            });

        // Merge + tri par heure
        $activiteRecente = $recentMesures
            ->concat($recentRappels)
            ->concat($recentPatients)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        /* ══════════════════════════════════════
           ALERTES CLINIQUES
        ══════════════════════════════════════ */
        $alertes = collect();

        // Tensions critiques (sys > 160 ou dia > 100)
        $tensionsCritiques = DB::table('mesures as m')
            ->join('patients as p', 'p.id_patient', '=', 'm.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('m.type', 'PRESSION_ARTERIELLE')
            ->where(function ($q) {
                $q->where('m.systolic', '>', 160)
                    ->orWhere('m.diastolic', '>', 100);
            })
            ->whereDate('m.created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                'p.id_patient',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                DB::raw("CONCAT(m.systolic,'/',m.diastolic,' mmHg') as valeur"),
                'm.created_at'
            )
            ->orderByDesc('m.created_at')
            ->limit(2)
            ->get()
            ->map(function ($item) {
                return [
                    'type'    => 'critical',
                    'patient_id' => $item->id_patient,
                    'patient' => $item->patient_name,
                    'niveau'  => 'Critique',
                    'titre'   => 'Tension critique',
                    'desc'    => $item->valeur . ' — Orientation médicale urgente',
                ];
            });

        // Traitements très en retard (> 10 jours)
        $traitRetard = DB::table('traitements as t')
            ->join('patients as p', 'p.id_patient', '=', 't.patient_id')
            ->join('patient_pathologies as pp', 'pp.patient_id', '=', 'p.id_patient')
            ->join('pathologies as path', 'path.id_pathologie', '=', 'pp.pathologie_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('t.status', 'ACTIF')
            ->where('t.estimated_end_date', '<', Carbon::now()->subDays(10))
            ->select(
                'p.id_patient',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                DB::raw("GROUP_CONCAT(DISTINCT path.name SEPARATOR ' + ') as pathologies"),
                DB::raw("DATEDIFF(NOW(), t.estimated_end_date) as days_late")
            )
            ->groupBy('p.id_patient', 'p.first_name', 'p.last_name', 't.estimated_end_date')
            ->orderByDesc('days_late')
            ->limit(2)
            ->get()
            ->map(function ($item) {
                return [
                    'type'    => 'high',
                    'patient_id' => $item->id_patient,
                    'patient' => $item->patient_name,
                    'niveau'  => 'Élevé',
                    'titre'   => 'Renouvellement en retard',
                    'desc'    => $item->pathologies . ' terminé depuis ' . $item->days_late . ' jours',
                ];
            });

        // Perte de suivi (aucune mesure depuis 45j)
        $perteSuivi = DB::table('patients as p')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('p.active', 'ACTIVE')
            ->whereNotExists(function ($query) {
                $query->from('mesures as m')
                    ->whereColumn('m.patient_id', 'p.id_patient')
                    ->where('m.created_at', '>=', Carbon::now()->subDays(45));
            })
            ->select(
                'p.id_patient',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                DB::raw("DATEDIFF(NOW(), (SELECT MAX(created_at) FROM mesures WHERE patient_id = p.id_patient)) as days_no_measure")
            )
            ->limit(2)
            ->get()
            ->map(function ($item) {
                return [
                    'type'    => 'critical',
                    'patient_id' => $item->id_patient,
                    'patient' => $item->patient_name,
                    'niveau'  => 'Critique',
                    'titre'   => 'Perte de suivi',
                    'desc'    => 'Aucune mesure depuis ' . ($item->days_no_measure ?? '?') . ' jours',
                ];
            });

        $alertes = $tensionsCritiques
            ->concat($traitRetard)
            ->concat($perteSuivi)
            ->take(4);

        /* ══════════════════════════════════════
           RÉSUMÉS BAS DE PAGE
        ══════════════════════════════════════ */

        // Rappels en attente aujourd'hui
        $rappelsEnAttente = DB::table('rappels as r')
            ->join('patients as p', 'p.id_patient', '=', 'r.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->whereDate('r.created_at', $today)
            ->where('r.status', 'ENVOYE')
            ->count();

        // Dernière tension enregistrée
        $derniereTension = DB::table('mesures as m')
            ->join('patients as p', 'p.id_patient', '=', 'm.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('m.type', 'PRESSION_ARTERIELLE')
            ->orderByDesc('m.created_at')
            ->select(
                'm.systolic',
                'm.diastolic',
                'm.status_label',
                DB::raw("CONCAT(LEFT(p.last_name,1), '. ', p.first_name) as patient_short")
            )
            ->first();

        // Dernière glycémie enregistrée
        $derniereGlycemie = DB::table('mesures as m')
            ->join('patients as p', 'p.id_patient', '=', 'm.patient_id')
            ->where('p.pharmacy_id', $pharmacyId)
            ->where('m.type', 'GLYCEMIE')
            ->orderByDesc('m.created_at')
            ->select(
                'm.glycemia_mmol',
                'm.status_label',
                DB::raw("CONCAT(LEFT(p.last_name,1), '. ', p.first_name) as patient_short")
            )
            ->first();

        return view('home.pharma-index', compact(
            'patientsCount',
            'newThisMonth',
            'aRelancer',
            'renouvellements',
            'critiques',
            'mesuresAujourdhui',
            'patientsARelancer',
            'activiteRecente',
            'alertes',
            'rappelsEnAttente',
            'derniereTension',
            'derniereGlycemie'
        ));
    }

    /* ── Helpers icônes mesures ── */
    private function getMesureIcon(string $type): string
    {
        return match ($type) {
            'PRESSION_ARTERIELLE' => 'ph:pulse-bold',
            'FREQUENCE_CARDIAQUE' => 'ph:activity-bold',
            'GLYCEMIE'            => 'ph:drop-bold',
            'POIDS_IMC'           => 'ph:scales-bold',
            default               => 'ph:heartbeat-bold',
        };
    }

    private function getMesureBg(string $type): string
    {
        return match ($type) {
            'PRESSION_ARTERIELLE' => '#eff6ff',
            'FREQUENCE_CARDIAQUE' => '#faf5ff',
            'GLYCEMIE'            => '#fff7ed',
            'POIDS_IMC'           => '#eff6ff',
            default               => '#ecfdf5',
        };
    }

    private function getMesureColor(string $type): string
    {
        return match ($type) {
            'PRESSION_ARTERIELLE' => '#2563eb',
            'FREQUENCE_CARDIAQUE' => '#9333ea',
            'GLYCEMIE'            => '#d97706',
            'POIDS_IMC'           => '#2563eb',
            default               => '#16a34a',
        };
    }

    private function getMesureLabel(string $type): string
    {
        return match ($type) {
            'PRESSION_ARTERIELLE' => 'Tension ajoutée',
            'FREQUENCE_CARDIAQUE' => 'Pouls ajouté',
            'GLYCEMIE'            => 'Glycémie ajoutée',
            'POIDS_IMC'           => 'Poids ajouté',
            default               => 'Mesure ajoutée',
        };
    }
}
