@extends('layouts.master', ['title' => 'Mon Profil'])

@section('content')
    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
            max-width: 680px;
            margin: 0 auto;
        }

        /* ── User hero card ── */
        .user-hero {
            background: white;
            border-radius: 20px;
            padding: 20px 22px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .u-avatar {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #16a34a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .u-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .u-role {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .u-contact {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ── Settings card ── */
        .settings-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        /* ── Accordion items ── */
        .acc-item {
            border-bottom: 1px solid #f8fafc;
        }

        .acc-item:last-child {
            border-bottom: none;
        }

        .acc-trigger {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            gap: 14px;
            cursor: pointer;
            transition: background .15s;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            font-family: 'DM Sans', sans-serif;
        }

        .acc-trigger:hover {
            background: #f8fafc;
        }

        .acc-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #f1f5f9;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            transition: background .2s, color .2s;
        }

        .acc-item.open .acc-icon-wrap {
            background: #ecfdf5;
            color: #16a34a;
        }

        .acc-texts {
            flex: 1;
        }

        .acc-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .acc-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .acc-chevron {
            color: #cbd5e1;
            font-size: 1rem;
            flex-shrink: 0;
            transition: transform .25s;
        }

        .acc-item.open .acc-chevron {
            transform: rotate(180deg);
            color: #16a34a;
        }

        /* ── Accordion panel ── */
        .acc-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            padding: 0 20px;
        }

        .acc-panel.open {
            max-height: 600px;
            padding: 0 20px 20px;
        }

        /* ── Form fields ── */
        .field-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .f-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            font-size: 13px;
            color: #334155;
            background: #f8fafc;
            outline: none;
            transition: border-color .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .f-input:focus {
            border-color: #16a34a;
            background: white;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        /* Password input with eye */
        .pw-wrap {
            position: relative;
        }

        .pw-wrap .f-input {
            padding-right: 44px;
        }

        .pw-eye {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .pw-eye:hover {
            color: #334155;
        }

        /* ── Enregistrer button ── */
        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 11px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
            margin-top: 14px;
        }

        .btn-save:hover {
            background: #15803d;
        }

        /* ── Toggle switch ── */
        .notif-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .notif-row:last-of-type {
            border-bottom: none;
        }

        .notif-label {
            font-size: 13px;
            font-weight: 500;
            color: #334155;
        }

        .toggle-pill {
            width: 46px;
            height: 26px;
            border-radius: 20px;
            background: #d1d5db;
            position: relative;
            cursor: pointer;
            border: none;
            transition: background .2s;
            flex-shrink: 0;
        }

        .toggle-pill::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            transition: left .2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        }

        .toggle-pill.on {
            background: #16a34a;
        }

        .toggle-pill.on::after {
            left: 23px;
        }

        /* ── 2FA block ── */
        .twofa-block {
            background: #eff6ff;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .twofa-title {
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 2px;
        }

        .twofa-sub {
            font-size: 12px;
            color: #3b82f6;
        }

        .twofa-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 10px;
        }

        /* ── Device rows ── */
        .device-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .device-row:last-child {
            border-bottom: none;
        }

        .device-icon {
            color: #94a3b8;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .device-name {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .device-meta {
            font-size: 12px;
            color: #94a3b8;
        }

        .badge-actuel {
            margin-left: auto;
            flex-shrink: 0;
            padding: 3px 12px;
            border-radius: 20px;
            background: #ecfdf5;
            color: #16a34a;
            font-size: 11px;
            font-weight: 700;
        }

        .btn-deconnect-device {
            margin-left: auto;
            flex-shrink: 0;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1.5px solid #fecaca;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #dc2626;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-deconnect-device:hover {
            background: #fef2f2;
        }

        /* ── Se déconnecter ── */
        .btn-logout {
            width: 100%;
            padding: 14px;
            border-radius: 16px;
            border: 1.5px solid #fecaca;
            background: white;
            font-size: 13px;
            font-weight: 700;
            color: #dc2626;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            margin-bottom: 16px;
        }

        .btn-logout:hover {
            background: #fef2f2;
        }

        /* ── Footer ── */
        .app-footer {
            text-align: center;
            font-size: 12px;
            color: #cbd5e1;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }
        }
    </style>

    <div class="dash-body">

        {{-- ── User hero ── --}}
        <div class="user-hero">
            <div class="u-avatar">
                {{ strtoupper(substr(Auth::guard('pharmacien')->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::guard('pharmacien')->user()->last_name ?? 'K', 0, 1)) }}
            </div>
            <div>
                <div class="u-name">
                    {{ Auth::guard('pharmacien')->user()->first_name ?? 'Amara' }}
                    {{ Auth::guard('pharmacien')->user()->last_name ?? 'Koné' }}
                </div>
                <div class="u-role">
                    {{ ucfirst(strtolower(Auth::guard('pharmacien')->user()->role ?? 'Pharmacien')) }}
                    · {{ session('pharmacy_name', 'Pharmacie du Centre Plateau') }}
                </div>
                <div class="u-contact">
                    {{ Auth::guard('pharmacien')->user()->phone_number ?? '+225 07 78 90 12 34' }}
                    · {{ Auth::guard('pharmacien')->user()->email ?? 'amara.kone@pharmacie-plateau.ci' }}
                </div>
            </div>
        </div>

        {{-- ── Settings accordion card ── --}}
        <div class="settings-card">

            {{-- 1. Mon profil --}}
            <div class="acc-item" id="acc-profil">
                <button class="acc-trigger" onclick="toggleAcc('profil')">
                    <div class="acc-icon-wrap">
                        <iconify-icon icon="ph:user-bold"></iconify-icon>
                    </div>
                    <div class="acc-texts">
                        <div class="acc-title">Mon profil</div>
                        <div class="acc-sub">Nom, téléphone, email</div>
                    </div>
                    <iconify-icon icon="ph:caret-down-bold" class="acc-chevron" id="chev-profil"></iconify-icon>
                </button>
                <div class="acc-panel" id="panel-profil">
                    <form action="{{ url('view-profile/update') }}" method="POST">
                        @csrf @method('POST')
                        <div class="row g-3 mb-0">
                            <div class="col-6">
                                <div class="field-lbl">Prénom</div>
                                <input type="text" name="first_name" class="f-input"
                                    value="{{ Auth::guard('pharmacien')->user()->first_name ?? 'Amara' }}">
                            </div>
                            <div class="col-6">
                                <div class="field-lbl">Nom</div>
                                <input type="text" name="last_name" class="f-input"
                                    value="{{ Auth::guard('pharmacien')->user()->last_name ?? 'Koné' }}">
                            </div>
                            <div class="col-6">
                                <div class="field-lbl">Téléphone</div>
                                <input type="text" name="phone" class="f-input"
                                    value="{{ Auth::guard('pharmacien')->user()->phone_number ?? '+225 07 78 90 12 34' }}">
                            </div>
                            <div class="col-6">
                                <div class="field-lbl">Email</div>
                                <input type="email" name="email" class="f-input"
                                    value="{{ Auth::guard('pharmacien')->user()->email ?? 'amara.kone@pharmacie-plateau.ci' }}">
                            </div>
                        </div>
                        <button type="submit" class="btn-save">Enregistrer</button>
                    </form>
                </div>
            </div>

            {{-- 2. Mot de passe --}}
            <div class="acc-item" id="acc-password">
                <button class="acc-trigger" onclick="toggleAcc('password')">
                    <div class="acc-icon-wrap">
                        <iconify-icon icon="ph:lock-bold"></iconify-icon>
                    </div>
                    <div class="acc-texts">
                        <div class="acc-title">Mot de passe</div>
                        <div class="acc-sub">Modifier le mot de passe</div>
                    </div>
                    <iconify-icon icon="ph:caret-down-bold" class="acc-chevron" id="chev-password"></iconify-icon>
                </button>
                <div class="acc-panel" id="panel-password">
                    <form action="{{ url('view-profile/change-password') }}" method="POST">
                        @csrf @method('POST')
                        <div class="mb-3">
                            <div class="field-lbl">Mot de passe actuel</div>
                            <div class="pw-wrap">
                                <input type="password" name="current_password" class="f-input" id="pw-current"
                                    value="········">
                                <button type="button" class="pw-eye" onclick="togglePw('pw-current', this)">
                                    <iconify-icon icon="ph:eye-bold"></iconify-icon>
                                </button>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="field-lbl">Nouveau mot de passe</div>
                            <div class="pw-wrap">
                                <input type="password" name="new_password" class="f-input" id="pw-new" value="········">
                                <button type="button" class="pw-eye" onclick="togglePw('pw-new', this)">
                                    <iconify-icon icon="ph:eye-bold"></iconify-icon>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-save">Changer le mot de passe</button>
                    </form>
                </div>
            </div>

            {{-- 3. Notifications --}}
            <div class="acc-item" id="acc-notif">
                <button class="acc-trigger" onclick="toggleAcc('notif')">
                    <div class="acc-icon-wrap">
                        <iconify-icon icon="ph:bell-bold"></iconify-icon>
                    </div>
                    <div class="acc-texts">
                        <div class="acc-title">Notifications</div>
                        <div class="acc-sub">Gérer les alertes</div>
                    </div>
                    <iconify-icon icon="ph:caret-down-bold" class="acc-chevron" id="chev-notif"></iconify-icon>
                </button>
                <div class="acc-panel" id="panel-notif">
                    @foreach ([['Nouvelles alertes cliniques', true], ['Renouvellements en retard', true], ['Nouveau patient ajouté', false], ['Statut des messages envoyés', true], ['Campagnes en cours', false]] as [$label, $on])
                        <div class="notif-row">
                            <span class="notif-label">{{ $label }}</span>
                            <button class="toggle-pill {{ $on ? 'on' : '' }}"
                                onclick="this.classList.toggle('on')"></button>
                        </div>
                    @endforeach
                    <button class="btn-save">Enregistrer</button>
                </div>
            </div>

            {{-- 4. Sécurité & 2FA --}}
            <div class="acc-item" id="acc-2fa">
                <button class="acc-trigger" onclick="toggleAcc('2fa')">
                    <div class="acc-icon-wrap">
                        <iconify-icon icon="ph:shield-check-bold"></iconify-icon>
                    </div>
                    <div class="acc-texts">
                        <div class="acc-title">Sécurité & 2FA</div>
                        <div class="acc-sub">Authentification à deux facteurs</div>
                    </div>
                    <iconify-icon icon="ph:caret-down-bold" class="acc-chevron" id="chev-2fa"></iconify-icon>
                </button>
                <div class="acc-panel" id="panel-2fa">
                    <div class="twofa-block">
                        <div>
                            <div class="twofa-title">Authentification 2FA</div>
                            <div class="twofa-sub">Via OTP SMS au
                                {{ Auth::guard('pharmacien')->user()->phone_number ?? '+225 07 78 90 12 34' }}</div>
                        </div>
                        <button class="toggle-pill on" onclick="this.classList.toggle('on')"></button>
                    </div>
                    <div class="twofa-desc">La 2FA est activée pour votre compte. Un code OTP vous est envoyé à chaque
                        connexion.</div>
                </div>
            </div>

            {{-- 5. Appareils connectés --}}
            <div class="acc-item" id="acc-devices">
                <button class="acc-trigger" onclick="toggleAcc('devices')">
                    <div class="acc-icon-wrap">
                        <iconify-icon icon="ph:device-mobile-bold"></iconify-icon>
                    </div>
                    <div class="acc-texts">
                        <div class="acc-title">Appareils connectés</div>
                        <div class="acc-sub">Sessions actives</div>
                    </div>
                    <iconify-icon icon="ph:caret-down-bold" class="acc-chevron" id="chev-devices"></iconify-icon>
                </button>
                <div class="acc-panel" id="panel-devices">
                    <div class="device-row">
                        <iconify-icon icon="ph:device-tablet-bold" class="device-icon"></iconify-icon>
                        <div>
                            <div class="device-name">Tablet Samsung (Chrome)</div>
                            <div class="device-meta">Abidjan, CI · Aujourd'hui 08:42</div>
                        </div>
                        <span class="badge-actuel">Actuel</span>
                    </div>
                    <div class="device-row">
                        <iconify-icon icon="ph:device-mobile-bold" class="device-icon"></iconify-icon>
                        <div>
                            <div class="device-name">iPhone 14 (Safari)</div>
                            <div class="device-meta">Abidjan, CI · Hier 19:15</div>
                        </div>
                        <button class="btn-deconnect-device">Déconnecter</button>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Se déconnecter ── --}}
        <a href="{{ url('logout') }}" class="btn-logout">
            <iconify-icon icon="ph:sign-out-bold"></iconify-icon> Se déconnecter
        </a>

        {{-- ── Footer ── --}}
        <div class="app-footer">Pharmaconsults v2.0.1 · Plateforme de suivi maladies chroniques</div>

    </div>

    <script>
        const SECTIONS = ['profil', 'password', 'notif', '2fa', 'devices'];

        function toggleAcc(id) {
            const item = document.getElementById('acc-' + id);
            const panel = document.getElementById('panel-' + id);
            const isOpen = item.classList.contains('open');

            // Ferme tout
            SECTIONS.forEach(s => {
                document.getElementById('acc-' + s)?.classList.remove('open');
                document.getElementById('panel-' + s)?.classList.remove('open');
            });

            // Ouvre le cliqué si était fermé
            if (!isOpen) {
                item.classList.add('open');
                panel.classList.add('open');
            }
        }

        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
            btn.querySelector('iconify-icon').setAttribute('icon', isText ? 'ph:eye-bold' : 'ph:eye-slash-bold');
        }
    </script>
@endsection
