<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PharmacyProfileController extends Controller
{
    /**
     * GET /ma-pharmacie
     */
    public function index()
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
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

        // 1. Récupérer la pharmacie de l'utilisateur connecté
        $pharmacie = DB::table('pharmacy')->where('id_pharmacy', $pharmacyId)->first();

        // 2. Déterminer la fin de la période d'essai (1 an après la création de la pharmacie ou du pharmacien)
        $dateCreation = Carbon::parse($pharmacie->created_at);
        $finEssai = $dateCreation->copy()->addYear();
        $joursRestantsEssai = Carbon::now()->diffInDays($finEssai, false);
        $estEnPeriodeEssai = $joursRestantsEssai > 0;

        // 3. Récupérer l'abonnement en cours
        $abonnement = DB::table('abonnements')
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', 'ACTIF')
            ->first();

        // 4. Calculer les statistiques réelles d'utilisation
        $totalPatients = DB::table('patients')->where('pharmacy_id', $pharmacyId)->count(); // À adapter selon votre structure
        $totalEquipe = DB::table('pharmacien')->where('pharmacy_id', $pharmacyId)->count();

        // Simulations pour les compteurs mensuels (à remplacer par vos requêtes réelles de logs)
        $totalMessagesCeMois = 0;
        $totalCampagnesCeMois = 0;

        return view('pharmacies.ma-pharmacie', compact(
            'pharmacy',
            'communes',
            'assurances',
            'paymentMethods',
            'pharmacie',
            'abonnement',
            'estEnPeriodeEssai',
            'finEssai',
            'joursRestantsEssai',
            'totalPatients',
            'totalEquipe',
            'totalMessagesCeMois',
            'totalCampagnesCeMois',
        ));
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
            'whats_app_phone_number' => 'nullable|string',
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

    public function checkoutWave(Request $request)
    {
        $request->validate([
            'plan_type'  => 'required|in:ESSENTIEL,PRO,EXPERT',
        ]);

        // Vérification du guard
        if (!Auth::guard('pharmacien')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Session expirée. Veuillez vous reconnecter.'
            ], 401);
        }

        $pharmacyId = Auth::guard('pharmacien')->user()->pharmacy_id;

        // Définition des caractéristiques de l'offre choisie
        $plans = [
            'ESSENTIEL' => ['price' => 100000, 'patients' => 300, 'messages' => 2000, 'campaigns' => 5, 'agents' => 3],
            'PRO'       => ['price' => 150000, 'patients' => 1500, 'messages' => 2500, 'campaigns' => 10, 'agents' => 10],
            'EXPERT'    => ['price' => 200000, 'patients' => 5000, 'messages' => 3000, 'campaigns' => 20, 'agents' => 20],
        ];

        $chosenPlan = $plans[$request->plan_type];

        try {
            $abonnement = DB::table('abonnements')->insertGetId([
                'plan_name' => $request->plan_type,
                'price' => $chosenPlan['price'],
                'payment_method' => 'wave',
                'status' => 'SUSPENDU',
                'status_payment' => 'pending',
                'billing_cycle' => 'ANNUEL',
                'max_patients' => $chosenPlan['patients'],
                'max_campaigns' => $chosenPlan['campaigns'],
                'max_team_members' => $chosenPlan['agents'],
                'max_messages_per_month' => $chosenPlan['messages'],
                'pharmacy_id' => $pharmacyId,
                'checkout_session_id' => null, // cos-xxxx
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur BDD Insertion Abonnement : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Impossible de générer la commande en base de données.'
            ], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer wave_ci_prod_tIc5B0OlAxjucp29W83a2YLvua7Z7FOTmAFYtQlONucpqcNHU0TklALECuBP-nf5HL8HkGgopw0UzPFz2aXld43qhMcAwXINng',
            'Content-Type'  => 'application/json',
        ])->post('https://api.wave.com/v1/checkout/sessions', [
            'amount' => $chosenPlan['price'],
            'currency' => 'XOF',
            'success_url' => 'https://pharmacie.pharma-consults.com/abonnement/wave/success/' . $abonnement,
            'error_url'   => 'https://pharmacie.pharma-consults.com/abonnement/wave/error/' . $abonnement,
            'client_reference' => 'PHARMA-' . $pharmacyId . '-' . time(),
        ]);

        if (!$response->successful()) {
            Log::error('Wave error', $response->json());

            return response()->json([
                'success' => false,
                'message' => 'Erreur Wave',
                'details' => $response->json(),
            ], 500);
        }

        $data = $response->json();

        DB::table('abonnements')
            ->where('id_abonnement', $abonnement)
            ->update([
                // Assurez-vous que cette colonne existe ou retirez-la si elle est gérée ailleurs
                'checkout_session_id' => $data['id'],
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'abonnement_url' => $data['wave_launch_url'],
        ]);
    }
}
