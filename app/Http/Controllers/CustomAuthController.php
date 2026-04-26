<?php

namespace App\Http\Controllers;

use App\Auth\ApiUser;
use App\Models\Pharmacien;
use App\Models\User;
use App\Models\UsersPharma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.sign-in');
    }

    public function customLogin(Request $request)
    {

        $roles = [
            'email' => 'required',
            'password' => 'required',
        ];
        $customMessages = [
            'email.required' => "Veuillez saisir votre adresse email.",
            'password.required' => "Veuillez saisir votre mot de passe.",
        ];

        $request->validate($roles, $customMessages);

        $user = Pharmacien::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();

        if (!$user || !password_verify($request->password, $user->password)) {
            return back()->withErrors(['E-mail ou mot de passe incorrect.']);
        }

        if ($user->active !== 'ACTIVE') {
            return back()->withErrors(['Votre compte est désactivé. Veuillez contacter l\'administrateur.']);
        }

        // CORRECTION : utiliser le guard 'pharmacien' pour éviter le conflit avec App\Models\User
        Auth::guard('pharmacien')->login($user);

        // Récupérer et stocker le nom de la pharmacie en session
        $pharmacy = DB::table('pharmacy')
            ->where('id_pharmacy', $user->pharmacy_id)
            ->select('id_pharmacy', 'name', 'address', 'facade_image')
            ->first();

        session([
            'pharmacy_id'   => $pharmacy->id_pharmacy ?? null,
            'pharmacy_name' => $pharmacy->name ?? 'Pharmacie',
            'pharmacy_address' => $pharmacy->address ?? '',
            'pharmacy_logo' => $pharmacy->facade_image ?? '',
        ]);

        return match ($user->role) {
            'PHARMACIEN'  => redirect()->intended('pharma-index'),
            'GESTIONNAIRE' => redirect()->intended('requete'),
            'CAISSIERE'   => redirect()->intended('transactions'),
            default       => back()->withErrors(['Rôle utilisateur non reconnu.']),
        };

        /* $response = Http::withOptions([
            'verify' => false
        ])->post(env('API_BASE_URL') . '/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->status() == 200) {
            $data = $response->json();

            if ($data['user']['active'] === 'ACTIVE') {
                $user = new ApiUser($data['user']);

                // Manually create session to ensure it persists
                Auth::login($user); // Second parameter "true" for "remember me"

                // Store token in session
                session([
                    'api_token' => $data['token']['token'],
                    'user_data' => $data['user'] // Optional: Store raw user data
                ]);

                // Regenerate session ID for security
                $request->session()->regenerate();

                if (session('user_data')['role'] == 'ADMIN' || session('user_data')['role'] == 'SUPERADMIN') {
                    return redirect()->intended('index');
                } else if (session('user_data')['role'] == 'PHARMACIEN') {
                    return redirect()->intended('pharma-index');
                } else if (session('user_data')['role'] == 'GESTIONNAIRE') {
                    return redirect()->intended('requete');
                } else if (session('user_data')['role'] == 'CAISSIERE') {
                    return redirect()->intended('transactions');
                }else {
                    return back()->withErrors(['Rôle utilisateur non reconnu.']);
                }
            } else {
                return back()->withErrors(['Votre compte est désactivé. Veuillez contacter l\'administrateur.']);
            }
        } else {
            return back()->withErrors(['Erreur lors de l\'authentification.']);
        }*/
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
