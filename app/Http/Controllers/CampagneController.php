<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampagneController extends Controller
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

        $campagnes = DB::table('campagnes')
            ->leftJoin('pathologies', 'pathologies.id_pathologie', '=', 'campagnes.pathologie_id')
            ->select(
                'campagnes.*',
                'pathologies.code as pathologie_code',
                'pathologies.name as pathologie_name'
            )
            ->where('campagnes.pharmacy_id', $pharmacyId)
            ->latest('campagnes.created_at')
            ->get();

        $enCours = DB::table('campagnes')
            ->where('campagnes.pharmacy_id', $pharmacyId)
            ->where('status', 'EN_COURS')
            ->count();

        $planifiees = DB::table('campagnes')
            ->where('campagnes.pharmacy_id', $pharmacyId)
            ->where('status', 'PLANIFIE')
            ->count();

        $patientsCibles = DB::table('campagnes')
            ->where('campagnes.pharmacy_id', $pharmacyId)
            ->sum('patients_count');

        $pathologies = DB::table('pathologies')->get();

        return view('campagnes.campagnes', compact(
            'campagnes',
            'enCours',
            'planifiees',
            'patientsCibles',
            'pathologies'
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

        DB::table('campagnes')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'PLANIFIE',
            'portee' => 'MA_PHARMACIE',
            'message_template' => $request->message_template,
            'scheduled_at' => $request->scheduled_at,
            'pathologie_id' => $request->pathologie_id,
            'pharmacy_id' => session('pharmacy_id'),
            'created_by' => Auth::guard('pharmacien')->user()->id_pharmacien,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with(
            'success',
            'Campagne créée avec succès.'
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
