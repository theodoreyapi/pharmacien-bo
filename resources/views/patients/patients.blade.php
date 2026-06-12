@extends('layouts.master', ['title' => 'Patients'])

@section('content')
    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
        }

        .kpi-card {
            background: white;
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            border: none;
            flex: 1;
            min-width: 0;
            transition: box-shadow .2s;
        }

        .kpi-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .07);
        }

        .kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .kpi-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 3px;
        }

        .kpi-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border-radius: 12px;
            padding: 10px 16px;
            flex: 1;
            border: 1px solid #e8edf0;
        }

        .search-bar input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #334155;
            width: 100%;
            font-family: 'DM Sans', sans-serif;
        }

        .search-bar input::placeholder {
            color: #94a3b8;
        }

        .btn-filtre {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }

        .btn-filtre:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .btn-filtre.active {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .btn-add {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 12px;
            border: none;
            background: #16a34a;
            font-size: 13px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: background .15s;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }

        .btn-add:hover {
            background: #15803d;
            color: white;
        }

        .filter-panel {
            background: white;
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid #e8edf0;
            display: none;
        }

        .filter-panel.show {
            display: block;
        }

        .filter-group-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .filter-chip:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .filter-chip.active {
            background: #16a34a;
            color: white;
            border-color: #16a34a;
        }

        .patients-table-wrap {
            background: white;
            border-radius: 18px;
            padding: 24px;
        }

        .patients-count {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
        }

        .patient-row {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border-radius: 14px;
            transition: background .15s;
            gap: 12px;
            border-bottom: 1px solid #f8fafc;
        }

        .patient-row:last-child {
            border-bottom: none;
        }

        .patient-row:hover {
            background: #f8fafc;
        }

        .p-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .p-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .p-age {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 400;
            margin-left: 6px;
        }

        .p-phone {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .tag-hta {
            background: #fce4ec;
            color: #c2185b;
        }

        .tag-diab {
            background: #fff3e0;
            color: #e65100;
        }

        .tag-dyslip {
            background: #e8eaf6;
            color: #3949ab;
        }

        .tag-asth {
            background: #e0f2f1;
            color: #00695c;
        }

        .mesure-date {
            font-size: 12px;
            color: #94a3b8;
            white-space: nowrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-ok {
            background: #ecfdf5;
            color: #16a34a;
        }

        .status-ok .status-dot {
            background: #16a34a;
        }

        .status-retard {
            background: #fff7ed;
            color: #d97706;
        }

        .status-retard .status-dot {
            background: #d97706;
        }

        .status-critique {
            background: #fef2f2;
            color: #dc2626;
        }

        .status-critique .status-dot {
            background: #dc2626;
        }

        .status-bientot {
            background: #fefce8;
            color: #ca8a04;
        }

        .status-bientot .status-dot {
            background: #ca8a04;
        }

        .p-actions {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .p-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: background .15s;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }

        .p-action-btn:hover {
            background: #f1f5f9;
            color: #334155;
        }

        .p-action-btn.voir {
            color: #16a34a;
        }

        .p-action-btn.voir:hover {
            background: #ecfdf5;
            color: #16a34a;
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #94a3b8;
            font-size: 13px;
        }

        @media (max-width:767.98px) {
            .dash-body {
                padding: 16px;
            }

            .kpi-cards-row {
                flex-direction: column;
            }

            .p-actions,
            .mesure-date {
                display: none;
            }
        }
    </style>

    <div class="dash-body">

        {{-- ══ KPI ══ --}}
        <div class="d-flex gap-3 mb-4 kpi-cards-row flex-wrap">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:#ecfdf5;color:#16a34a;">
                    <iconify-icon icon="ph:users-three-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-value">{{ $totalPatients }}</div>
                    <div class="kpi-label">Patients suivis</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:#ecfdf5;color:#16a34a;">
                    <iconify-icon icon="ph:trend-up-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-value">{{ $aJour }}</div>
                    <div class="kpi-label">À jour</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:#fef2f2;color:#dc2626;">
                    <iconify-icon icon="ph:warning-octagon-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-value">{{ $enRetard }}</div>
                    <div class="kpi-label">En retard / Critique</div>
                </div>
            </div>
        </div>

        <br>

        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

        {{-- ══ Barre recherche + actions ══ --}}
        <div class="d-flex gap-2 mb-3 flex-wrap">
            <form method="GET" action="{{ route('patients.index') }}" class="d-flex gap-2 flex-wrap flex-grow-1"
                id="searchForm">
                <label class="search-bar flex-grow-1">
                    <iconify-icon icon="ph:magnifying-glass-bold"
                        style="color:#94a3b8;font-size:1.1rem;flex-shrink:0;"></iconify-icon>
                    <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                        oninput="debounceSubmit()" id="searchInput">
                </label>
                <button type="button" class="btn-filtre {{ request('patho') || request('obs') ? 'active' : '' }}"
                    id="btnFiltre" onclick="toggleFiltres()">
                    <iconify-icon icon="ph:funnel-bold"></iconify-icon> Filtres
                    <iconify-icon icon="ph:caret-down-bold" id="filtreChevron"></iconify-icon>
                </button>
            </form>
            <a href="javascript:void(0);" id="btn-scan-qr" class="quick-action btn-filtre">
                <iconify-icon icon="ph:qr-code-bold"></iconify-icon> Scanner QR
            </a>
            <a href="{{ url('add-patient') }}" class="btn-add">
                <iconify-icon icon="ph:plus-bold"></iconify-icon> Ajouter patient
            </a>
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

        {{-- ══ Panel Filtres ══ --}}
        <div class="filter-panel mb-3 {{ request('patho') || request('obs') ? 'show' : '' }}" id="filterPanel">
            <form method="GET" action="{{ route('patients.index') }}" id="filterForm">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="row g-4">
                    <div class="col-auto">
                        <div class="filter-group-label">Pathologie</div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" name="patho" value=""
                                class="filter-chip {{ !request('patho') ? 'active' : '' }}">Toutes</button>
                            @foreach ($allPathologies as $patho)
                                <button type="submit" name="patho" value="{{ $patho->code }}"
                                    class="filter-chip {{ request('patho') === $patho->code ? 'active' : '' }}">
                                    {{ $patho->code }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="filter-group-label">Observance</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach (['' => 'Tous', 'ajour' => 'À jour', 'retard' => 'En retard', 'critique' => 'Critique', 'bientot' => 'Bientôt'] as $val => $label)
                                <button type="submit" name="obs" value="{{ $val }}"
                                    class="filter-chip {{ request('obs', '') === $val ? 'active' : '' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- ══ Table patients ══ --}}
        <div class="patients-table-wrap">
            <div class="patients-count">
                {{ $patients->count() }} patient{{ $patients->count() > 1 ? 's' : '' }}
                @if (request('search'))
                    pour « {{ request('search') }} »
                @endif
            </div>

            @if ($patients->isEmpty())
                <div class="empty-state">
                    <iconify-icon icon="ph:users-three-bold"
                        style="font-size:2.5rem;color:#d1fae5;display:block;margin-bottom:12px;"></iconify-icon>
                    Aucun patient trouvé
                    @if (request('search') || request('patho') || request('obs'))
                        <br><a href="{{ route('patients.index') }}"
                            style="color:#16a34a;font-weight:600;font-size:12px;">Réinitialiser les filtres</a>
                    @endif
                </div>
            @else
                @foreach ($patients as $patient)
                    <div class="patient-row">
                        {{-- Avatar --}}
                        <div class="p-avatar"
                            style="background:{{ $patient->avatar_bg }};color:{{ $patient->avatar_color }};">
                            {{ $patient->initials }}
                        </div>

                        {{-- Nom + téléphone --}}
                        <div style="min-width:180px;">
                            <div class="p-name">
                                {{ $patient->first_name }} {{ $patient->last_name }}
                                @if ($patient->age)
                                    <span class="p-age">{{ $patient->age }} ans</span>
                                @endif
                            </div>
                            <div class="p-phone">
                                <iconify-icon icon="ph:phone-bold"></iconify-icon>
                                {{ $patient->phone_number }}
                            </div>
                        </div>

                        {{-- Tags pathologies --}}
                        <div class="d-flex gap-1 flex-wrap" style="min-width:120px;">
                            @foreach (array_filter(explode(',', $patient->pathologies_list ?? '')) as $code)
                                <span class="tag tag-{{ strtolower(trim($code)) }}">{{ trim($code) }}</span>
                            @endforeach
                        </div>

                        {{-- Dernière mesure --}}
                        <div class="mesure-date" style="min-width:130px;">
                            @if ($patient->last_measure_label)
                                Mesure : {{ $patient->last_measure_label }}
                            @else
                                <span style="color:#cbd5e1;">Aucune mesure</span>
                            @endif
                        </div>

                        {{-- Statut --}}
                        <div style="min-width:120px;">
                            <span class="status-badge {{ $patient->status_class }}">
                                <span class="status-dot"></span>
                                {{ $patient->status_label }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="p-actions ms-auto">
                            <a href="{{ route('patients.show', $patient->id_patient) }}" class="p-action-btn voir">
                                <iconify-icon icon="ph:eye-bold"></iconify-icon> Voir
                            </a>
                            <a href="{{ route('patients.show', $patient->id_patient) }}#rappel" class="p-action-btn">
                                <iconify-icon icon="ph:bell-bold"></iconify-icon> Rappel
                            </a>
                            <a href="{{ route('patients.show', $patient->id_patient) }}#mesure" class="p-action-btn">
                                <iconify-icon icon="ph:heartbeat-bold"></iconify-icon> Mesure
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
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

    <script>
        let filtreOpen = {{ request('patho') || request('obs') ? 'true' : 'false' }};
        let searchTimer;

        function toggleFiltres() {
            filtreOpen = !filtreOpen;
            document.getElementById('filterPanel').classList.toggle('show', filtreOpen);
            document.getElementById('btnFiltre').classList.toggle('active', filtreOpen);
            document.getElementById('filtreChevron').setAttribute('icon',
                filtreOpen ? 'ph:caret-up-bold' : 'ph:caret-down-bold');
        }

        // Debounce recherche → soumet le form après 400ms de pause
        function debounceSubmit() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 400);
        }
    </script>
@endsection
