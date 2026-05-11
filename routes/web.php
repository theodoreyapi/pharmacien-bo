<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MentionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentWaveController;
use App\Http\Controllers\PharmacienController;
use App\Http\Controllers\PharmacienViewController;
use App\Http\Controllers\PharmacyProfileController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RechargementController;
use App\Http\Controllers\ReviewController;
use App\Models\Commune;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('custom-login', [CustomAuthController::class, 'customLogin']);
Route::get('logout', [CustomAuthController::class, 'signOut'])->name('logout');

Route::get('/payment/wave/success/{id}', [PaymentWaveController::class, 'success'])
    ->name('wave.success');
Route::get('/payment/wave/error/{id}', [PaymentWaveController::class, 'error'])
    ->name('wave.error');

Route::get('/', function () {
    if (Auth::guard('pharmacien')->check()) {
        return redirect()->intended('pharma-index');
    }
    return view('auth.sign-in');
});

Route::get('/proxy/pharmacies/{commune}', function ($commune) {
    return Pharmacy::where('commune_id', $commune)->get();
});

// Authentification
Route::get('sign-up', function () {
    return view('auth.sign-up');
});
Route::get('sign-up', function () {
    return view('auth.sign-up');
});
Route::get('forgot', function () {
    return view('auth.forgot-password');
});

// tableau de bord
Route::get('pharma-index', function () {

    if (!Auth::guard('pharmacien')->check()) {
        return redirect()->route('logout');
    }

    $pharmacien = Auth::guard('pharmacien')->user();
    $pharmacyId = $pharmacien->pharmacy_id;
    $username   = $pharmacien->username;
    $currentYear = date('Y');

    // ── Requêtes ──────────────────────────────────────────────────────
    $totalReceived = DB::table('pharmacy_request')
        ->where('pharmacy_id', $pharmacyId)->count();

    $totalSent = DB::table('pharmacy_request')
        ->where('pharmacy_id', $pharmacyId)
        ->whereNotNull('status')->where('status', '!=', 'EN_ATTENTE')->count();

    $totalAcceptees = DB::table('pharmacy_request')
        ->where('pharmacy_id', $pharmacyId)->where('status', 'ACCEPTEE')->count();

    $totalRefusees = DB::table('pharmacy_request')
        ->where('pharmacy_id', $pharmacyId)->where('status', 'REFUSEE')->count();

    $totalEnAttente = DB::table('pharmacy_request')
        ->where('pharmacy_id', $pharmacyId)
        ->where(function ($q) {
            $q->where('status', 'EN_ATTENTE')->orWhereNull('status');
        })->count();

    // ── Réservations ──────────────────────────────────────────────────
    $totalReservations = DB::table('reservation_medicament')
        ->where('pharmacy_id', $pharmacyId)->count();

    $totalReserve = DB::table('reservation_medicament')
        ->where('pharmacy_id', $pharmacyId)->where('status', 'RESERVE')->count();

    $totalServi = DB::table('reservation_medicament')
        ->where('pharmacy_id', $pharmacyId)->where('status', 'SERVI')->count();

    $totalExpire = DB::table('reservation_medicament')
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', 'RESERVE')
        ->where('date_expiration', '<', now())->count();

    // ── Wallet ────────────────────────────────────────────────────────
    $solde = number_format($pharmacien->amount ?? 0, 0, ',', ' ') . ' FCFA';

    $totalCredit = DB::table('transfert')
        ->where('receiver_username', $username)->where('type_operation', 'CREDIT')->sum('amount');

    $totalDebit = DB::table('transfert')
        ->where('sender_username', $username)->where('type_operation', 'DEBIT')->sum('amount');

    $totalRechargements = DB::table('rechargements')
        ->where('username', $username)->where('status', 'success')->sum('montant');

    // ── Avis ──────────────────────────────────────────────────────────
    $allReviews   = DB::table('review')->where('pharmacy_id', $pharmacyId)->get();
    $totalAvis    = $allReviews->count();
    $noteMoyenne  = $totalAvis > 0 ? round($allReviews->avg('evaluation'), 1) : 0;

    // ── Statistiques par mois ─────────────────────────────────────────
    $moisFr = [
        '01' => 'Jan',
        '02' => 'Fév',
        '03' => 'Mar',
        '04' => 'Avr',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juil',
        '08' => 'Août',
        '09' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Déc',
    ];

    $requestsParMois = DB::table('pharmacy_request')
        ->selectRaw("DATE_FORMAT(created_at, '%m') as mois_num, COUNT(*) as total")
        ->where('pharmacy_id', $pharmacyId)->whereYear('created_at', $currentYear)
        ->groupByRaw("DATE_FORMAT(created_at, '%m')")->pluck('total', 'mois_num')->toArray();

    $responsesParMois = DB::table('pharmacy_request')
        ->selectRaw("DATE_FORMAT(updated_at, '%m') as mois_num, COUNT(*) as total")
        ->where('pharmacy_id', $pharmacyId)->whereYear('updated_at', $currentYear)
        ->whereNotNull('status')->where('status', '!=', 'EN_ATTENTE')
        ->groupByRaw("DATE_FORMAT(updated_at, '%m')")->pluck('total', 'mois_num')->toArray();

    $reservationsParMois = DB::table('reservation_medicament')
        ->selectRaw("DATE_FORMAT(date_reservation, '%m') as mois_num, COUNT(*) as total")
        ->where('pharmacy_id', $pharmacyId)->whereYear('date_reservation', $currentYear)
        ->groupByRaw("DATE_FORMAT(date_reservation, '%m')")->pluck('total', 'mois_num')->toArray();

    $souscriptions = [];
    foreach ($moisFr as $num => $label) {
        $souscriptions[] = [
            'mois'             => $label,
            'requestCount'     => (int) ($requestsParMois[$num]     ?? 0),
            'responseCount'    => (int) ($responsesParMois[$num]    ?? 0),
            'reservationCount' => (int) ($reservationsParMois[$num] ?? 0),
        ];
    }

    // ── Rendez-vous Vaccination ─────────────────────────────────────
    $totalAppointments = DB::table('appointments')
        ->where('pharmacy_id', $pharmacyId)
        ->count();

    $totalPendingAppointments = DB::table('appointments')
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', 'pending')
        ->count();

    $totalConfirmedAppointments = DB::table('appointments')
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', 'confirmed')
        ->count();

    $totalCompletedAppointments = DB::table('appointments')
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', 'completed')
        ->count();

    $totalCancelledAppointments = DB::table('appointments')
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', 'cancelled')
        ->count();

    $statistiques = [
        'totalReceived'     => $totalReceived,
        'totalSent'         => $totalSent,
        'totalAcceptees'    => $totalAcceptees,
        'totalRefusees'     => $totalRefusees,
        'totalEnAttente'    => $totalEnAttente,
        'totalReservations' => $totalReservations,
        'totalReserve'      => $totalReserve,
        'totalServi'        => $totalServi,
        'totalExpire'       => $totalExpire,
        'totalCredit'       => number_format($totalCredit, 0, ',', ' '),
        'totalDebit'        => number_format($totalDebit, 0, ',', ' '),
        'totalRechargements' => number_format($totalRechargements, 0, ',', ' '),
        'totalAvis'         => $totalAvis,
        'noteMoyenne'       => $noteMoyenne,

        // Rendez-vous
        'totalAppointments'          => $totalAppointments,
        'totalPendingAppointments'   => $totalPendingAppointments,
        'totalConfirmedAppointments' => $totalConfirmedAppointments,
        'totalCompletedAppointments' => $totalCompletedAppointments,
        'totalCancelledAppointments' => $totalCancelledAppointments,
    ];

    return view('home.pharma-index', compact('statistiques', 'souscriptions', 'solde'));
});

