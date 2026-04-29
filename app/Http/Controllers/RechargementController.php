<?php

namespace App\Http\Controllers;

use App\Models\Rechargements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RechargementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // ▶️ INITIATE PAYMENT
    public function initiatePayment(Request $request)
    {
        $rules = [
            'username' => 'required',
            'deposit_amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string',
        ];

        $messages = [
            'username.required' => "Votre session a expiré. Veuillez vous reconnecter",
            'deposit_amount.required' => "Impossible d'avoir votre token",
            'payment_method.required' => "Impossible d'avoir votre token",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => collect($validator->errors()->all()),
            ], 422);
        }

        $user = DB::table('pharmacien')->where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur introuvable.',
            ], 404);
        }

        // ── Vérifier la limite du wallet (200 000 XOF max) ───────────────
        // CORRECTION : la logique max() était incorrecte
        $newAmount = $user->amount + $request->deposit_amount;
        if ($newAmount > 200000) {
            return response()->json([
                'message' => "Le total de votre wallet après rechargement ({$newAmount} XOF) dépasserait la limite de 200 000 XOF. Veuillez saisir un montant inférieur.",
            ], 422);
        }

        try {

            $rechargement = Rechargements::create([
                'username' => $user->username,
                'montant' => $request->deposit_amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'currency' => 'XOF',
                'checkout_session_id' => null, // cos-xxxx
            ]);

            $payload = [
                'amount' => (string) $request->deposit_amount,
                'currency' => 'XOF',
                'success_url' => 'https://pharmacie.pharma-consults.com/payment/wave/success/' . $rechargement->id_rechargement,
                'error_url'   => 'https://pharmacie.pharma-consults.com/payment/wave/error/' . $rechargement->id_rechargement,
                'client_reference' => (string) $user->username,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer wave_ci_prod_tIc5B0OlAxjucp29W83a2YLvua7Z7FOTmAFYtQlONucpqcNHU0TklALECuBP-nf5HL8HkGgopw0UzPFz2aXld43qhMcAwXINng',
                'Content-Type'  => 'application/json',
            ])->post('https://api.wave.com/v1/checkout/sessions', $payload);

            if (!$response->successful()) {
                Log::error('Wave error', $response->json());

                return response()->json([
                    'success' => false,
                    'message' => 'Erreur Wave',
                    'details' => $response->json(),
                ], 500);
            }

            $data = $response->json();

            $rechargement->update([
                'checkout_session_id' => $data['id'],
            ]);

            return response()->json([
                'success' => true,
                'rechargement_url' => $data['wave_launch_url'],
                'rechargement_id' => $rechargement->id_rechargement,
            ]);
        } catch (\Throwable $e) {
            Log::error('Wave Exception', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
            ], 500);
        }
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
