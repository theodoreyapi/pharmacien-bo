<?php

namespace App\Console\Commands;

use App\Models\Planning;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RegenererOccurrencesRdv extends Command
{
    protected $signature = 'rdv:regenerer';
    protected $description = 'Génère les prochaines occurrences de RDV pour les plannings actifs';

    public function handle(): void
    {
        $joursMap = ['Dim' => 0, 'Lun' => 1, 'Mar' => 2, 'Mer' => 3, 'Jeu' => 4, 'Ven' => 5, 'Sam' => 6];

        $plannings = Planning::where('status', 'ACTIF')->get();

        foreach ($plannings as $planning) {
            $dernier = RendezVous::where('planning_id', $planning->id)
                ->orderByDesc('date')
                ->value('date');

            $debut = $dernier ? Carbon::parse($dernier)->addDay() : Carbon::today();
            $fin   = Carbon::today()->addWeeks(4);

            if ($debut->gt($fin)) continue;

            $joursNumeros = collect($planning->jours)
                ->map(fn($j) => $joursMap[$j] ?? null)
                ->filter(fn($n) => $n !== null);

            $date = $debut->copy();
            while ($date->lte($fin)) {
                if ($joursNumeros->contains($date->dayOfWeek)) {
                    RendezVous::firstOrCreate([
                        'planning_id' => $planning->id,
                        'date'        => $date->format('Y-m-d'),
                    ], [
                        'patient_id'    => $planning->patient_id,
                        'pharmacy_id'   => $planning->pharmacy_id,
                        'heure'         => $planning->heure,
                        'mesures_types' => $planning->mesures_types,
                        'status'        => 'ATTENTE',
                    ]);
                }
                $date->addDay();
            }
        }

        $this->info('Occurrences RDV régénérées avec succès.');
    }
}