// utilisateurs
Route::get('user-add', function () {
    return view('users.add-user');
});

// reviews
Route::resource('reviews', ReviewController::class);

// Ma pharmacie
Route::get('ma-pharmacie', [PharmacyProfileController::class, 'index'])->name('pharmacie.profil');
Route::post('ma-pharmacie/update', [PharmacyProfileController::class, 'update'])->name('pharmacie.update');

//{{ url()->previous() }}
// pharmacie
Route::get('rechargements', [PharmacienViewController::class, 'rechargements']);
Route::post('rechargements/initier', [RechargementController::class, 'initiatePayment']);

// Requêtes
Route::get('requete', [PharmacienViewController::class, 'requetes']);
Route::post('requete/{id}/accepter', [PharmacienViewController::class, 'accepterRequete']);
Route::post('requete/{id}/refuser',  [PharmacienViewController::class, 'refuserRequete']);

// Réservations
Route::get('reservations', [PharmacienViewController::class, 'reservations']);
Route::post('reservations/{id}/servir', [PharmacienViewController::class, 'servirReservation']);

// Transactions
Route::get('transactions', [PharmacienViewController::class, 'transactions']);

Route::post('/save-fcm-token', [NotificationController::class, 'storeToken']);

// termes
Route::resource('terms-about', AboutController::class);
Route::resource('terms-politicy', PolicyController::class);
Route::resource('terms-mention', MentionController::class);
Route::resource('terms-aide', HelpController::class);
Route::resource('terms-condition', ConditionController::class);

// setting
Route::resource('equipes', PharmacienController::class);
Route::post('profile', [PharmacienController::class, 'profile']);

Route::get('view-profile', function () {
    return view('users.profile');
});
Route::get('add-admin', function () {

    $communes = Commune::orderBy('name', 'ASC')->get();

    return view('users.add-admin', compact('communes'));
});

Route::get(
    '/appointments',
    [AppointmentController::class, 'index']
)->name('appointments.index');

Route::patch(
    '/appointments/{id}/confirm',
    [AppointmentController::class, 'confirm']
)->name('appointments.confirm');

Route::patch(
    '/appointments/{id}/cancel',
    [AppointmentController::class, 'cancel']
)->name('appointments.cancel');

Route::patch(
    '/appointments/{id}/complete',
    [AppointmentController::class, 'complete']
)->name('appointments.complete');
