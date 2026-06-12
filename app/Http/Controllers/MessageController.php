<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Patient;
use App\Models\Rappel;
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
    public function store(Request $request)
    {

        $message = Message::create([
            'channel' => $request->channel,
            'content' => $request->content,
            'sending_mode' => 'MANUEL',
            'sent_at' => now(),
            'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
        ]);

        foreach ($request->patient_ids as $patientId) {

            Rappel::create([
                'message_id' => $message->id_message,
                'patient_id' => $patientId,
                'type' => 'PERSONNALISE',
                'channel' => 'WHATSAPP',
                'message' => $request->content,
                'status' => 'ENVOYE',
                'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
                'sent_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Message envoyé avec succès');
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
