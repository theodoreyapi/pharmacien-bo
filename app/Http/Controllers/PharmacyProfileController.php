<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyProfileController extends Controller
{
    /**
     * GET /ma-pharmacie
     */
    public function index()
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('login');
        }

        $pharmacyId = Auth::guard('pharmacien')->user()->pharmacy_id;

        $pharmacy = DB::table('pharmacy')
            ->join('commune', 'pharmacy.commune_id', '=', 'commune.id_commune')
            ->where('pharmacy.id_pharmacy', $pharmacyId)
            ->select(
                'pharmacy.*',
                'commune.id_commune',
                'commune.name as commune_name'
            )
            ->first();

        $communes = DB::table('commune')->orderBy('name')->get();

        // Assurances liées
        $assurances = DB::table('pharmacy_assurances')
            ->join('assurances', 'pharmacy_assurances.assurance_id', '=', 'assurances.id_assurance')
            ->where('pharmacy_assurances.pharmacy_id', $pharmacyId)
            ->select('assurances.id_assurance', 'assurances.name', 'assurances.assurance_picture')
            ->get();

        // Moyens de paiement liés
        $paymentMethods = DB::table('pharmacy_payment_methods')
            ->join('moyens_paiement', 'pharmacy_payment_methods.payment_method_id', '=', 'moyens_paiement.id_moyen_payment')
            ->where('pharmacy_payment_methods.pharmacy_id', $pharmacyId)
            ->select('moyens_paiement.id_moyen_payment', 'moyens_paiement.name', 'moyens_paiement.payment_method_picture')
            ->get();

        return view('pharmacies.ma-pharmacie', compact('pharmacy', 'communes', 'assurances', 'paymentMethods'));
    }

    /**
     * POST /ma-pharmacie/update
     */
    public function update(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name'                  => 'required|string|max:255',
            'address'               => 'nullable|string',
            'phone_number'          => 'nullable|string',
            'whats_app_phone_number'=> 'nullable|string',
            'opening_hours'         => 'nullable|string',
            'closing_hours'         => 'nullable|string',
            'owner_name'            => 'nullable|string',
            'gps_coordinates'       => 'nullable|string',
            'commune_id'            => 'required|integer',
            'facade_image'          => 'nullable|file|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $pharmacyId = Auth::guard('pharmacien')->user()->pharmacy_id;

        $data = [
            'name'                   => $request->name,
            'address'                => $request->address,
            'phone_number'           => $request->phone_number,
            'whats_app_phone_number' => $request->whats_app_phone_number,
            'opening_hours'          => $request->opening_hours,
            'closing_hours'          => $request->closing_hours,
            'owner_name'             => $request->owner_name,
            'gps_coordinates'        => $request->gps_coordinates,
            'commune_id'             => $request->commune_id,
            'updated_at'             => now(),
        ];

        // Mise à jour de la photo de façade
        if ($request->hasFile('facade_image')) {
            $pharmacy = DB::table('pharmacy')->where('id_pharmacy', $pharmacyId)->first();

            // Supprimer l'ancienne photo
            if ($pharmacy && $pharmacy->facade_image) {
                $oldFileName = basename(parse_url($pharmacy->facade_image, PHP_URL_PATH));
                $oldPath     = public_path('pharmacies/' . $oldFileName);
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $file     = $request->file('facade_image');
            $fileName = uniqid('pharma_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('pharmacies'), $fileName);
            $data['facade_image'] = url('admin/public/pharmacies/' . $fileName);
        }

        DB::table('pharmacy')->where('id_pharmacy', $pharmacyId)->update($data);

        return redirect()->back()->with('success', 'Informations de la pharmacie mises à jour avec succès.');
    }
}
