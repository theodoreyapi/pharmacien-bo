@extends('layouts.master', ['title' => 'Campagnes santé'])

@section('content')
    <style>
        .dash-body {
            padding: 24px;
            min-height: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a237e;
            margin: 0;
        }

        /* ── KPI Cards (Rondes et Colorées) ── */
        .kpi-row {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-mini-card {
            background: white;
            border-radius: 16px;
            padding: 16px 20px;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        .kpi-mini-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .kpi-mini-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a237e;
            line-height: 1;
        }

        .kpi-mini-label {
            font-size: 13px;
            color: #666666;
            font-weight: 500;
            margin-top: 4px;
        }

        /* ── Système de Sous-Onglets (Navbar) ── */
        .tabs-navigation-card {
            background: white;
            border-radius: 12px;
            padding: 6px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            display: flex;
            gap: 4px;
        }

        .tab-nav-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #666666;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-nav-btn iconify-icon {
            font-size: 18px;
        }

        .tab-nav-btn.active {
            background-color: #4caf50;
            color: white;
        }

        /* ── Conteneur Général des Vues d'Onglets ── */
        .tab-content-panel {
            display: none;
        }

        .tab-content-panel.show {
            display: block;
        }

        /* ── Onglet : Historique (Flux de messages) ── */
        .history-message-item {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid #eef2f0;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
        }

        .message-avatar-icon {
            width: 40px;
            height: 40px;
            background-color: #e8f5e9;
            color: #4caf50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .message-main-content {
            flex: 1;
        }

        .message-header-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 6px;
        }

        .message-patient-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a237e;
        }

        /* Badges & Pills */
        .badge-pill-custom {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-chan-whatsapp {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .badge-chan-sms {
            background-color: #e8eaf6;
            color: #1a237e;
        }

        .badge-category {
            background-color: #f5f5f5;
            color: #666666;
        }

        .message-body-text {
            font-size: 14px;
            color: #333333;
            line-height: 1.4;
            margin-bottom: 6px;
        }

        .message-meta-time {
            font-size: 12px;
            color: #666666;
        }

        .status-badge-right {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .status-lu {
            background-color: #f3e5f5;
            color: #9c27b0;
        }

        .status-livre {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .status-echec {
            background-color: #ffebee;
            color: #f44336;
        }

        /* ── Onglet : Envoi Manuel (Formulaire) ── */
        .manual-form-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .form-main-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 20px;
        }

        .custom-form-group {
            margin-bottom: 20px;
        }

        .custom-form-label {
            font-size: 14px;
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 8px;
            display: block;
        }

        .custom-select,
        .custom-input,
        .custom-textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #333333;
            outline: none;
            background-color: #fff;
            transition: border-color 0.15s;
        }

        .custom-select:focus,
        .custom-input:focus,
        .custom-textarea:focus {
            border-color: #4caf50;
        }

        .channel-selector-row {
            display: flex;
            gap: 12px;
        }

        .channel-select-btn {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #fff;
            font-size: 14px;
            font-weight: 600;
            color: #1a237e;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .channel-select-btn.active {
            background-color: #4caf50;
            color: white;
            border-color: #4caf50;
        }

        .btn-submit-action {
            width: 100%;
            background-color: rgba(76, 175, 80, 0.6);
            /* Style désactivé/prêt de l'image */
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        /* Placeholder pour Onglets Vides */
        .empty-tab-placeholder {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            color: #666666;
            font-size: 15px;
        }

        /* ── MODAL PREVIEW (Aperçu) ── */
        .custom-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .custom-modal-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        .custom-modal-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 480px;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(0.95);
            transition: transform 0.2s ease;
            position: relative;
        }

        .custom-modal-backdrop.open .custom-modal-card {
            transform: scale(1);
        }

        .close-modal-x {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .modal-preview-header {
            margin-bottom: 16px;
        }

        .modal-preview-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a237e;
        }

        .modal-preview-subtitle {
            font-size: 13px;
            color: #666666;
            margin-top: 2px;
        }

        .message-bubble-preview {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            font-size: 14px;
            color: #333333;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .modal-meta-destination {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #333333;
            font-weight: 500;
            margin-bottom: 24px;
        }

        .modal-footer-actions {
            display: flex;
            gap: 12px;
        }

        .modal-btn-cancel {
            flex: 1;
            background: #fff;
            border: 1px solid #cbd5e1;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #333333;
            cursor: pointer;
        }

        .modal-btn-confirm {
            flex: 1;
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>

    <div class="dash-body">

        {{-- Affichage des messages de succès --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Compteurs Globaux Circulaires (KPIs) --}}
        <div class="kpi-row">
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#e8eaf6; color:#1a237e;">
                    <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $envoyes }}</div>
                    <div class="kpi-mini-label">Envoyés</div>
                </div>
            </div>
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#e8f5e9; color:#4caf50;">
                    <iconify-icon icon="ph:check-circle-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $livres }}</div>
                    <div class="kpi-mini-label">Livrés</div>
                </div>
            </div>
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#f3e5f5; color:#9c27b0;">
                    <iconify-icon icon="ph:eye-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $lus }}</div>
                    <div class="kpi-mini-label">Lus</div>
                </div>
            </div>
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#ffebee; color:#f44336;">
                    <iconify-icon icon="ph:x-circle-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $echecs }}</div>
                    <div class="kpi-mini-label">Échec</div>
                </div>
            </div>
        </div>

        {{-- Barre de Navigation d'Onglets Horizontaux --}}
        <div class="tabs-navigation-card">
            <button class="tab-nav-btn active" onclick="switchTab(0)">
                <iconify-icon icon="ph:history-bold"></iconify-icon> Historique
            </button>
            <button class="tab-nav-btn" onclick="switchTab(1)">
                <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon> Envoi manuel
            </button>
            {{-- <button class="tab-nav-btn" onclick="switchTab(2)">
                <iconify-icon icon="ph:file-text-bold"></iconify-icon> Modèles
            </button>
            <button class="tab-nav-btn" onclick="switchTab(3)">
                <iconify-icon icon="ph:lightning-bold"></iconify-icon> Automatiques
            </button> --}}
        </div>

        {{-- ── ONGLET 0 : HISTORIQUE ── --}}
        <div class="tab-content-panel show" id="tabPanel-0">
            @foreach ($historique as $item)
                {{-- Message 1 --}}
                <div class="history-message-item">
                    <div class="message-avatar-icon">
                        <iconify-icon icon="ph:chat-circle-dots-bold"></iconify-icon>
                    </div>
                    <div class="message-main-content">
                        <div class="message-header-row">
                            <span class="message-patient-name">{{ $item->first_name }} {{ $item->last_name }}</span>
                            <span class="badge-pill-custom badge-chan-whatsapp">
                                <iconify-icon icon="ph:whatsapp-logo-bold"></iconify-icon> {{ $item->channel }}
                            </span>
                            <span class="badge-pill-custom badge-category">{{ $item->category }}</span>
                        </div>
                        <div class="message-body-text">
                            {{ $item->content }}
                        </div>
                        <div class="message-meta-time">{{ \Carbon\Carbon::parse($item->sent_at)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="status-badge-right status-lu">{{ $item->status }}</div>
                </div>
            @endforeach
        </div>

        {{-- ── ONGLET 1 : ENVOI MANUEL ── --}}
        <form action="{{ route('messages.store') }}" method="POST">
            @csrf
            <div class="tab-content-panel" id="tabPanel-1">
                <div class="manual-form-card">
                    <div class="form-main-title">Envoi manuel</div>

                    {{-- 1. Ajoutez les CDN dans votre section head ou en haut de la vue si ce n'est pas déjà fait --}}
                    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
                    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

                    {{-- 2. Votre bloc de formulaire modifié --}}
                    <div class="custom-form-group">
                        <label class="custom-form-label" for="patients">Patient ou segment</label>
                        {{-- L'attribut placeholder servira d'invite dans la barre de recherche --}}
                        <select class="custom-select" id="patients" name="patient_ids[]" multiple
                            placeholder="Rechercher ou sélectionner des patients..." autocomplete="off">
                            @foreach ($patient as $p)
                                <option value="{{ $p->id_patient }}">
                                    {{ $p->first_name }} {{ $p->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Initialisation JavaScript (à placer dans votre bloc <script> existant) --}}
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            new TomSelect("#patients", {
                                plugins: ['remove_button'], // Permet de retirer un patient en cliquant sur une petite croix
                                maxItems: null, // Nombre illimité de sélections
                                persist: false,
                                create: false, // Empêche l'utilisateur d'ajouter des options qui n'existent pas
                                onDelete: function(values) {
                                    return confirm(values.length > 1 ? 'Supprimer ces ' + values.length +
                                        ' éléments ?' : 'Supprimer cet élément ?');
                                }
                            });
                        });
                    </script>

                    <div class="custom-form-group">
                        <label class="custom-form-label">Canal d'envoi</label>
                        {{-- CHAMP CRUCIAL : C'est cet input qui va envoyer la valeur (WHATSAPP, SMS ou PUSH) à votre contrôleur --}}
                        <input type="hidden" id="manualChannelInput" name="channel" value="WHATSAPP">
                        <div class="channel-selector-row">
                            <button type="button" class="channel-select-btn active" id="btnManualWhatsapp"
                                onclick="setManualChannel('whatsapp')">
                                <iconify-icon icon="ph:whatsapp-logo-bold"></iconify-icon> WhatsApp
                            </button>
                            <button type="button" class="channel-select-btn" id="btnManualSms" onclick="setManualChannel('sms')">
                                <iconify-icon icon="ph:chats-teardrop-bold"></iconify-icon> SMS
                            </button>
                            <button type="button" class="channel-select-btn" id="btnManualPush" onclick="setManualChannel('push')">
                                <iconify-icon icon="ph:bell-bold"></iconify-icon> Push
                            </button>
                        </div>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-form-label">Message</label>
                        <textarea name="content" class="custom-textarea" rows="4" placeholder="Rédigez votre message..."></textarea>
                    </div>

                    <button class="btn btn-primary" type="submit">
                        {{-- onclick="openPreviewModal('Patient Sélectionné', 'WhatsApp', 'Exemple de texte rédigé...', '+225 07 00 00 00 00')"> --}}
                        {{-- <iconify-icon icon="ph:eye-bold"></iconify-icon> --}}
                        Envoyer
                    </button>
                </div>
            </div>
        </form>
        {{-- ── ONGLET 2 : MODÈLES ── --}}
        <div class="tab-content-panel" id="tabPanel-2">
            <div class="empty-tab-placeholder">
                <iconify-icon icon="ph:file-text-bold"
                    style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px; display:block;"></iconify-icon>
                Gestion et configuration de vos modèles de messages pré-enregistrés.
            </div>
        </div>

        {{-- ── ONGLET 3 : AUTOMATIQUES ── --}}
        <div class="tab-content-panel" id="tabPanel-3">
            <div class="empty-tab-placeholder">
                <iconify-icon icon="ph:lightning-bold"
                    style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px; display:block;"></iconify-icon>
                Configuration des règles automatiques d'envoi (anniversaires, rappels automatiques).
            </div>
        </div>

    </div>

    {{-- ══ MODAL DE PREVIEW EN LIGNE ══ --}}
    <div class="custom-modal-backdrop" id="previewModal">
        <div class="custom-modal-card">
            <button class="close-modal-x" onclick="closePreviewModal()">&times;</button>

            <div class="modal-preview-header">
                <div class="modal-preview-title">Aperçu du message</div>
                <div class="modal-preview-subtitle" id="modalPatientContext">Fatou Koné · WhatsApp</div>
            </div>

            <div class="message-bubble-preview" id="modalMessageContent">
                Bonjour Mme Koné, votre traitement pour l'hypertension arrive à échéance. Venez le renouveler à la
                pharmacie.
            </div>

            <div class="modal-meta-destination">
                <span class="badge-pill-custom badge-chan-whatsapp" id="modalChannelBadge">
                    <iconify-icon icon="ph:whatsapp-logo-bold"></iconify-icon> WhatsApp
                </span>
                <span id="modalTargetPhone">→ +225 07 12 34 56 78</span>
            </div>

            <div class="modal-footer-actions">
                <button class="modal-btn-cancel" onclick="closePreviewModal()">Annuler</button>
                <button class="modal-btn-confirm" onclick="confirmAndSend()">
                    <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon> Confirmer l'envoi
                </button>
            </div>
        </div>
    </div>

    {{-- Script JavaScript de Navigation & État --}}
    <script>
        // Changement d'onglet horizontal principal
        function switchTab(tabIndex) {
            // Sélectionner tous les boutons d'onglets et les panneaux
            const buttons = document.querySelectorAll('.tab-nav-btn');
            const panels = document.querySelectorAll('.tab-content-panel');

            buttons.forEach((btn, idx) => {
                if (idx === tabIndex) {
                    btn.classList.add('active');
                    panels[idx].classList.add('show');
                } else {
                    btn.classList.remove('active');
                    panels[idx].classList.remove('show');
                }
            });
        }

        // Sélection du canal dans l'envoi manuel
        function setManualChannel(channel) {
            const btnWhatsapp = document.getElementById('btnManualWhatsapp');
            const btnSms = document.getElementById('btnManualSms');
            const btnPush = document.getElementById('btnManualPush');

            // Retirer l'état actif de tous
            btnWhatsapp.classList.remove('active');
            btnSms.classList.remove('active');
            btnPush.classList.remove('active');

            // Assigner l'état actif au sélectionné
            if (channel === 'whatsapp') btnWhatsapp.classList.add('active');
            if (channel === 'sms') btnSms.classList.add('active');
            if (channel === 'push') btnPush.classList.add('active');
        }

        /* Gestion de la fenêtre d'aperçu (Modal) */
        function openPreviewModal(patientName, channelType, textMessage, phone) {
            document.getElementById('modalPatientContext').textContent = `${patientName} · ${channelType}`;
            document.getElementById('modalMessageContent').textContent = textMessage;
            document.getElementById('modalTargetPhone').textContent = `→ ${phone}`;

            const badge = document.getElementById('modalChannelBadge');
            if (channelType.toLowerCase() === 'whatsapp') {
                badge.className = "badge-pill-custom badge-chan-whatsapp";
                badge.innerHTML = `<iconify-icon icon="ph:whatsapp-logo-bold"></iconify-icon> WhatsApp`;
            } else {
                badge.className = "badge-pill-custom badge-chan-sms";
                badge.innerHTML = `<iconify-icon icon="ph:chats-teardrop-bold"></iconify-icon> SMS`;
            }

            document.getElementById('previewModal').open = true; // accessibilité fallback
            document.getElementById('previewModal').classList.add('open');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.remove('open');
        }

        function confirmAndSend() {
            alert('Campagne transmise avec succès !');
            closePreviewModal();
        }
    </script>
@endsection
