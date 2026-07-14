<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Patient;
use App\Models\Rappel;
use App\Services\ReminderDispatchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        $historique = DB::table('rappels')
            ->join('patients', 'patients.id_patient', '=', 'rappels.patient_id')
            ->join('messages', 'messages.id_message', '=', 'rappels.message_id')
            ->select(
                'rappels.*',

                'patients.first_name',
                'patients.last_name',
                'patients.phone_number',

                'messages.channel',
                'messages.category',
                'messages.content'
            )
            ->orderByDesc('rappels.created_at')
            ->paginate(20);

        $envoyes = Rappel::where('status', 'ENVOYE')->where('message_id', '!=', null)->count();
        $livres = Rappel::where('status', 'LIVRE')->where('message_id', '!=', null)->count();
        $lus = Rappel::where('status', 'LU')->where('message_id', '!=', null)->count();
        $echecs = Rappel::where('status', 'ECHEC')->where('message_id', '!=', null)->count();

        $patient = Patient::all();

        return view('messages.messages', compact('historique', 'envoyes', 'livres', 'lus', 'echecs', 'patient'));
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
    // public function store(Request $request)
    // {

    //     $message = Message::create([
    //         'channel' => $request->channel,
    //         'content' => $request->content,
    //         'sending_mode' => 'MANUEL',
    //         'sent_at' => now(),
    //         'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
    //     ]);

    //     foreach ($request->patient_ids as $patientId) {

    //         Rappel::create([
    //             'message_id' => $message->id_message,
    //             'patient_id' => $patientId,
    //             'type' => 'PERSONNALISE',
    //             'channel' => 'WHATSAPP',
    //             'message' => $request->content,
    //             'status' => 'ENVOYE',
    //             'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
    //             'sent_at' => now()
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Message envoyé avec succès');
    // }

    public function store(Request $request, ReminderDispatchService $dispatcher)
    {
        $request->validate([
            'channel'       => 'required|in:WHATSAPP,SMS',
            'content'       => 'required|string|min:5|max:1000',
            'patient_ids'   => 'required|array|min:1',
            'patient_ids.*' => 'exists:patients,id_patient',
        ]);

        $message = Message::create([
            'channel'       => $request->channel,
            'content'       => $request->content,
            'sending_mode'  => 'MANUEL',
            'sent_at'       => now(),
            'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
        ]);

        $envoyes = 0;
        $echecs  = 0;
        $ignores = 0;

        foreach ($request->patient_ids as $patientId) {
            $patient = Patient::find($patientId);
            if (!$patient) continue;

            if ($dispatcher->checkConsent($patient, $request->channel)) {
                $ignores++;
                continue; // patient n'a pas consenti à ce canal
            }

            $rappel = Rappel::create([
                'message_id'    => $message->id_message,
                'patient_id'    => $patientId,
                'type'          => 'PERSONNALISE',
                'channel'       => $request->channel,
                'message'       => $request->content,
                'status'        => 'ENVOYE',
                'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
            ]);

            $dispatcher->send($rappel, $patient);

            $rappel->status === 'ENVOYE' ? $envoyes++ : $echecs++;
        }

        return redirect()->back()->with(
            'success',
            "Message envoyé : $envoyes réussi(s), $echecs échec(s), $ignores ignoré(s) (consentement absent)."
        );
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
