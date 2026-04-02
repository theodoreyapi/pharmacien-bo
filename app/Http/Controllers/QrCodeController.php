<?php

namespace App\Http\Controllers;

use App\Models\Pharmacien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->intended('logout');
        }

        $admins = Pharmacien::join('pharmacy', 'pharmacien.pharmacy_id', '=', 'pharmacy.id_pharmacy')
            ->where('pharmacien.role', '=', 'PHARMACIEN')
            ->select(
                'pharmacien.username',
                'pharmacien.first_name as nomPharmacien',
                'pharmacy.name as nomPharmacy',
                'pharmacy.id_pharmacy as pharmacyId',
            )
            ->get();

        return view('users.qrcode', compact('admins'));
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
