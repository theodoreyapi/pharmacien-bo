<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Rappel;

class ReminderDispatchService
{
    public function __construct(
        private OrangeSmsService $orangeSms,
        private WhatsAppService $whatsApp
    ) {}

    /**
     * Envoie effectivement le rappel (SMS ou WhatsApp) et met à jour son statut.
     */
    public function send(Rappel $rappel, Patient $patient): Rappel
    {
        if (empty($patient->phone_number)) {
            $rappel->update([
                'status'        => 'ECHEC',
                'error_message' => 'Numéro de téléphone manquant.',
            ]);
            return $rappel;
        }

        $result = $rappel->channel === 'WHATSAPP'
            ? $this->whatsApp->send($patient->phone_number, $rappel->message)
            : $this->orangeSms->send($patient->phone_number, $rappel->message);

        $rappel->update([
            'status'        => $result['success'] ? 'ENVOYE' : 'ECHEC',
            'provider_id'   => $result['provider_id'],
            'error_message' => $result['error'],
            'sent_at'       => $result['success'] ? now() : $rappel->sent_at,
        ]);

        return $rappel;
    }

    /**
     * Vérifie le consentement du patient pour un canal donné.
     * Retourne un message d'erreur ou null si OK.
     */
    public function checkConsent(Patient $patient, string $channel): ?string
    {
        if ($channel === 'WHATSAPP' && !$patient->consent_whatsapp) {
            return "Le patient n'a pas consenti aux rappels WhatsApp.";
        }
        if ($channel === 'SMS' && !$patient->consent_sms) {
            return "Le patient n'a pas consenti aux rappels SMS.";
        }
        return null;
    }
}
