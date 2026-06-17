<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CampagneController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MentionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientActionsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentWaveController;
use App\Http\Controllers\PharmacienController;
use App\Http\Controllers\PharmacienViewController;
use App\Http\Controllers\PharmacyProfileController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RappelController;
use App\Http\Controllers\RechargementController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StatistiqueController;
use App\Models\Commune;
use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('custom-login', [CustomAuthController::class, 'customLogin']);
Route::get('logout', [CustomAuthController::class, 'signOut'])->name('logout');

// Rechargement
Route::get('/payment/wave/success/{id}', [PaymentWaveController::class, 'success'])
    ->name('wave.success');
Route::get('/payment/wave/error/{id}', [PaymentWaveController::class, 'error'])
    ->name('wave.error');

    // Abonnement
Route::get('/abonnement/wave/success/{id}', [PaymentWaveController::class, 'Abonsuccess'])
    ->name('abonnement.wave.success');
Route::get('/abonnement/wave/error/{id}', [PaymentWaveController::class, 'Abonerror'])
    ->name('abonnement.wave.error');

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
Route::get('pharma-index', [DashboardController::class, 'index']);

// utilisateurs
Route::get('user-add', function () {
    return view('users.add-user');
});

// reviews
Route::resource('reviews', ReviewController::class);

// Ma pharmacie
Route::get('ma-pharmacie', [PharmacyProfileController::class, 'index'])->name('pharmacie.profil');
Route::post('ma-pharmacie/update', [PharmacyProfileController::class, 'update'])->name('pharmacie.update');
Route::post('abonnement/payer-wave', [PharmacyProfileController::class, 'checkoutWave']);

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

// Suivi des patients
// Ressource principale (index, create, store, show, edit, update, destroy)
Route::get('/patients/search-qr', [PatientsController::class, 'searchByQr'])->name('patients.search_qr');
Route::resource('patients', PatientsController::class);
// Supposons également que votre route de détail ressemble à ceci :
Route::get('/patients/{id}', [PatientsController::class, 'show'])->name('patients.show');

// ── Actions du dossier patient ──
Route::prefix('patients/{patient}')->name('patients.')->group(function () {

    // Pathologies
    Route::post('pathologie', [PatientActionsController::class, 'storePathologie'])->name('pathologie.store');

    // Traitements
    Route::post('traitement', [PatientActionsController::class, 'storeTraitement'])->name('traitement.store');
    Route::post('traitement/{traitement}/renouveler', [PatientActionsController::class, 'renouvelerTraitement'])->name('traitement.renouveler');

    // Mesures cliniques
    Route::post('mesure', [PatientActionsController::class, 'storeMesure'])->name('mesure.store');

    // Rappels / Messages
    Route::post('rappel', [PatientActionsController::class, 'storeRappel'])->name('rappel.store');
});

Route::resource('rappels', RappelController::class);
Route::resource('messages', MessageController::class);
Route::resource('campagnes', CampagneController::class);
Route::resource('statistiques', StatistiqueController::class);
Route::get('add-patient', function () {
    return view('patients.add-patient');
});
Route::get('company', function () {
    return view('settings.company');
});

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
