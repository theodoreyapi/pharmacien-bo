@extends('layouts.master', ['title' => 'Tableau de bord'])

@section('content')
    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 22px 24px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card .stat-label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .stat-card .stat-sub {
            font-size: 12.5px;
            color: #94a3b8;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            margin-top: 4px;
        }

        /* ── Section Titles ── */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Patient Row ── */
        .patient-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            transition: background 0.15s;
        }

        .patient-row:hover {
            background: #f1f5f9;
        }

        .patient-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
            background-color: #e8f5ee;
            color: #2d9e5a;
        }

        .patient-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
            letter-spacing: 0.02em;
        }

        .pill-danger {
            background: #fef2f2;
            color: #dc2626;
        }

        .pill-warning {
            background: #fff7ed;
            color: #d97706;
        }

        .pill-info {
            background: #eff6ff;
            color: #2563eb;
        }

        /* ── Bouton relancer ── */
        .btn-relancer {
            font-size: 12px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            background: #ecfdf5;
            color: #16a34a;
            transition: background 0.15s;
        }

        .btn-relancer:hover {
            background: #dcfce7;
        }

        /* ── Activity Log ── */
        .log-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .log-item:last-child {
            border-bottom: none;
        }

        .log-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .log-text {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .log-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .log-time {
            font-size: 11px;
            font-family: 'DM Mono', monospace;
            color: #cbd5e1;
            margin-left: auto;
            flex-shrink: 0;
            padding-top: 2px;
        }

        /* ── Alert Card ── */
        .alert-card {
            border-radius: 14px;
            padding: 16px;
            border: 1px solid transparent;
            transition: transform 0.15s;
        }

        .alert-card:hover {
            transform: translateY(-1px);
        }

        .alert-card.critical {
            background: #fff5f5;
            border-color: #fecaca;
        }

        .alert-card.high {
            background: #fffbeb;
            border-color: #fed7aa;
        }

        .alert-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .alert-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }

        .btn-dossier {
            font-size: 11px;
            font-weight: 600;
            padding: 6px 13px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            cursor: pointer;
            flex-shrink: 0;
            transition: border-color 0.15s;
        }

        .btn-dossier:hover {
            border-color: #94a3b8;
        }

        /* ── Bottom Summary Cards ── */
        .summary-card {
            border-radius: 18px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            border: none;
        }

        .summary-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .summary-label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.03em;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .summary-sub {
            font-size: 12px;
            margin-top: 3px;
        }

        /* ── Quick Actions ── */
        .quick-action {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: white;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .quick-action:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .quick-action.primary {
            background: #16a34a;
            color: white;
            border-color: #16a34a;
        }

        .quick-action.primary:hover {
            background: #15803d;
        }

        /* ── Card wrapper ── */
        .dash-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .stat-card .stat-icon {
                display: none;
            }
        }
    </style>

    <div class="dash-body">

        {{-- ══ KPI Cards ══ --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-label">Patients suivis</div>
                    <div class="stat-value">{{ $patientsCount }}</div>
                    <div class="stat-badge" style="background:#ecfdf5;color:#16a34a;">
                        ↑ +{{ $newThisMonth }} ce mois
                    </div>
                    <div class="stat-icon" style="background:#ecfdf5;color:#16a34a;">
                        <iconify-icon icon="ph:users-three-bold"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-label">À relancer</div>
                    <div class="stat-value">{{ $aRelancer }}</div>
                    <div class="stat-badge" style="background:#fff7ed;color:#d97706;">
                        Rappels en attente
                    </div>
                    <div class="stat-icon" style="background:#fff7ed;color:#d97706;">
                        <iconify-icon icon="ph:bell-bold"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-label">Renouvellements</div>
                    <div class="stat-value">{{ $renouvellements }}</div>
                    <div class="stat-badge" style="background:#fef2f2;color:#dc2626;">
                        {{ $critiques }} critiques
                    </div>
                    <div class="stat-icon" style="background:#fef2f2;color:#dc2626;">
                        <iconify-icon icon="ph:arrows-counter-clockwise-bold"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-label">Mesures aujourd'hui</div>
                    <div class="stat-value">{{ $mesuresAujourdhui }}</div>
                    <div class="stat-badge" style="background:#eff6ff;color:#2563eb;">
                        Tension · Glycémie
                    </div>
                    <div class="stat-icon" style="background:#eff6ff;color:#2563eb;">
                        <iconify-icon icon="ph:pulse-bold"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        <br>

        {{-- ══ Actions rapides ══ --}}
        {{-- CDN pour le scanner de QR Code (à mettre idéalement dans votre layout ou ici) --}}
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

        {{-- ══ Actions rapides ══ --}}
        <div class="col-12 mb-4">
            <div class="dash-card">
                <div class="section-title">Actions rapides</div>
                <div class="row g-2">
                    <div class="col-sm-4">
                        <a href="{{ url('add-patient') }}" class="quick-action primary">
                            <iconify-icon icon="ph:plus-bold"></iconify-icon> Ajouter patient
                        </a>
                    </div>
                    <div class="col-sm-4">
                        {{-- ID ajouté ici pour cibler le clic, et suppression du href --}}
                        <a href="javascript:void(0);" id="btn-scan-qr" class="quick-action">
                            <iconify-icon icon="ph:qr-code-bold"></iconify-icon> Scanner QR
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ url('rappels') }}" class="quick-action">
                            <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon> Envoyer rappel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ MODALE SCANNER & SAISIE MANUELLE ══ --}}
        <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <span class="modal-title d-flex align-items-center gap-2" id="qrScannerModalLabel"
                            style="color: black; font-weight: 600;">
                            <iconify-icon icon="ph:qr-code-bold" style="color: #16a34a; font-size: 1.4rem;"></iconify-icon>
                            Scanner QR Patient
                        </span>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="btn-close-scanner"></button>
                    </div>
                    <div class="modal-body p-4">

                        {{-- Zone d'affichage de la caméra --}}
                        <div id="reader-container" class="mb-4"
                            style="position: relative; background: #0f172a; border-radius: 12px; overflow: hidden; min-height: 250px;">
                            <div id="qr-reader" style="width: 100%;"></div>
                        </div>

                        <div class="text-center text-muted mb-3 position-relative">
                            <hr style="border-color: #cbd5e1;">
                            <span
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 0 15px; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">OU
                                SAISIR LE CODE</span>
                        </div>

                        {{-- Formulaire de saisie manuelle alternative --}}
                        <form id="manual-qr-form">
                            <div class="form-group mb-3">
                                <label for="manual_qr_code" class="form-label"
                                    style="font-weight: 500; color: #475569; font-size: 14px;">Numéro Social / CMU</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="background: #f1f5f9; border-color: #cbd5e1;"><iconify-icon
                                            icon="ph:identification-card-bold"></iconify-icon></span>
                                    <input type="text" id="manual_qr_code" class="form-control"
                                        placeholder="Ex: 1234567890" style="border-color: #cbd5e1;" required>
                                    <button type="submit" class="btn btn-primary"
                                        style="font-weight: 500; padding: 0 20px;">
                                        Valider
                                    </button>
                                </div>
                                <div id="scanner-error" class="text-danger mt-2" style="font-size: 13px; display: none;">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <br>

        {{-- ══ Patients à relancer + Activité récente ══ --}}
        <div class="row g-3 mb-4">

            <div class="col-12 col-lg-8">
                <div class="dash-card h-100">
                    <div class="section-title">
                        <iconify-icon icon="ph:bell-ring-bold" style="color:#d97706;"></iconify-icon>
                        Patients à relancer
                    </div>

                    @if ($patientsARelancer->isEmpty())
                        <div style="text-align:center;padding:24px 0;color:#94a3b8;font-size:13px;">
                            <iconify-icon icon="ph:check-circle-bold"
                                style="font-size:2rem;color:#16a34a;display:block;margin-bottom:8px;"></iconify-icon>
                            Aucun patient à relancer pour le moment
                        </div>
                    @else
                        <div class="d-flex flex-column gap-2">
                            @foreach ($patientsARelancer as $patient)
                                <div class="patient-row">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="patient-avatar">{{ $patient->initials }}</div>
                                        <div>
                                            <div class="patient-name">
                                                {{ $patient->first_name }} {{ $patient->last_name }}
                                            </div>
                                            <div class="d-flex gap-1 flex-wrap">
                                                @foreach (explode(',', $patient->pathologies ?? '') as $patho)
                                                    @if (trim($patho))
                                                        <span class="pill pill-danger">{{ trim($patho) }}</span>
                                                    @endif
                                                @endforeach
                                                @if ($patient->days_late > 14)
                                                    <span class="pill pill-danger">Critique</span>
                                                @else
                                                    <span class="pill pill-warning">En retard</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ url('patient/' . $patient->id_patient . '/rappel') }}"
                                        class="btn-relancer">Relancer</a>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ url('rappels') }}" class="fw-semibold text-decoration-none"
                                style="font-size:12.5px;color:#16a34a;">
                                Voir tous les rappels →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="dash-card h-100">
                    <div class="section-title">
                        <iconify-icon icon="ph:clock-countdown-bold" style="color:#64748b;"></iconify-icon>
                        Activité récente
                    </div>
                    <div>
                        @forelse($activiteRecente as $log)
                            <div class="log-item">
                                <div class="log-icon" style="background:{{ $log['bg'] }};color:{{ $log['color'] }};">
                                    <iconify-icon icon="{{ $log['icon'] }}"></iconify-icon>
                                </div>
                                <div>
                                    <div class="log-text">{{ $log['text'] }}</div>
                                    <div class="log-sub">{{ $log['sub'] }}</div>
                                </div>
                                <div class="log-time">{{ $log['time'] }}</div>
                            </div>
                        @empty
                            <div style="text-align:center;padding:20px 0;color:#94a3b8;font-size:13px;">
                                Aucune activité aujourd'hui
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <br>

        {{-- ══ Alertes cliniques ══ --}}
        <div class="dash-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="section-title mb-0" style="flex:1;">
                    <iconify-icon icon="ph:warning-octagon-bold" style="color:#dc2626;"></iconify-icon>
                    Alertes cliniques
                </div>
                <span class="pill pill-danger ms-2">{{ $alertes->count() }} actives</span>
            </div>

            @if ($alertes->isEmpty())
                <div style="text-align:center;padding:20px 0;color:#94a3b8;font-size:13px;">
                    <iconify-icon icon="ph:check-circle-bold"
                        style="font-size:2rem;color:#16a34a;display:block;margin-bottom:8px;"></iconify-icon>
                    Aucune alerte clinique active
                </div>
            @else
                <div class="row g-3">
                    @foreach ($alertes as $alerte)
                        <div class="col-md-6 col-12">
                            <div class="alert-card {{ $alerte['type'] }}">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="alert-title">
                                            <iconify-icon icon="ph:warning-bold"
                                                style="color:{{ $alerte['type'] === 'critical' ? '#dc2626' : '#d97706' }};"></iconify-icon>
                                            {{ $alerte['patient'] }}
                                            <span
                                                class="pill {{ $alerte['type'] === 'critical' ? 'pill-danger' : 'pill-warning' }} ms-1">
                                                {{ $alerte['niveau'] }}
                                            </span>
                                        </div>
                                        <div class="fw-semibold" style="font-size:13px;color:#0f172a;margin:4px 0 2px;">
                                            {{ $alerte['titre'] }}
                                        </div>
                                        <div class="alert-desc">{{ $alerte['desc'] }}</div>
                                    </div>
                                    <a href="{{ url('requete') }}?search={{ urlencode($alerte['patient']) }}"
                                        class="btn-dossier">Voir dossier</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <br>

        {{-- ══ Résumés bas de page ══ --}}
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="summary-card" style="background:#16a34a;">
                    <div class="summary-icon" style="background:rgba(255,255,255,0.15);color:white;">
                        <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon>
                    </div>
                    <div style="color:white;">
                        <div class="summary-label" style="opacity:.75;">Rappels en attente</div>
                        <div class="summary-value">{{ $rappelsEnAttente }}</div>
                        <div class="summary-sub" style="opacity:.7;">À envoyer aujourd'hui</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="summary-card" style="background:white;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                    <div class="summary-icon" style="background:#fef2f2;color:#dc2626;">
                        <iconify-icon icon="ph:heart-bold"></iconify-icon>
                    </div>
                    @if ($derniereTension)
                        <div>
                            <div class="summary-label" style="color:#94a3b8;">
                                Dernière tension · {{ $derniereTension->patient_short }}
                            </div>
                            <div class="summary-value" style="color:#0f172a;">
                                {{ $derniereTension->systolic }}/{{ $derniereTension->diastolic }}
                            </div>
                            <div class="summary-sub fw-semibold"
                                style="color:{{ in_array($derniereTension->status_label, ['Critique', 'Élevé']) ? '#dc2626' : '#16a34a' }};">
                                @if (in_array($derniereTension->status_label, ['Critique', 'Élevé']))
                                    <iconify-icon icon="ph:warning-fill"></iconify-icon>
                                @else
                                    <iconify-icon icon="ph:check-circle-fill"></iconify-icon>
                                @endif
                                {{ $derniereTension->status_label ?? 'Tension élevée' }}
                            </div>
                        </div>
                    @else
                        <div>
                            <div class="summary-label" style="color:#94a3b8;">Dernière tension</div>
                            <div class="summary-value" style="color:#cbd5e1;">—</div>
                            <div class="summary-sub" style="color:#94a3b8;">Aucune mesure</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="summary-card" style="background:white;box-shadow:0 1px 4px rgba(0,0,0,.04);">
                    <div class="summary-icon" style="background:#fefce8;color:#ca8a04;">
                        <iconify-icon icon="ph:drop-bold"></iconify-icon>
                    </div>
                    @if ($derniereGlycemie)
                        <div>
                            <div class="summary-label" style="color:#94a3b8;">
                                Dernière glycémie · {{ $derniereGlycemie->patient_short }}
                            </div>
                            <div class="summary-value" style="color:#0f172a;">
                                {{ $derniereGlycemie->glycemia_mmol }}
                                <span style="font-size:1rem;font-weight:400;color:#94a3b8;">mmol/L</span>
                            </div>
                            <div class="summary-sub fw-semibold"
                                style="color:{{ $derniereGlycemie->status_label === 'Normal' ? '#16a34a' : '#d97706' }};">
                                @if ($derniereGlycemie->status_label === 'Normal')
                                    <iconify-icon icon="ph:check-circle-fill"></iconify-icon> Bonne valeur
                                @else
                                    <iconify-icon icon="ph:warning-fill"></iconify-icon>
                                    {{ $derniereGlycemie->status_label ?? 'Élevé' }}
                                @endif
                            </div>
                        </div>
                    @else
                        <div>
                            <div class="summary-label" style="color:#94a3b8;">Dernière glycémie</div>
                            <div class="summary-value" style="color:#cbd5e1;">—</div>
                            <div class="summary-sub" style="color:#94a3b8;">Aucune mesure</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let html5QrcodeScanner = null;
            const qrModalElement = document.getElementById('qrScannerModal');
            const qrModal = new bootstrap.Modal(qrModalElement);
            const errorDiv = document.getElementById('scanner-error');

            // Au clic sur "Scanner QR"
            document.getElementById('btn-scan-qr').addEventListener('click', function() {
                qrModal.show();
            });

            // Événement déclenché à l'ouverture complète de la modale Bootstrap
            qrModalElement.addEventListener('shown.bs.modal', function() {
                errorDiv.style.display = 'none';

                // Initialisation du scanner
                html5QrcodeScanner = new Html5Qrcode("qr-reader");

                const config = {
                    fps: 10,
                    qrbox: {
                        width: 200,
                        height: 200
                    },
                    aspectRatio: 1.0
                };

                // Démarrage de la caméra arrière par défaut
                html5QrcodeScanner.start({
                        facingMode: "environment"
                    },
                    config,
                    onScanSuccess
                ).catch(err => {
                    console.error("Erreur d'accès à la caméra : ", err);
                    document.getElementById('qr-reader').innerHTML = `
                <div class="text-white text-center p-4" style="font-size:13px;">
                    <iconify-icon icon="ph:camera-slash-bold" style="font-size:2rem;color:#ef4444;"></iconify-icon><br>
                    Impossible d'accéder à la caméra.<br>Veuillez saisir le code manuellement.
                </div>`;
                });
            });

            // Événement déclenché à la fermeture de la modale
            qrModalElement.addEventListener('hidden.bs.modal', function() {
                stopScanner();
                document.getElementById('manual-qr-form').reset();
            });

            // Callback quand le QR code est détecté avec succès
            function onScanSuccess(decodedText, decodedResult) {
                stopScanner(); // Stop immédiat pour éviter les scans multiples
                findPatientAndRedirect(decodedText);
            }

            // Arrêt propre du flux vidéo de la caméra
            function stopScanner() {
                if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                    html5QrcodeScanner.stop().then(() => {
                        html5QrcodeScanner = null;
                    }).catch(err => console.error("Erreur lors de l'arrêt : ", err));
                }
            }

            // Soumission manuelle du formulaire
            document.getElementById('manual-qr-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const codeInput = document.getElementById('manual_qr_code').value.trim();
                if (codeInput) {
                    findPatientAndRedirect(codeInput);
                }
            });

            // Fonction de traitement et vérification AJAX via la base de données
            function findPatientAndRedirect(qrCodeValue) {
                errorDiv.style.display = 'none';

                console.log("Recherche du patient avec le code : ", qrCodeValue);

                // Requête Fetch vers notre route Laravel
                fetch(`/patients/search-qr?qr_code=${encodeURIComponent(qrCodeValue)}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            qrModal.hide();
                            // Redirection vers l'URL de détail envoyée par le serveur
                            window.location.href = data.redirect_url;
                        } else {
                            errorDiv.innerText = data.message || "Aucun patient trouvé avec ce code CMU.";
                            errorDiv.style.display = 'block';
                            // Si c'était la caméra qui tournait, on la relance si besoin
                            if (html5QrcodeScanner === null) {
                                qrModalElement.dispatchEvent(new Event('shown.bs.modal'));
                            }
                        }
                    })
                    .catch(error => {
                        console.error("Erreur de traitement:", error);
                        errorDiv.innerText = "Une erreur est survenue lors de la vérification.";
                        errorDiv.style.display = 'block';
                    });
            }
        });
    </script>
@endsection
