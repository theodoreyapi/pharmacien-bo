<?php

namespace App\Http\Controllers;

use App\Models\Pathologies;
use App\Models\Patient;
use App\Models\Rappel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RappelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        // 1. Récupération des filtres et données pour le formulaire
        $patients = Patient::where('active', 'ACTIVE')
            ->where('pharmacy_id', session('pharmacy_id'))
            ->get();
        $pathologies = Pathologies::all();

        // 2. Calcul des KPI basés sur le statut du Patient ou du Rappel
        // Note: Selon votre schéma, le statut de retard est sur le patient ('status')
        // et le statut de distribution sur le rappel ('status').
        $kpiEnAttente = Rappel::whereNull('sent_at')->count(); // Si vous gérez une planification
        $kpiEnRetard = Patient::where('status', 'EN_RETARD')->count();
        $kpiEnvoyes = Rappel::where('status', 'ENVOYE')->count();

        // 3. Récupération de la liste des rappels avec les relations indispensables
        // On trie par le plus récent
        $reminders = Rappel::with(['patient'])->orderBy('created_at', 'desc')->get();

        return view('rappels.rappels', compact(
            'patients',
            'pathologies',
            'reminders',
            'kpiEnAttente',
            'kpiEnRetard',
            'kpiEnvoyes'
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
        $request->validate([
            'patient_id' => 'required|exists:patients,id_patient',
            'type' => 'required|in:RENOUVELLEMENT,MESURE,CONSEIL,CAMPAGNE,PERSONNALISE',
            'channel' => 'required|in:WHATSAPP,SMS',
            'message' => 'required|string',
            'send_at' => 'nullable|date',
        ]);

        Rappel::create([
            'patient_id' => $request->patient_id,
            'type' => $request->type,
            'channel' => $request->channel,
            'message' => $request->message,
            'status' => 'ENVOYE',
            'sent_at' => $request->send_at ? Carbon::parse($request->send_at) : now(),
            'pharmacien_id' => Auth::guard('pharmacien')->user()->id_pharmacien,
        ]);

        return redirect()->back()->with('success', 'Le rappel a bien été programmé/envoyé.');
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
