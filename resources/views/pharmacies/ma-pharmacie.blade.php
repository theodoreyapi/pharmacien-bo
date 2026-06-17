@extends('layouts.master', ['title' => 'Ma Pharmacie'])

@section('content')
    <style>
        .dash-body {
            margin-left: 100px;
            margin-right: 100px;
            padding: 28px;
            min-height: 100%;
        }

        /* ── Section card ── */
        .p-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 16px;
            border: none;
        }

        /* ── Section titles ── */
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .section-title iconify-icon {
            font-size: 1.1rem;
        }

        /* ── Modifier button ── */
        .btn-modifier {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 11px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-modifier:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
        }

        /* ── Pharmacy identity block ── */
        .pharma-identity {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .pharma-logo {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #16a34a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .pharma-name {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .pharma-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-plan {
            display: inline-flex;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #ecfdf5;
            color: #16a34a;
        }

        .pharma-status {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #16a34a;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 24px;
        }

        .info-item-label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .info-item-label iconify-icon {
            font-size: .9rem;
        }

        .info-item-value {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }

        /* ── Abonnement plan card ── */
        .plan-card {
            background: linear-gradient(135deg, #16a34a 0%, #059669 100%);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            color: white;
        }

        .plan-label {
            font-size: 11px;
            font-weight: 600;
            opacity: .75;
            margin-bottom: 4px;
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .plan-renew {
            font-size: 12px;
            opacity: .7;
        }

        .plan-price-label {
            font-size: 11px;
            opacity: .7;
            text-align: right;
            margin-bottom: 4px;
        }

        .plan-price {
            font-size: 1.3rem;
            font-weight: 800;
            text-align: right;
        }

        /* ── Usage stats ── */
        .usage-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .usage-item {}

        .usage-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .usage-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .usage-check {
            color: #16a34a;
            font-size: 1rem;
        }

        .usage-values {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .usage-values span {
            font-weight: 400;
            color: #94a3b8;
        }

        .usage-bar {
            height: 5px;
            border-radius: 10px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .usage-fill {
            height: 100%;
            border-radius: 10px;
            background: #16a34a;
        }

        /* ── Gérer button ── */
        .btn-gerer {
            width: 100%;
            padding: 13px;
            border-radius: 13px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-gerer:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* ── Réseau affilié ── */
        .reseau-desc {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .partner-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
        }

        .partner-row:last-of-type {
            border-bottom: none;
        }

        .partner-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #16a34a;
            flex-shrink: 0;
        }

        .partner-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .partner-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .badge-partenaire {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            flex-shrink: 0;
        }

        /* ── Modal inputs ── */
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

        .field-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .info-grid,
            .usage-grid {
                grid-template-columns: 1fr;
            }

            .plan-card {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>

    <div class="dash-body">

        @include('layouts.statuts')

        {{-- ══ Profil de la pharmacie ══ --}}
        <div class="p-card">
            <div class="section-head">
                <div class="section-title">
                    <iconify-icon icon="ph:buildings-bold" style="color:#16a34a;"></iconify-icon>
                    Profil de la pharmacie
                </div>
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editModal" class="btn-modifier">
                    <iconify-icon icon="ph:pencil-bold"></iconify-icon> Modifier
                </a>
            </div>

            {{-- Identity --}}
            <div class="pharma-identity">
                <div class="pharma-logo">
                    <iconify-icon icon="ph:heart-bold"></iconify-icon>
                </div>
                <div>
                    <div class="pharma-name">{{ $pharmacy->name ?? 'Pharmacie du Centre Plateau' }}</div>
                    <div class="pharma-badges">
                        <span class="badge-plan">Plan Pro</span>
                        <span class="pharma-status">
                            <span class="status-dot"></span>
                            Actif · Code: PCP-001
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info grid --}}
            <div class="info-grid">
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:phone-bold"></iconify-icon> Téléphone
                    </div>
                    <div class="info-item-value">{{ $pharmacy->phone_number ?? '' }}</div>
                </div>
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:envelope-bold"></iconify-icon> Email
                    </div>
                    <div class="info-item-value">{{ $pharmacy->email ?? '' }}</div>
                </div>
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:map-pin-bold"></iconify-icon> Adresse
                    </div>
                    <div class="info-item-value">
                        {{ $pharmacy->address ?? '' }}</div>
                </div>
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:globe-bold"></iconify-icon> Ville
                    </div>
                    <div class="info-item-value">{{ $pharmacy->commune_name ?? 'Abidjan' }}</div>
                </div>
            </div>
        </div>

        {{-- ══ Section Abonnement ══ --}}
        <div class="p-card">
            <div class="section-head mb-3">
                <div class="section-title">
                    <iconify-icon icon="ph:credit-card-bold" style="color:#9333ea;"></iconify-icon>
                    Abonnement
                </div>
            </div>

            @if (!$abonnement && $estEnPeriodeEssai)
                {{-- ─── ÉTAT 1 : BANDEAU ESSAI GRATUIT ─── --}}
                <div class="essai-banner mb-4"
                    style="background: #0061ff; color: white; padding: 20px; border-radius: 12px; position: relative;">
                    <div style="font-size: 13px; opacity: 0.9;">Période d'essai</div>
                    <div style="font-size: 24px; font-weight: 700; margin: 4px 0;">Essai — 1 an gratuit</div>
                    <div style="font-size: 13px; opacity: 0.9;">Essai jusqu'au
                        {{ \Carbon\Carbon::parse($finEssai)->format('d juin Y') }} · {{ $joursRestantsEssai }} jours
                        restants</div>
                    {{-- <button type="button" class="btn-close-essai"
                        style="position: absolute; right: 20px; top: 20px; background: rgba(255,255,255,0.2); border: 1px solid white; color: white; border-radius: 6px; padding: 4px 12px; font-size: 12px;">Fermer</button> --}}
                </div>
                <br>
                {{-- GRILLE DES OFFRES DE SOUSCRIPTION --}}
                {{-- <form action="{{ url('abonnement/payer-wave') }}" method="POST">
                    @csrf --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="plan-selector-box p-3 d-block border rounded text-center" style="cursor:pointer;">
                            <input type="radio" name="plan_type" value="ESSENTIEL" class="d-none">
                            <div class="fw-bold text-dark">Essentiel</div>
                            <div class="text-muted small">100 000 FCFA/an</div>
                            <div class="small mt-2" style="color: #64748b;">300 patients · 3 agents</div>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="plan-selector-box p-3 d-block border rounded text-center" style="cursor:pointer;">
                            <input type="radio" name="plan_type" value="PRO" class="d-none">
                            <div class="fw-bold text-dark">Pro</div>
                            <div class="text-muted small">150 000 FCFA/an</div>
                            <div class="small mt-2" style="color: #64748b;">1 500 patients · 10 agents</div>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="plan-selector-box p-3 d-block border border-success rounded text-center bg-light"
                            style="cursor:pointer; border-width: 2px !important;">
                            <input type="radio" name="plan_type" value="EXPERT" checked class="d-none">
                            <div class="fw-bold text-dark">Expert</div>
                            <div class="text-success small fw-bold">200 000 FCFA/an</div>
                            <div class="small mt-2" style="color: #64748b;">5 000 patients · 20 agents</div>
                        </label>
                    </div>
                </div>

                {{-- FORMULAIRE DE PAIEMENT WAVE --}}
                {{-- <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="field-lbl" style="font-size:12px; color:#64748b; font-weight:600;">Opérateur</div>
                            <select class="f-input f-select" name="operator" disabled>
                                <option value="wave">Wave</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="field-lbl" style="font-size:12px; color:#64748b; font-weight:600;">Numéro de
                                téléphone</div>
                            <input type="text" name="wave_phone" class="f-input" placeholder="0700000000" required>
                        </div>
                    </div> --}}

                {{-- Erreur --}}
                <div id="rechargeError" class="alert alert-danger py-10 px-16 radius-8 text-sm mb-16" style="display:none;">
                </div>
                <br>
                <div class="text-end mb-4">
                    <button type="button" class="btn btn-primary" id="rechargeBtn" onclick="lancerAbonnement()"
                        style="background-color: #22c55e; border: none; font-weight: 600; border-radius: 10px;">
                        Payer <span id="amount-placeholder">200 000</span> FCFA
                    </button>
                </div>
                {{-- </form> --}}
            @else
                {{-- ─── ÉTAT 2 : ABONNEMENT ACTIF (IMAGE 2) ─── --}}
                @php
                    $maxPatients = $abonnement->max_patients ?? 5000;
                    $maxMessages = $abonnement->max_messages_per_month ?? 3000;
                    $maxCampaigns = $abonnement->max_campaigns ?? 20;
                    $maxTeam = $abonnement->max_team_members ?? 20;
                @endphp

                <div class="active-subscription-banner mb-4"
                    style="background: #16a34a; color: white; padding: 20px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 12px; opacity: 0.9;">Abonnement actif</div>
                        <div style="font-size: 26px; font-weight: 800; margin: 2px 0;">
                            {{ $abonnement->plan_name ?? 'Expert' }}</div>
                        <div style="font-size: 13px; opacity: 0.9;">Expire le
                            {{ \Carbon\Carbon::parse($abonnement->renewal_date)->format('d juin Y') }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 20px; font-weight: 700;">
                            {{ number_format($abonnement->price ?? 200000, 0, '.', ' ') }} FCFA/an</div>
                        <button class="btn btn-sm btn-outline-light mt-2"
                            style="font-size: 11px; border-radius: 20px; padding: 2px 12px;">Renouveler / Changer</button>
                    </div>
                </div>

                {{-- JUGES DE PROGRESSION / USAGE GRID --}}
                <div class="usage-grid"
                    style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
                    <div class="usage-item">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 13px; color: #475569;">
                            <span>Patients</span>
                            <span class="fw-bold">{{ $totalPatients }} <span style="color:#94a3b8; font-weight:normal;">/
                                    {{ $maxPatients }}</span></span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ ($totalPatients / $maxPatients) * 100 }}%; border-radius: 10px;"></div>
                        </div>
                    </div>

                    <div class="usage-item">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 13px; color: #475569;">
                            <span>Messages/mois</span>
                            <span class="fw-bold">{{ $totalMessagesCeMois }} <span
                                    style="color:#94a3b8; font-weight:normal;">/ {{ $maxMessages }}</span></span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ ($totalMessagesCeMois / $maxMessages) * 100 }}%; border-radius: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="usage-item">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 13px; color: #475569;">
                            <span>Campagnes/mois</span>
                            <span class="fw-bold">{{ $totalCampagnesCeMois }} <span
                                    style="color:#94a3b8; font-weight:normal;">/ {{ $maxCampaigns }}</span></span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ ($totalCampagnesCeMois / $maxCampaigns) * 100 }}%; border-radius: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="usage-item">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 13px; color: #475569;">
                            <span>Membres équipe</span>
                            <span class="fw-bold">{{ $totalEquipe }} <span style="color:#94a3b8; font-weight:normal;">/
                                    {{ $maxTeam }}</span></span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ ($totalEquipe / $maxTeam) * 100 }}%; border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Bouton Support permanent en bas --}}
            <div class="text-center mt-3">
                <a href="https://wa.me/2250714565080" target="_blank" class="w-100 btn border text-secondary"
                    style="border-radius: 10px; font-size: 14px; font-weight: 600; background: #fff;">
                    <iconify-icon icon="ph:star-bold" style="vertical-align: middle; margin-right: 5px;"></iconify-icon>
                    Contacter le support PharmaConsults
                </a>
            </div>
        </div>

        <script>
            // Variable globale pour stocker le plan sélectionné (Expert par défaut selon la maquette)
            let currentPlan = "EXPERT";
            let currentAmount = 200000;

            document.addEventListener("DOMContentLoaded", function() {
                // 1. Gestion du changement de plan au clic sur les cartes
                document.querySelectorAll('.plan-selector-box').forEach(box => {
                    box.addEventListener('click', function() {
                        // Reset des styles sur toutes les cartes
                        document.querySelectorAll('.plan-selector-box').forEach(b => {
                            b.classList.remove('border-success', 'badge-plan');
                            b.style.borderWidth = '1px';
                        });

                        // Activer la carte cliquée
                        this.classList.add('border-success', 'badge-plan');
                        this.style.borderWidth = '2px';

                        // Récupérer la valeur du radio bouton interne
                        const radioInput = this.querySelector('input[name="plan_type"]');
                        if (radioInput) {
                            radioInput.checked = true;
                            currentPlan = radioInput.value;
                        }

                        // Déterminer le montant selon le plan
                        if (currentPlan === "ESSENTIEL") {
                            currentAmount = 100000;
                        } else if (currentPlan === "PRO") {
                            currentAmount = 150000;
                        } else {
                            currentAmount = 200000; // EXPERT
                        }

                        // Mettre à jour l'affichage du prix dans le bouton de paiement
                        const placeholder = document.getElementById('amount-placeholder');
                        if (placeholder) {
                            placeholder.innerText = currentAmount.toLocaleString(
                                'fr-FR'); // Format propre avec espaces
                        }
                    });
                });
            });

            // 2. Fonction asynchrone de soumission du paiement vers l'API Wave
            async function lancerAbonnement() {
                const btn = document.getElementById('rechargeBtn');
                const errDiv = document.getElementById('rechargeError');

                if (errDiv) {
                    errDiv.style.display = 'none';
                    errDiv.innerHTML = '';
                }

                // Validation de sécurité côté client
                if (!currentAmount || currentAmount < 100000) {
                    if (errDiv) {
                        errDiv.textContent = 'Veuillez sélectionner un abonnement valide.';
                        errDiv.style.display = 'block';
                    }
                    return;
                }

                // Changement d'état du bouton (Loading)
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connexion à Wave...';

                try {
                    const response = await fetch('{{ url('abonnement/payer-wave') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            plan_type: currentPlan, // ESSENTIEL, PRO, ou EXPERT
                        }),
                    });

                    console.log(currentPlan);

                    const data = await response.json();

                    console.log(data);

                    // Vérification du succès de l'API et présence de l'URL Wave
                    if (response.ok && data.success && (data.abonnement_url)) {

                        // Utilise la clé disponible renvoyée par votre contrôleur
                        const waveUrl = data.abonnement_url;

                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Ouverture de Wave...';

                        // Configuration et centrage de la fenêtre Popup
                        const popupWidth = 500;
                        const popupHeight = 700;
                        const left = Math.round((window.screen.width - popupWidth) / 2);
                        const top = Math.round((window.screen.height - popupHeight) / 2);

                        const popup = window.open(
                            waveUrl,
                            'WavePayment',
                            `width=${popupWidth},height=${popupHeight},left=${left},top=${top},toolbar=no,menubar=no,scrollbars=yes,resizable=no`
                        );

                        // Anti-Popup Blocker : Si le navigateur bloque la fenêtre, on ouvre dans un nouvel onglet standard
                        if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                            window.open(waveUrl, '_blank');
                        }

                        // Surveillance en temps réel de la fermeture de la fenêtre de paiement
                        const checkClosed = setInterval(() => {
                            if (popup && popup.closed) {
                                clearInterval(checkClosed);
                                window.location.reload(); // Rafraîchit l'application pour activer le nouveau plan
                            }
                        }, 1000);

                        // Remise à l'état initial du bouton au cas où
                        btn.disabled = false;
                        btn.innerHTML = `Payer ${currentAmount.toLocaleString('fr-FR')} FCFA`;

                    } else {
                        // Gestion et affichage des erreurs renvoyées par le serveur
                        if (errDiv) {
                            const msg = Array.isArray(data.message) ?
                                data.message.join('<br>') :
                                (data.message ?? 'Une erreur est survenue lors de l\'initialisation.');
                            errDiv.innerHTML = msg;
                            errDiv.style.display = 'block';
                        }
                        resetPayButton(btn);
                    }
                } catch (err) {
                    console.log(err);
                    // Erreur réseau ou plantage JS script
                    if (errDiv) {
                        errDiv.textContent = 'Erreur réseau ou connexion impossible. Veuillez réessayer.';
                        errDiv.style.display = 'block';
                    }
                    resetPayButton(btn);
                }
            }

            // Fonction outil pour réinitialiser le texte du bouton en cas d'échec
            function resetPayButton(btn) {
                btn.disabled = false;
                btn.innerHTML = `Payer <span id="amount-placeholder">${currentAmount.toLocaleString('fr-FR')}</span> FCFA`;
            }
        </script>

        {{-- ══ Réseau affilié ══ --}}
        {{-- <div class="p-card">
            <div class="section-head mb-2">
                <div class="section-title">
                    <iconify-icon icon="ph:users-three-bold" style="color:#2563eb;"></iconify-icon>
                    Réseau affilié
                </div>
            </div>

            <p class="reseau-desc">Pharmacies partenaires pouvant accéder aux dossiers avec consentement réseau actif.</p>

            <div class="partner-row">
                <div class="d-flex align-items-center gap-3">
                    <div class="partner-dot"></div>
                    <div>
                        <div class="partner-name">Pharmacie Koumassi Centre</div>
                        <div class="partner-sub">Koumassi, Abidjan · 8 patients partagés</div>
                    </div>
                </div>
                <span class="badge-partenaire">Partenaire</span>
            </div>

            <div class="partner-row">
                <div class="d-flex align-items-center gap-3">
                    <div class="partner-dot"></div>
                    <div>
                        <div class="partner-name">Pharmacie Les 2 Plateaux</div>
                        <div class="partner-sub">Cocody, Abidjan · 3 patients partagés</div>
                    </div>
                </div>
                <span class="badge-partenaire">Partenaire</span>
            </div>

            <button class="btn-gerer mt-3">
                <iconify-icon icon="ph:chat-circle-dots-bold"></iconify-icon> Gérer les affiliations
            </button>
        </div> --}}

    </div>

    {{-- ══ Modal modification ══ --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:20px;border:none;">
                <div class="modal-header" style="background:#16a34a;border-radius:20px 20px 0 0;padding:18px 26px;">
                    <h5 class="modal-title text-white fw-bold"
                        style="font-size:15px;display:flex;align-items:center;gap:8px;">
                        <iconify-icon icon="ph:pencil-bold"></iconify-icon> Modifier les informations
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ url('ma-pharmacie/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('POST')
                        <div class="row g-3">

                            <div class="col-12">
                                <div class="field-lbl">Photo de façade</div>
                                <input type="file" name="facade_image" class="f-input" accept=".jpg,.jpeg,.png">
                                @if ($pharmacy->facade_image ?? false)
                                    <div class="mt-2">
                                        <img src="{{ $pharmacy->facade_image }}" height="60" class="rounded"
                                            alt="Façade">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="field-lbl">Nom de la pharmacie <span style="color:#dc2626;">*</span></div>
                                <input required type="text" name="name" class="f-input"
                                    value="{{ $pharmacy->name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Nom du propriétaire</div>
                                <input type="text" name="owner_name" class="f-input"
                                    value="{{ $pharmacy->owner_name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Adresse</div>
                                <input type="text" name="address" class="f-input"
                                    value="{{ $pharmacy->address ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Commune <span style="color:#dc2626;">*</span></div>
                                <select required name="commune_id" class="f-input" style="appearance:none;">
                                    @foreach ($communes as $commune)
                                        <option value="{{ $commune->id_commune }}"
                                            {{ ($pharmacy->commune_id ?? '') == $commune->id_commune ? 'selected' : '' }}>
                                            {{ $commune->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Téléphone</div>
                                <input type="text" name="phone_number" class="f-input"
                                    value="{{ $pharmacy->phone_number ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">WhatsApp</div>
                                <input type="text" name="whats_app_phone_number" class="f-input"
                                    value="{{ $pharmacy->whats_app_phone_number ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Heure d'ouverture</div>
                                <input type="text" name="opening_hours" class="f-input" placeholder="ex: 08h00"
                                    value="{{ $pharmacy->opening_hours ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Heure de fermeture</div>
                                <input type="text" name="closing_hours" class="f-input" placeholder="ex: 20h00"
                                    value="{{ $pharmacy->closing_hours ?? '' }}">
                            </div>
                            <div class="col-12">
                                <div class="field-lbl">Coordonnées GPS</div>
                                <input type="text" name="gps_coordinates" class="f-input"
                                    placeholder="ex: 5.3364,-4.0267" value="{{ $pharmacy->gps_coordinates ?? '' }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" data-bs-dismiss="modal"
                                style="padding:10px 22px;border-radius:11px;border:1.5px solid #e2e8f0;background:white;font-size:13px;font-weight:600;color:#475569;cursor:pointer;">
                                Annuler
                            </button>
                            <button type="submit"
                                style="padding:10px 24px;border-radius:11px;border:none;background:#16a34a;color:white;font-size:13px;font-weight:600;cursor:pointer;">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
