@extends('layouts.master', ['title' => 'Rappels & Communications'])

@section('content')
    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* ── Boutons généraux ── */
        .btn-custom-primary {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-custom-primary:hover {
            background-color: #15803d;
            color: white;
        }

        .btn-custom-secondary {
            background-color: #334155;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        /* ── Formulaire Nouveau Rappel ── */
        .reminder-form-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            display: none;
        }

        .reminder-form-card.show {
            display: block;
        }

        .form-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #16a34a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .custom-form-label {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 6px;
        }

        .custom-select,
        .custom-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #334155;
            outline: none;
            background-color: #fff;
        }

        .channel-selector {
            display: flex;
            gap: 12px;
        }

        .channel-btn {
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background: #fff;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .channel-btn.active-whatsapp {
            background-color: #22c55e;
            color: white;
            border-color: #22c55e;
        }

        .channel-btn.active-sms {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* ── KPI Cards Minimalistes ── */
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
            border: 1px solid #e2e8f0;
        }

        .kpi-mini-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .kpi-mini-value {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }

        .kpi-mini-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
            margin-top: 4px;
        }

        /* ── Filtres Horizontaux Express ── */
        .filter-container-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .filter-row-chips {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-separator {
            width: 1px;
            height: 24px;
            background-color: #e2e8f0;
            margin: 0 8px;
        }

        .filter-label-inline {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-right: 4px;
        }

        .chip-btn {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #fff;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s;
        }

        .chip-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .chip-btn.active {
            background-color: #16a34a;
            color: white;
            border-color: #16a34a;
        }

        .chip-btn.active-badge-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-count-inside {
            background: #dc2626;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
        }

        /* ── Liste des rappels ── */
        .reminders-list-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }

        .list-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .reminder-item-row {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #f1f5f9;
            margin-bottom: 10px;
            gap: 16px;
            transition: all 0.15s;
        }

        .reminder-item-row:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .p-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .patient-info-meta {
            min-width: 220px;
        }

        .patient-header-flex {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .patient-title-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .reminder-reason-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Badges Specifiques */
        .badge-channel {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-channel-whatsapp {
            background-color: #e8fbf0;
            color: #16a34a;
        }

        .badge-channel-sms {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .priority-indicator {
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .priority-haute {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .priority-moyenne {
            background-color: #fff7ed;
            color: #d97706;
        }

        .reminder-date-col {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 90px;
        }

        .status-pill-state {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-width: 100px;
        }

        .state-waiting {
            background-color: #fff7ed;
            color: #ea580c;
        }

        .state-waiting .dot {
            width: 6px;
            height: 6px;
            background-color: #ea580c;
            border-radius: 50%;
        }

        /* Boutons Actions */
        .action-send-btn {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .action-send-btn:hover {
            background-color: #15803d;
        }

        .icon-action-trigger {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: transparent;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .icon-action-trigger:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        /* ── MODAL PREVIEW ── */
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
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .modal-preview-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .message-bubble-preview {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            color: #334155;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .modal-meta-destination {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #475569;
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
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
            cursor: pointer;
        }

        .modal-btn-confirm {
            flex: 1;
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
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

        {{-- Entête avec bouton dynamique bascule --}}
        <div class="page-header">
            <p class="page-title">Rappels & Communications</p>
            <button class="btn-custom-primary" id="triggerFormBtn" onclick="toggleReminderForm()">
                <iconify-icon icon="ph:plus-bold" id="formBtnIcon"></iconify-icon> <span id="formBtnText">Nouveau rappel</span>
            </button>
        </div>

        {{-- Block pliable : Créer un nouveau rappel --}}
        <div class="reminder-form-card" id="reminderFormSection">
            <div class="form-section-title">
                <iconify-icon icon="ph:bell-bold"></iconify-icon> Créer un nouveau rappel
            </div>

            <form action="{{ route('rappels.store') }}" method="POST" id="mainReminderForm">
                @csrf
                {{-- Input caché pour le canal d'envoi géré par les boutons JS --}}
                <input type="hidden" name="channel" id="selectedChannel" value="WHATSAPP">
                {{-- Message par défaut ou généré --}}
                <input type="hidden" name="message" value="Bonjour, ceci est un rappel concernant votre suivi santé.">

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="custom-form-label">Patient</div>
                        <select class="custom-select" name="patient_id" required>
                            <option value="">Sélectionner un patient...</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id_patient }}">
                                    {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->phone_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-form-label">Type de rappel</div>
                        <select class="custom-select" name="type" required>
                            <option value="RENOUVELLEMENT">Renouvellement traitement</option>
                            <option value="MESURE">Contrôle / Mesure urgente</option>
                            <option value="CONSEIL">Conseil</option>
                            <option value="CAMPAGNE">Campagne</option>
                            <option value="PERSONNALISE">Personnalisé</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-form-label">Canal d'envoi</div>
                        <div class="channel-selector">
                            <button type="button" class="channel-btn active-whatsapp" id="chanWhatsapp"
                                onclick="setChannel('WHATSAPP')">
                                <iconify-icon icon="ph:whatsapp-logo"></iconify-icon> WhatsApp
                            </button>
                            <button type="button" class="channel-btn" id="chanSms" onclick="setChannel('SMS')">
                                <iconify-icon icon="ph:chats-teardrop"></iconify-icon> SMS
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-form-label">Date / heure d'envoi</div>
                        <input type="datetime-local" name="send_at" class="custom-input" value="{{ date('Y-m-d\TH:i') }}">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="modal-btn-cancel" style="max-width: 100px;"
                        onclick="toggleReminderForm()">Annuler</button>
                    <button type="submit" class="btn-custom-primary" style="border-radius: 10px;">
                        <iconify-icon icon="ph:bell-bold"></iconify-icon> Créer le rappel
                    </button>
                </div>
            </form>
        </div>

        {{-- Compteurs Mini KPI Dynamiques --}}
        <div class="kpi-row">
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#fef3c7; color:#d97706;">
                    <iconify-icon icon="ph:clock-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $kpiEnAttente }}</div>
                    <div class="kpi-mini-label">En attente</div>
                </div>
            </div>
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#fef2f2; color:#dc2626;">
                    <iconify-icon icon="ph:warning-amber-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $kpiEnRetard }}</div>
                    <div class="kpi-mini-label">En retard</div>
                </div>
            </div>
            <div class="kpi-mini-card">
                <div class="kpi-mini-icon" style="background:#dcfce7; color:#15803d;">
                    <iconify-icon icon="ph:check-circle-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-mini-value">{{ $kpiEnvoyes }}</div>
                    <div class="kpi-mini-label">Envoyés</div>
                </div>
            </div>
        </div>

        {{-- Menu de Filtrage Global en ligne (Chips) Dynamique --}}
        <div class="filter-container-card">
            <div class="filter-row-chips">
                <button class="chip-btn active">Aujourd'hui</button>
                <button class="chip-btn">Cette semaine</button>
                <button class="chip-btn active-badge-count">En retard <span
                        class="badge-count-inside">{{ $kpiEnRetard }}</span></button>

                <div class="filter-separator"></div>

                <span class="filter-label-inline">Pathologie:</span>
                <button class="chip-btn active">Toutes</button>
                @foreach ($pathologies as $pathologie)
                    <button class="chip-btn">{{ $pathologie->code }}</button>
                @endforeach

                <div class="filter-separator"></div>

                <span class="filter-label-inline">Priorité:</span>
                <button class="chip-btn active">Toutes</button>
                <button class="chip-btn">Haute</button>
                <button class="chip-btn">Moyenne</button>
                <button class="chip-btn">Basse</button>
            </div>
        </div>

        {{-- Conteneur Principal des Rappels Dynamiques --}}
        <div class="reminders-list-card">
            <div class="list-section-title">{{ $reminders->count() }} rappels</div>

            <div id="remindersContainer">
                @forelse($reminders as $reminder)
                    @php
                        // Initiales pour l'avatar
$initials = strtoupper(
    substr($reminder->patient->first_name, 0, 1) . substr($reminder->patient->last_name, 0, 1),
);

// Détermination graphique selon le statut de retard du patient
$isRetard = $reminder->patient->status === 'EN_RETARD';
$priorityClass = $isRetard ? 'priority-haute' : 'priority-moyenne';
$priorityLabel = $isRetard ? 'Haute' : 'Moyenne';
                    @endphp

                    <div class="reminder-item-row"
                        data-name="{{ $reminder->patient->first_name }} {{ $reminder->patient->last_name }}">
                        {{-- Avatar --}}
                        <div class="p-avatar-circle" style="background:#e8f5e9; color:#2e7d32;">{{ $initials }}</div>

                        {{-- Infos Patient --}}
                        <div class="patient-info-meta">
                            <div class="patient-header-flex">
                                <span class="patient-title-name">{{ $reminder->patient->first_name }}
                                    {{ $reminder->patient->last_name }}</span>
                                {{-- Affichage dynamique du statut médical (Ex: HTA si en retard historique) --}}
                                <span
                                    class="priority-indicator {{ $priorityClass }}">{{ $reminder->patient->status }}</span>
                            </div>
                            <div class="reminder-reason-text">{{ $reminder->message }}</div>
                        </div>

                        {{-- Canal d'envoi --}}
                        <div style="min-width: 110px;">
                            @if ($reminder->channel === 'WHATSAPP')
                                <span class="badge-channel badge-channel-whatsapp">
                                    <iconify-icon icon="ph:whatsapp-logo"></iconify-icon> WhatsApp
                                </span>
                            @else
                                <span class="badge-channel badge-channel-sms">
                                    <iconify-icon icon="ph:chats-teardrop"></iconify-icon> SMS
                                </span>
                            @endif
                        </div>

                        {{-- Priorité --}}
                        <div style="min-width: 80px;">
                            <span class="priority-indicator {{ $priorityClass }}">{{ $priorityLabel }}</span>
                        </div>

                        {{-- Date d'envoi --}}
                        <div class="reminder-date-col">
                            <iconify-icon icon="ph:calendar-blank"></iconify-icon>
                            {{ $reminder->sent_at ? \Carbon\Carbon::parse($reminder->sent_at)->translatedFormat('d M Y') : 'Non défini' }}
                        </div>

                        {{-- Statut de distribution --}}
                        <div>
                            <span class="status-pill-state {{ $reminder->status === 'ENVOYE' ? 'state-waiting' : '' }}">
                                <span class="dot"></span>{{ $reminder->status }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <button class="action-send-btn"
                                onclick="openPreviewModal('{{ $reminder->patient->first_name }} {{ $reminder->patient->last_name }}', '{{ $reminder->channel }}', '{{ addslashes($reminder->message) }}', '{{ $reminder->patient->phone_number }}')">
                                <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon> Envoyer
                            </button>
                            <button class="icon-action-trigger"
                                onclick="openPreviewModal('{{ $reminder->patient->first_name }} {{ $reminder->patient->last_name }}', '{{ $reminder->channel }}', '{{ addslashes($reminder->message) }}', '{{ $reminder->patient->phone_number }}')">
                                <iconify-icon icon="ph:eye-bold"></iconify-icon>
                            </button>
                            <button class="icon-action-trigger"><iconify-icon icon="ph:x-circle"></iconify-icon></button>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-4 text-muted">Aucun rappel enregistré pour le moment.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══ COMPOSANT INTERFACES POPUP (MODAL D'APERÇU) ══ --}}
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
                <span class="badge-channel badge-channel-whatsapp" id="modalChannelBadge">
                    <iconify-icon icon="ph:whatsapp-logo"></iconify-icon> WhatsApp
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

    {{-- Script Dynamic Management --}}
    <script>
        let formOpen = false;

        function toggleReminderForm() {
            formOpen = !formOpen;
            const panel = document.getElementById('reminderFormSection');
            const icon = document.getElementById('formBtnIcon');
            const text = document.getElementById('formBtnText');
            const triggerBtn = document.getElementById('triggerFormBtn');

            if (formOpen) {
                panel.classList.add('show');
                triggerBtn.className = "btn-custom-secondary";
                icon.setAttribute('icon', 'ph:x-bold');
                text.textContent = "Annuler";
            } else {
                panel.classList.remove('show');
                triggerBtn.className = "btn-custom-primary";
                icon.setAttribute('icon', 'ph:plus-bold');
                text.textContent = "Nouveau rappel";
            }
        }

        function setChannel(channel) {
            const btnString = channel.toLowerCase();
            const btnWhatsapp = document.getElementById('chanWhatsapp');
            const btnSms = document.getElementById('chanSms');
            const hiddenInput = document.getElementById('selectedChannel');

            // Assigner la valeur en majuscule au champ caché pour correspondre à l'ENUM BDD ('WHATSAPP', 'SMS')
            hiddenInput.value = channel;

            if (btnString === 'whatsapp') {
                btnWhatsapp.classList.add('active-whatsapp');
                btnSms.classList.remove('active-sms');
            } else {
                btnSms.classList.add('active-sms');
                btnWhatsapp.classList.remove('active-whatsapp');
            }
        }

        /* Fonctions de contrôle de l'aperçu du Message (Modal) */
        function openPreviewModal(patientName, channelType, textMessage, phone) {
            document.getElementById('modalPatientContext').textContent = `${patientName} · ${channelType}`;
            document.getElementById('modalMessageContent').textContent = textMessage;
            document.getElementById('modalTargetPhone').textContent = `→ ${phone}`;

            const badge = document.getElementById('modalChannelBadge');
            if (channelType.toLowerCase() === 'whatsapp') {
                badge.className = "badge-channel badge-channel-whatsapp";
                badge.innerHTML = `<iconify-icon icon="ph:whatsapp-logo"></iconify-icon> WhatsApp`;
            } else {
                badge.className = "badge-channel badge-channel-sms";
                badge.innerHTML = `<iconify-icon icon="ph:chats-teardrop"></iconify-icon> SMS`;
            }

            document.getElementById('previewModal').classList.add('open');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.remove('open');
        }

        function confirmAndSend() {
            alert('Message transmis avec succès !');
            closePreviewModal();
        }
    </script>
@endsection
