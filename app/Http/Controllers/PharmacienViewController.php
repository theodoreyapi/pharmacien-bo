<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacienViewController extends Controller
{
    private function pharmacien()
    {
        return Auth::guard('pharmacien')->user();
    }

    // =========================================================================
    // REQUÊTES
    // =========================================================================

    public function requetes(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->intended('logout');
        }

        $pharmacyId = $this->pharmacien()->pharmacy_id;
        $status     = $request->get('status');
        $search     = $request->get('search');

        $query = DB::table('pharmacy_request')
            ->join('request_medicament', 'pharmacy_request.request_medicament_id', '=', 'request_medicament.id_request')
            ->leftJoin('medicaments', 'request_medicament.medicament_id', '=', 'medicaments.id_medicament')
            ->leftJoin('users_pharma', 'request_medicament.username', '=', 'users_pharma.username')
            ->where('pharmacy_request.pharmacy_id', $pharmacyId)
            ->select(
                'pharmacy_request.id_pharmacy_request',
                'pharmacy_request.status as pharmacy_status',
                'pharmacy_request.created_at',
                'request_medicament.id_request',
                'request_medicament.username',
                'request_medicament.comment',
                'request_medicament.medicament_name',
                'request_medicament.photo',
                'medicaments.name as med_name',
                'medicaments.medicament_picture',
                'users_pharma.first_name',
                'users_pharma.last_name'
            )
            ->orderBy('pharmacy_request.created_at', 'desc');

        if ($status) {
            $query->where('pharmacy_request.status', $status);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('medicaments.name', 'LIKE', "%$search%")
                    ->orWhere('request_medicament.medicament_name', 'LIKE', "%$search%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('pharmacies.requete', compact('requests'));
    }

    public function accepterRequete(int $id)
    {
        DB::table('pharmacy_request')
            ->where('id_pharmacy_request', $id)
            ->where('pharmacy_id', $this->pharmacien()->pharmacy_id)
            ->update(['status' => 'ACCEPTEE', 'updated_at' => now()]);

        // Notifier le patient via FCM
        $req = DB::table('pharmacy_request')
            ->join('request_medicament', 'pharmacy_request.request_medicament_id', '=', 'request_medicament.id_request')
            ->where('pharmacy_request.id_pharmacy_request', $id)
            ->select('request_medicament.username')
            ->first();

        if ($req) {
            try {
                (new \App\Services\FcmService())->sendToUser(
                    $req->username,
                    '✅ Requête acceptée',
                    session('pharmacy_name', 'La pharmacie') . ' a accepté votre demande de médicament.',
                    ['type' => 'REQUETE', 'status' => 'ACCEPTEE']
                );
            } catch (\Throwable $e) {
            }
        }

        return redirect()->back()->with('success', 'Requête acceptée avec succès.');
    }

    public function refuserRequete(int $id)
    {
        DB::table('pharmacy_request')
            ->where('id_pharmacy_request', $id)
            ->where('pharmacy_id', $this->pharmacien()->pharmacy_id)
            ->update(['status' => 'REFUSEE', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Requête refusée.');
    }

    // =========================================================================
    // RÉSERVATIONS
    // =========================================================================

    public function reservations(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->intended('logout');
        }

        $pharmacyId = $this->pharmacien()->pharmacy_id;
        $status     = $request->get('status');

        $query = DB::table('reservation_medicament')
            ->leftJoin('medicaments', 'reservation_medicament.medicament_id', '=', 'medicaments.id_medicament')
            ->leftJoin('users_pharma', 'reservation_medicament.user_name', '=', 'users_pharma.username')
            ->where('reservation_medicament.pharmacy_id', $pharmacyId)
            ->select(
                'reservation_medicament.*',
                'medicaments.name as med_name',
                'medicaments.medicament_picture',
                'users_pharma.first_name',
                'users_pharma.last_name'
            )
            ->orderBy('reservation_medicament.date_reservation', 'desc');

        if ($status) {
            $query->where('reservation_medicament.status', $status);
        }

        $reservations = $query->paginate(15)->withQueryString();

        return view('pharmacies.reservation', compact('reservations'));
    }

    public function servirReservation(int $id)
    {
        DB::table('reservation_medicament')
            ->where('id_reservation', $id)
            ->where('pharmacy_id', $this->pharmacien()->pharmacy_id)
            ->update(['status' => 'SERVI', 'updated_at' => now()]);

        // Notifier le patient
        $res = DB::table('reservation_medicament')->where('id_reservation', $id)->first();
        if ($res) {
            try {
                (new \App\Services\FcmService())->sendToUser(
                    $res->user_name,
                    '💊 Médicament prêt',
                    'Votre réservation a été marquée comme servie par ' . session('pharmacy_name', 'la pharmacie') . '.',
                    ['type' => 'RESERVATION', 'status' => 'SERVI']
                );
            } catch (\Throwable $e) {
            }
        }

        return redirect()->back()->with('success', 'Réservation marquée comme servie.');
    }

    public function rechargements(Request $request)
    {
        $username      = $this->pharmacien()->username;
        $status        = $request->get('status');
        $paymentMethod = $request->get('payment_method');

        $query = DB::table('rechargements')
            ->where('username', $username)
            ->orderBy('created_at', 'desc');

        if ($status)        $query->where('status', $status);
        if ($paymentMethod) $query->where('payment_method', $paymentMethod);

        $rechargements = $query->paginate(15)->withQueryString();

        $allRech   = DB::table('rechargements')->where('username', $username);
        $totalSuccess = (clone $allRech)->where('status', 'success')->sum('montant');
        $countSuccess = (clone $allRech)->where('status', 'success')->count();
        $countPending = (clone $allRech)->where('status', 'pending')->count();
        $countFailed  = (clone $allRech)->where('status', 'failed')->count();

        return view('pharmacies.rechargement', compact(
            'rechargements',
            'totalSuccess',
            'countSuccess',
            'countPending',
            'countFailed'
        ));
    }

    // =========================================================================
    // TRANSACTIONS
    // =========================================================================

    public function transactions(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->intended('logout');
        }

        $username       = $this->pharmacien()->username;
        $pharmacien     = $this->pharmacien();
        $typeOperation  = $request->get('type_operation');

        // Récupérer toutes les transactions depuis la table transfert
        $query = DB::table('transfert')
            ->where(function ($q) use ($username) {
                $q->where('sender_username', $username)
                    ->orWhere('receiver_username', $username);
            })
            ->orderBy('created_at', 'desc');

        if ($typeOperation) {
            if ($typeOperation === 'CREDIT') {
                $query->where('receiver_username', $username)->where('type_operation', 'CREDIT');
            } else {
                $query->where('sender_username', $username)->where('type_operation', 'DEBIT');
            }
        }

        $rawTransactions = $query->paginate(20)->withQueryString();

        // Formater pour la vue
        $transactions = $rawTransactions->through(function ($t) use ($username) {
            $isDebit          = $t->sender_username === $username;
            $interlocuteur    = $isDebit ? $t->receiver_username : $t->sender_username;
            $interlocuteurNom = $this->resolveDisplayName($interlocuteur);

            return [
                'id'               => $t->id_transfert,
                'amount'           => $t->amount,
                'typeOperation'    => $isDebit ? 'DEBIT' : 'CREDIT',
                'category'         => 'TRANSFERT',
                'interlocuteurNom' => $interlocuteurNom,
                'date'             => $t->created_at,
            ];
        });

        $totalCredit = DB::table('transfert')
            ->where('receiver_username', $username)
            ->where('type_operation', 'CREDIT')
            ->sum('amount');

        $totalDebit = DB::table('transfert')
            ->where('sender_username', $username)
            ->where('type_operation', 'DEBIT')
            ->sum('amount');

        return view('pharmacies.transaction', compact('transactions', 'pharmacien', 'totalCredit', 'totalDebit'));
    }

    private function resolveDisplayName(string $username): string
    {
        $user = DB::table('users_pharma')
            ->where('username', $username)
            ->select('first_name', 'last_name')
            ->first();

        if ($user) return trim($user->first_name . ' ' . $user->last_name) ?: $username;

        $pharmacien = DB::table('pharmacien')
            ->leftJoin('pharmacy', 'pharmacien.pharmacy_id', '=', 'pharmacy.id_pharmacy')
            ->where('pharmacien.username', $username)
            ->select('pharmacy.name as pharmacy_name')
            ->first();

        return $pharmacien->pharmacy_name ?? $username;
    }
}
