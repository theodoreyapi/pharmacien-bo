<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaConsults Pharmacie - Authentification</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('') }}assets/images/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/remixicon.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/lib/apexcharts.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/lib/dataTables.min.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/style.css">

    <style>
        html,
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1a30;
            height: 100%;
            overflow: hidden;
        }

        .container-fluid {
            height: 100vh;
            overflow: hidden;
        }

        .row.g-0.min-vh-100 {
            height: 100vh;
        }

        /* --- SECTION GAUCHE (Présentation) --- */
        .left-panel {
            background: radial-gradient(circle at 20% 30%, #112d4e 0%, #06101e 100%);
            height: 100vh;
            overflow-y: auto;
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 3rem;
            margin-bottom: 0.5rem;
        }

        .hero-title span {
            color: #76bc00;
            /* Vert Maquette */
        }

        .hero-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 3rem;
        }

        /* Liste des fonctionnalités */
        .feature-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.06);
        }

        .feature-icon-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .feature-icon-box {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #a0aec0;
        }

        .feature-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 2px;
            color: #f1f5f9;
        }

        .feature-desc {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background-color: #76bc00;
            border-radius: 50%;
            box-shadow: 0 0 8px #76bc00;
        }

        .footer-text {
            font-size: 0.75rem;
            color: #475569;
            margin-top: 2rem;
        }

        /* --- SECTION DROITE (Formulaire) --- */
        .right-panel {
            background-color: #ffffff;
            height: 100vh;
            overflow-y: auto;
            border-top-left-radius: 40px;
            border-bottom-left-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        @media (max-width: 991.98px) {
            .right-panel {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
                padding: 2rem 1.5rem;
            }
        }

        .login-box {
            width: 100%;
            max-width: 440px;
        }

        .login-box h2 {
            color: #0f172a;
            font-weight: 700;
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 6px;
        }

        .login-box .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2.5rem;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        /* Inputs personnalisés */
        .custom-input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .custom-input-group input {
            width: 100%;
            height: 54px;
            padding: 10px 16px 10px 45px;
            font-size: 0.95rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #334155;
            transition: all 0.2s ease;
        }

        .custom-input-group input:focus {
            background-color: #ffffff;
            border-color: #76bc00;
            box-shadow: 0 0 0 3px rgba(118, 188, 0, 0.15);
            outline: none;
        }

        /* Label ciblé au focus de l'input */
        .custom-input-group input:focus-visible {
            border-color: #76bc00;
        }

        .custom-input-group .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
        }

        .custom-input-group .btn-toggle-view {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
        }

        /* Bouton de validation Vert */
        .btn-submit {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #76bc00 0%, #659e00 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(118, 188, 0, 0.25);
            transition: transform 0.2s, cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 1.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(118, 188, 0, 0.35);
        }

        /* Section multi-profils */
        .profile-section-title {
            font-size: 0.75rem;
            color: #94a3b8;
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .profile-section-title::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 25%;
            height: 1px;
            background-color: #f1f5f9;
        }

        .profile-section-title::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            width: 25%;
            height: 1px;
            background-color: #f1f5f9;
        }

        .profiles-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .profile-badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        /* Variantes de couleurs pour correspondre aux badges de l'image */
        .profile-badge.active-blue {
            background-color: #f0f4ff;
            color: #4f46e5;
            border-color: #e0e7ff;
        }

        .profile-badge.active-green {
            background-color: #f4fbf0;
            color: #659e00;
            border-color: #e6f7df;
        }

        .profile-badge.active-purple {
            background-color: #faf5ff;
            color: #9333ea;
            border-color: #f3e8ff;
        }

        .trouble-link {
            text-align: center;
            font-size: 0.82rem;
            color: #94a3b8;
        }

        .trouble-link a {
            color: #76bc00;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <div class="col-lg-6 d-none d-lg-flex h-100">
                <div class="left-panel w-100">

                    <div class="brand-logo">
                        <img src="{{ URL::asset('assets/images/PC.png') }}" alt="PharmaConsults"
                            style="width: 200px; height: 80px;">
                    </div>

                    <div>
                        <h3 class="hero-title">La pharmacie<br><span>interconnectée.</span></h3>
                        <p class="hero-subtitle">Système de gestion officinale intégré — OS {{ date('Y') }}</p>

                        <div class="features-list" style="max-width: 460px;">
                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    <div class="feature-icon-box"><i class="ri-shopping-cart-2-line"></i></div>
                                    <div>
                                        <div class="feature-title">Ventes & caisse</div>
                                        <p class="feature-desc">Flux de vente temps réel</p>
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    <div class="feature-icon-box"><i class="ri-archive-line"></i></div>
                                    <div>
                                        <div class="feature-title">Stock & traçabilité</div>
                                        <p class="feature-desc">Lots, FEFO, reliquats</p>
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    <div class="feature-icon-box"><i class="ri-user-heart-line"></i></div>
                                    <div>
                                        <div class="feature-title">Patients & ordonnances</div>
                                        <p class="feature-desc">Historique & suivi santé</p>
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    <div class="feature-icon-box"><i class="ri-hand-coin-line"></i></div>
                                    <div>
                                        <div class="feature-title">Comptabilité</div>
                                        <p class="feature-desc">Grand livre & rapports</p>
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    <div class="feature-icon-box"><i class="ri-team-line"></i></div>
                                    <div>
                                        <div class="feature-title">RH & équipe</div>
                                        <p class="feature-desc">Planning & performances</p>
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    <div class="feature-icon-box"><i class="ri-robot-line"></i></div>
                                    <div>
                                        <div class="feature-title">Pharma AI</div>
                                        <p class="feature-desc">Rapports automatiques & analyses</p>
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-text">
                        © {{ date('Y') }} PharmaConsults · OS v2.0.1 · Tous droits réservés
                    </div>
                </div>
            </div>

            <div class="col-lg-6 row-panel-container h-100">
                <div class="right-panel">
                    <div class="login-box">

                        <h2>Connexion</h2>
                        <p class="subtitle">Accédez à votre espace officinal</p>

                        @include('layouts.statuts')

                        <form action="{{ url('custom-login') }}" method="POST" role="form">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Identifiant</label>
                                <div class="custom-input-group">
                                    <span class="input-icon">
                                        <iconify-icon icon="mage:email"></iconify-icon>
                                    </span>
                                    <input type="email" required name="email" placeholder="Ex: nom@pharma.com">
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label">Mot de passe</label>
                                </div>
                                <div class="custom-input-group">
                                    <span class="input-icon">
                                        <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                    </span>
                                    <input name="password" required type="password" id="your-password"
                                        placeholder="••••••••">
                                    <span class="btn-toggle-view toggle-password"
                                        data-toggle="#your-password">Voir</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">
                                Accéder au système <i class="ri-arrow-right-line"></i>
                            </button>

                            {{-- <div class="profile-section-title">Accès multi-profil</div>
                            <div class="profiles-container">
                                <span class="profile-badge active-blue">Titulaire</span>
                                <span class="profile-badge active-blue">Pharmacien</span>
                                <span class="profile-badge active-green">Gestionnaire</span>
                                <span class="profile-badge active-green">Vendeur</span>
                                <span class="profile-badge active-green">Caissier</span>
                                <span class="profile-badge active-green">Comptable</span>
                                <span class="profile-badge active-green">RH</span>
                                <span class="profile-badge active-purple">Préparateur</span>
                                <span class="profile-badge">Auditeur</span>
                            </div> --}}

                            <br>

                            <div class="trouble-link">
                                Problème d'accès ? <a href="{{ url('forgot') }}">Contacter l'administrateur</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ URL::asset('') }}assets/js/lib/jquery-3.7.1.min.js"></script>
    <script src="{{ URL::asset('') }}assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="{{ URL::asset('') }}assets/js/lib/iconify-icon.min.js"></script>
    <script src="{{ URL::asset('') }}assets/js/app.js"></script>

    <script>
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function() {
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    $(this).text("Masquer");
                } else {
                    input.attr("type", "password");
                    $(this).text("Voir");
                }
            });
        }
        initializePasswordToggle('.toggle-password');
    </script>

</body>

</html>
