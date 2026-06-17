@extends('layouts.master', ['title' => 'Ajouter un patient'])

@section('content')
    <style>
        .dash-body {
            margin-left: 150px;
            margin-right: 150px;
            padding: 28px;
            min-height: 100%;
        }

        /* ── Retour ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            margin-bottom: 20px;
            transition: color 0.15s;
        }

        .back-link:hover {
            color: #0f172a;
        }

        /* ── Stepper ── */
        .stepper-card {
            background: white;
            border-radius: 18px;
            padding: 24px 32px;
            margin-bottom: 16px;
        }

        .stepper {
            display: flex;
            align-items: center;
            gap: 0;
            max-width: 500px;
            margin: 0 auto;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .step-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 1;
            border: 2px solid #e2e8f0;
            background: white;
            color: #94a3b8;
            transition: all 0.3s;
        }

        .step-icon.active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .step-icon.done {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            color: #94a3b8;
            transition: color 0.3s;
        }

        .step-label.active {
            color: #16a34a;
        }

        .step-label.done {
            color: #16a34a;
        }

        /* Ligne entre étapes */
        .step-line {
            flex: 1;
            height: 2px;
            background: #e2e8f0;
            margin-bottom: 22px;
            transition: background 0.3s;
        }

        .step-line.done {
            background: #16a34a;
        }

        /* ── Formulaire card ── */
        .form-card {
            background: white;
            border-radius: 18px;
            padding: 28px 32px;
        }

        .form-card h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .form-card .form-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 24px;
        }

        /* ── Inputs ── */
        .field-label {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .field-label .req {
            color: #dc2626;
        }

        .field-label .opt {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-input {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            color: #334155;
            background: #f8fafc;
            outline: none;
            transition: all 0.15s;
            font-family: 'DM Sans', sans-serif;
        }

        .form-input:focus {
            border-color: #16a34a;
            background: white;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .form-input::placeholder {
            color: #cbd5e1;
        }

        /* ── Sexe buttons ── */
        .sexe-group {
            display: flex;
            gap: 10px;
        }

        .sexe-btn {
            flex: 1;
            padding: 10px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s;
            text-align: center;
            font-family: 'DM Sans', sans-serif;
        }

        .sexe-btn:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .sexe-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
            font-weight: 600;
        }

        /* ── Consentement checkboxes ── */
        .consent-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }

        .consent-item:hover {
            border-color: #86efac;
            background: #f0fdf4;
        }

        .consent-item.checked {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .consent-check {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
            transition: all 0.2s;
        }

        .consent-item.checked .consent-check {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .consent-title {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .consent-desc {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ── Rattachement ── */
        .recap-box {
            background: #f8fafc;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 20px;
            border: 1px solid #f1f5f9;
        }

        .recap-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .recap-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .recap-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .recap-phone {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 1px;
        }

        .recap-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #ecfdf5;
            color: #16a34a;
        }

        .pharma-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1.5px solid #16a34a;
            background: #f0fdf4;
            cursor: pointer;
        }

        .pharma-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #16a34a;
            flex-shrink: 0;
        }

        .pharma-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .pharma-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .pharma-check {
            color: #16a34a;
            font-size: 1.3rem;
        }

        /* ── Boutons nav ── */
        .btn-next {
            flex: 1;
            padding: 13px;
            border-radius: 13px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-next:hover {
            background: #15803d;
        }

        .btn-next:disabled {
            background: #86efac;
            cursor: not-allowed;
        }

        .btn-back {
            padding: 13px 22px;
            border-radius: 13px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* ── Pages ── */
        .step-page {
            display: none;
        }

        .step-page.active {
            display: block;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .stepper-card,
            .form-card {
                padding: 20px 18px;
            }

            .sexe-group {
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="dash-body">

        <a href="{{ url('patients') }}" class="back-link">
            <iconify-icon icon="ph:arrow-left-bold"></iconify-icon> Retour à la liste
        </a>

        @include('layouts.statuts')

        {{-- ── Stepper ── --}}
        <div class="stepper-card">
            <div class="stepper">
                <div class="step">
                    <div class="step-icon active" id="icon-1">
                        <iconify-icon icon="ph:user-bold"></iconify-icon>
                    </div>
                    <div class="step-label active" id="label-1">Identité</div>
                </div>
                <div class="step-line" id="line-1"></div>
                <div class="step">
                    <div class="step-icon" id="icon-2">
                        <iconify-icon icon="ph:shield-check-bold"></iconify-icon>
                    </div>
                    <div class="step-label" id="label-2">Consentement</div>
                </div>
                <div class="step-line" id="line-2"></div>
                <div class="step">
                    <div class="step-icon" id="icon-3">
                        <iconify-icon icon="ph:buildings-bold"></iconify-icon>
                    </div>
                    <div class="step-label" id="label-3">Rattachement</div>
                </div>
            </div>
        </div>

        <form action="{{ route('patients.store') }}" method="POST" role="form">
            @csrf

            {{-- ═══════════════════════
         ÉTAPE 1 : Identité
    ═══════════════════════ --}}
            <div class="form-card step-page active" id="page-1">
                <h5>Identité du patient</h5>
                <p class="form-subtitle">Le numéro de téléphone est l'identifiant principal</p>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="field-label">Numéro de sécurité sociale <span class="req">*</span></div>
                        <input type="number" name="social" class="form-input" id="inp-social" placeholder="3845678903210"
                            oninput="checkStep1()">
                    </div>

                    <div class="col-6">
                        <div class="field-label">Téléphone <span class="req">*</span></div>
                        <input type="tel" name="phone_number" class="form-input" id="inp-tel"
                            placeholder="+225 07 00 00 00 00" oninput="checkStep1()">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="field-label">Nom <span class="req">*</span></div>
                        <input type="text" name="first_name" class="form-input" id="inp-nom" placeholder="KONÉ"
                            oninput="checkStep1()">
                    </div>
                    <div class="col-6">
                        <div class="field-label">Prénom <span class="req">*</span></div>
                        <input type="text" name="last_name" class="form-input" id="inp-prenom" placeholder="Fatou"
                            oninput="checkStep1()">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="field-label">Sexe</div>
                    <div class="sexe-group">
                        <button class="sexe-btn" onclick="setSexe(this)">Femme</button>
                        <button class="sexe-btn active" onclick="setSexe(this)">Homme</button>
                        <button class="sexe-btn" onclick="setSexe(this)">Autre</button>
                        <input type="hidden" name="gender" id="gender" value="HOMME">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="field-label">Date de naissance <span class="opt">(optionnel)</span></div>
                        <input type="date" name="birth_date" class="form-input" placeholder="jj/mm/aaaa">
                    </div>
                    <div class="col-6">
                        <div class="field-label">Ville <span class="opt">(optionnel)</span></div>
                        <input type="text" name="city" class="form-input" placeholder="Abidjan">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="field-label">Commune <span class="opt">(optionnel)</span></div>
                    <input type="text" name="commune" class="form-input" placeholder="Cocody, Plateau, Yopougon...">
                </div>

                <button type="button" class="btn-next" id="btn-next-1" onclick="goTo(2)">
                    Suivant : Consentement <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                </button>
            </div>

            {{-- ═══════════════════════
         ÉTAPE 2 : Consentement
    ═══════════════════════ --}}
            <div class="form-card step-page" id="page-2">
                <h5>Consentements du patient</h5>
                <p class="form-subtitle">Le patient doit accepter avant toute communication</p>

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="consent-item" onclick="toggleConsent(this, 'consent_suivi')" id="consent-suivi">
                        <div class="consent-check">
                            <iconify-icon icon="ph:check-bold" style="font-size:12px;"></iconify-icon>
                        </div>
                        <div>
                            <div class="consent-title">J'accepte le suivi santé en pharmacie <span
                                    style="color:#dc2626;">*</span></div>
                            <div class="consent-desc">Permet l'enregistrement des mesures et traitements</div>
                        </div>
                    </div>

                    <div class="consent-item" onclick="toggleConsent(this, 'consent_whatsapp')">
                        <div class="consent-check">
                            <iconify-icon icon="ph:check-bold" style="font-size:12px;"></iconify-icon>
                        </div>
                        <div>
                            <div class="consent-title">J'accepte les rappels WhatsApp</div>
                            <div class="consent-desc">Rappels de renouvellement et conseils santé</div>
                        </div>
                    </div>

                    <div class="consent-item" onclick="toggleConsent(this, 'consent_sms')">
                        <div class="consent-check">
                            <iconify-icon icon="ph:check-bold" style="font-size:12px;"></iconify-icon>
                        </div>
                        <div>
                            <div class="consent-title">J'accepte les rappels SMS</div>
                            <div class="consent-desc">Alertes et notifications par SMS</div>
                        </div>
                    </div>

                    <div class="consent-item" onclick="toggleConsent(this, 'consent_reseau')">
                        <div class="consent-check">
                            <iconify-icon icon="ph:check-bold" style="font-size:12px;"></iconify-icon>
                        </div>
                        <div>
                            <div class="consent-title">J'accepte le partage entre pharmacies</div>
                            <div class="consent-desc">Une pharmacie peut consulter le dossier en cas de besoin
                            </div>
                        </div>
                    </div>
                    {{-- <div class="consent-item" onclick="toggleConsent(this, 'consent_reseau')">
                        <div class="consent-check">
                            <iconify-icon icon="ph:check-bold" style="font-size:12px;"></iconify-icon>
                        </div>
                        <div>
                            <div class="consent-title">J'accepte le partage entre pharmacies affiliées</div>
                            <div class="consent-desc">Une pharmacie partenaire peut consulter le dossier en cas de besoin
                            </div>
                        </div>
                    </div> --}}
                    <input type="hidden" name="consent_suivi" id="consent_suivi" value="0">
                    <input type="hidden" name="consent_whatsapp" id="consent_whatsapp" value="0">
                    <input type="hidden" name="consent_sms" id="consent_sms" value="0">
                    <input type="hidden" name="consent_reseau" id="consent_reseau" value="0">
                </div>

                <div class="d-flex gap-3">
                    <button type="button" class="btn-back" onclick="goTo(1)">
                        <iconify-icon icon="ph:arrow-left-bold"></iconify-icon> Retour
                    </button>
                    <button type="button" class="btn-next" id="btn-next-2" onclick="goTo(3)" disabled>
                        Suivant : Rattachement <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════
         ÉTAPE 3 : Rattachement
    ═══════════════════════ --}}
            <div class="form-card step-page" id="page-3">
                <h5>Rattachement pharmacie</h5>
                <p class="form-subtitle">Définissez la pharmacie principale du patient</p>

                <div class="recap-box">
                    <div class="recap-label">Récapitulatif</div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="recap-avatar" id="recap-avatar">NY</div>
                        <div>
                            <div class="recap-name" id="recap-name">N'GUESSAN YAPI</div>
                            <div class="recap-phone" id="recap-phone">+225 07 78 90 12 34</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2" id="recap-badges">
                        <span class="recap-badge"><iconify-icon icon="ph:check-bold"
                                style="font-size:10px;"></iconify-icon>
                            Suivi</span>
                        <span class="recap-badge"><iconify-icon icon="ph:check-bold"
                                style="font-size:10px;"></iconify-icon>
                            SMS</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="field-label mb-3">Pharmacie principale</div>
                    <div class="pharma-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="pharma-dot"></div>
                            <div>
                                <div class="pharma-name">{{ session('pharmacy_name', 'PharmaConsults') }}
                                </div>
                                <div class="pharma-sub">
                                    {{ session('pharmacy_address', '537, Rue D29 – Abidjan - Côte d’Ivoire') }} ·
                                    Plan Pro · Actif</div>
                            </div>
                        </div>
                        <div class="pharma-check">
                            <iconify-icon icon="ph:check-circle-bold"></iconify-icon>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="button" class="btn-back" onclick="goTo(2)">
                        <iconify-icon icon="ph:arrow-left-bold"></iconify-icon> Retour
                    </button>
                    <button type="submit" class="btn-next">
                        <iconify-icon icon="ph:check-circle-bold"></iconify-icon> Créer le patient
                    </button>
                </div>
            </div>

        </form>

    </div>

    <script>
        let currentStep = 1;

        /* ── Navigation entre étapes ── */
        function goTo(step) {
            document.getElementById('page-' + currentStep).classList.remove('active');
            document.getElementById('page-' + step).classList.add('active');
            updateStepper(step);
            currentStep = step;
            if (step === 3) buildRecap();
        }

        function updateStepper(step) {
            for (let i = 1; i <= 3; i++) {
                const icon = document.getElementById('icon-' + i);
                const label = document.getElementById('label-' + i);
                icon.classList.remove('active', 'done');
                label.classList.remove('active', 'done');
                if (i < step) {
                    icon.classList.add('done');
                    label.classList.add('done');
                } else if (i === step) {
                    icon.classList.add('active');
                    label.classList.add('active');
                }
            }
            if (document.getElementById('line-1'))
                document.getElementById('line-1').classList.toggle('done', step > 1);
            if (document.getElementById('line-2'))
                document.getElementById('line-2').classList.toggle('done', step > 2);
        }

        /* ── Validation étape 1 ── */
        function checkStep1() {
            const tel = document.getElementById('inp-tel').value.trim();
            const social = document.getElementById('inp-social').value.trim();
            const nom = document.getElementById('inp-nom').value.trim();
            const prenom = document.getElementById('inp-prenom').value.trim();
            document.getElementById('btn-next-1').disabled = !(tel && social && nom && prenom);
        }
        checkStep1();

        /* ── Sexe toggle ── */
        function setSexe(btn) {
            event.preventDefault();

            document.querySelectorAll('.sexe-btn')
                .forEach(b => b.classList.remove('active'));

            btn.classList.add('active');

            document.getElementById('gender').value =
                btn.textContent.trim().toUpperCase();
        }

        /* ── Consentement toggle ── */
        function toggleConsent(el, field) {

            el.classList.toggle('checked');

            document.getElementById(field).value =
                el.classList.contains('checked') ? 1 : 0;

            checkConsents();
        }

        function checkConsents() {
            const required = document.getElementById('consent-suivi').classList.contains('checked');
            document.getElementById('btn-next-2').disabled = !required;
        }

        /* ── Récap étape 3 ── */
        function buildRecap() {
            const nom = document.getElementById('inp-nom').value.trim().toUpperCase();
            const prenom = document.getElementById('inp-prenom').value.trim();
            const tel = document.getElementById('inp-tel').value.trim();
            const social = document.getElementById('inp-social').value.trim();

            const initials = (nom[0] || '') + (prenom[0] || '');
            document.getElementById('recap-avatar').textContent = initials;
            document.getElementById('recap-name').textContent = nom + ' ' + prenom.toUpperCase();
            document.getElementById('recap-phone').textContent = tel;
            document.getElementById('recap-social').textContent = social;

            // Badges consentements cochés
            const badges = [];
            document.querySelectorAll('.consent-item.checked .consent-title').forEach(t => {
                const txt = t.textContent.trim().replace('*', '').trim();
                const label = txt.includes('suivi') ? 'Suivi' :
                    txt.includes('WhatsApp') ? 'WhatsApp' :
                    txt.includes('SMS') ? 'SMS' :
                    'Partage';
                badges.push(label);
            });
            document.getElementById('recap-badges').innerHTML = badges.map(b =>
                `<span class="recap-badge"><iconify-icon icon="ph:check-bold" style="font-size:10px;"></iconify-icon> ${b}</span>`
            ).join('');
        }

        /* ── Soumission ── */
        function submitForm() {
            // Remplacer par un vrai submit POST Laravel
            alert('Patient créé avec succès !');
            window.location.href = '{{ route('patients.store') }}';
        }
    </script>
@endsection
