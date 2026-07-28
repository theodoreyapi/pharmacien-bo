<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaConsults Pharmacie - Authentification</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('') }}assets/images/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/remixicon.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/lib/apexcharts.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/lib/dataTables.min.css">
    <link rel="stylesheet" href="{{ URL::asset('') }}assets/css/style.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1a30;
            min-height: 100%;
        }

        body {
            margin: 0;
        }

        .container-fluid {
            min-height: 100vh;
        }

        .row.g-0.min-vh-100 {
            min-height: 100vh;
        }

        /* --- SECTION GAUCHE (Présentation) --- */
        .left-panel {
            background: radial-gradient(circle at 20% 20%, #14345a 0%, #06101e 65%);
            min-height: 100vh;
            padding: 3rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(118, 188, 0, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand-logo img {
            width: 170px;
            height: auto;
            display: block;
        }

        .hero-title {
            font-size: clamp(1.9rem, 2.4vw, 2.4rem);
            font-weight: 800;
            line-height: 1.2;
            margin-top: 2rem;
            margin-bottom: 0.4rem;
        }

        .hero-title span {
            color: #76bc00;
        }

        .hero-subtitle {
            color: #64748b;
            font-size: 0.88rem;
            margin-bottom: 1.75rem;
        }

        /* Liste des fonctionnalités */
        .features-list {
            max-width: 440px;
            display: grid;
            gap: 0.6rem;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 0.7rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(2px);
        }

        .feature-icon-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .feature-icon-box {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: #a0aec0;
        }

        .feature-title {
            font-size: 0.86rem;
            font-weight: 600;
            margin-bottom: 1px;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .feature-desc {
            font-size: 0.74rem;
            color: #64748b;
            margin-bottom: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background-color: #76bc00;
            border-radius: 50%;
            box-shadow: 0 0 8px #76bc00;
            flex-shrink: 0;
        }

        .footer-text {
            font-size: 0.72rem;
            color: #475569;
            margin-top: 1.5rem;
        }

        /* --- SECTION DROITE (Formulaire) --- */
        .right-panel {
            background-color: #ffffff;
            min-height: 100vh;
            border-top-left-radius: 32px;
            border-bottom-left-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
        }

        @media (max-width: 991.98px) {
            .right-panel {
                border-radius: 0;
                padding: 2.5rem 1.5rem;
                min-height: 100vh;
            }
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.55rem;
            text-align: center;
            margin-bottom: 4px;
        }

        .login-box .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 1.9rem;
        }

        .form-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 6px;
            display: block;
        }

        /* Inputs personnalisés */
        .custom-input-group {
            position: relative;
            margin-bottom: 1.15rem;
        }

        .custom-input-group input {
            width: 100%;
            height: 48px;
            padding: 10px 46px 10px 44px;
            font-size: 0.92rem;
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            color: #334155;
            transition: all 0.2s ease;
        }

        .custom-input-group input::placeholder {
            color: #b0b9c5;
        }

        .custom-input-group input:focus {
            background-color: #ffffff;
            border-color: #76bc00;
            box-shadow: 0 0 0 3px rgba(118, 188, 0, 0.14);
            outline: none;
        }

        .custom-input-group .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .custom-input-group .btn-toggle-view {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .custom-input-group .btn-toggle-view:hover {
            background: #f1f5f9;
            color: #475569;
        }

        /* Bouton de validation */
        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #76bc00 0%, #5f9600 100%);
            border: none;
            border-radius: 11px;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(118, 188, 0, 0.28);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            margin-top: 0.6rem;
            cursor: pointer;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(118, 188, 0, 0.38);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .trouble-link {
            text-align: center;
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 1.5rem;
        }

        .trouble-link a {
            color: #76bc00;
            text-decoration: none;
            font-weight: 600;
        }

        .trouble-link a:hover {
            text-decoration: underline;
        }

        /* Petits écrans : masquer certaines fonctionnalités superflues */
        @media (max-width: 1199.98px) and (min-width: 992px) {
            .feature-item:nth-child(n+5) {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <div class="col-lg-6 d-none d-lg-flex">
                <div class="left-panel w-100">

                    <div class="brand-logo">
                        <img src="{{ URL::asset('assets/images/PC.png') }}" alt="PharmaConsults">
                    </div>

                    <div>
                        <h3 class="hero-title">La pharmacie<br><span>interconnectée.</span></h3>
                        <p class="hero-subtitle">Système de gestion officinale intégré — OS {{ date('Y') }}</p>

                        <div class="features-list">
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

            <div class="col-lg-6">
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
                                        <i class="ri-mail-line"></i>
                                    </span>
                                    <input type="email" required name="email" placeholder="Ex: nom@pharma.com">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Mot de passe</label>
                                <div class="custom-input-group">
                                    <span class="input-icon">
                                        <i class="ri-lock-password-line"></i>
                                    </span>
                                    <input name="password" required type="password" id="your-password"
                                        placeholder="••••••••">
                                    <button type="button" class="btn-toggle-view toggle-password"
                                        data-toggle="#your-password" aria-label="Afficher le mot de passe">
                                        <i class="ri-eye-off-line"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">
                                Accéder au système <i class="ri-arrow-right-line"></i>
                            </button>

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
                var icon = $(this).find('i');
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("ri-eye-off-line").addClass("ri-eye-line");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("ri-eye-line").addClass("ri-eye-off-line");
                }
            });
        }
        initializePasswordToggle('.toggle-password');
    </script>

</body>

</html>
