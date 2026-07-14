<?php
// app/Console/Commands/EnvoyerRappelsPlanifies.php

namespace App\Console\Commands;

use App\Models\Patient;
use App\Models\Rappel;
use App\Services\ReminderDispatchService;
use Illuminate\Console\Command;

class EnvoyerRappelsPlanifies extends Command
{
    protected $signature = 'rappels:envoyer-planifies';
    protected $description = 'Envoie les rappels planifiés dont la date est échue';

    public function handle(ReminderDispatchService $dispatcher): void
    {
        $rappels = Rappel::where('status', 'PLANIFIE')
            ->where('sent_at', '<=', now())
            ->get();

        foreach ($rappels as $rappel) {
            $patient = Patient::find($rappel->patient_id);
            if (!$patient) continue;

            $rappel->status = 'ENVOYE';
            $rappel->save();

            $dispatcher->send($rappel, $patient);
        }

        $this->info($rappels->count() . ' rappel(s) traité(s).');
    }
}
