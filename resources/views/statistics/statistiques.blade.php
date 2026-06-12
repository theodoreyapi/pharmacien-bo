@extends('layouts.master', ['title' => 'Statistiques'])

@section('content')
    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
        }

        /* ── KPI Cards ── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .kpi-card {
            background: white;
            border-radius: 18px;
            padding: 20px 22px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            border: none;
            transition: box-shadow .2s;
        }

        .kpi-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .07);
        }

        .kpi-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .kpi-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* ── Chart cards ── */
        .chart-card {
            background: white;
            border-radius: 18px;
            padding: 22px 24px;
            margin-bottom: 16px;
        }

        .chart-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .chart-title iconify-icon {
            font-size: 1.1rem;
        }

        /* ── Two-col row ── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }

        /* ── Donut legend ── */
        .donut-layout {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .donut-wrap {
            flex-shrink: 0;
        }

        .legend-list {
            flex: 1;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            font-size: 13px;
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-label {
            flex: 1;
            color: #334155;
            font-weight: 500;
        }

        .legend-num {
            font-weight: 700;
            color: #0f172a;
            min-width: 28px;
            text-align: right;
        }

        .legend-pct {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            color: #94a3b8;
            min-width: 36px;
            text-align: right;
        }

        /* ── Mini stat cards ── */
        .mini-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .mini-card {
            border-radius: 16px;
            padding: 18px 20px;
        }

        .mini-card-label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .mini-card-value {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .mini-card-sub {
            font-size: 12px;
        }

        /* ── Observance table ── */
        .obs-card {
            background: white;
            border-radius: 18px;
            padding: 22px 24px;
        }

        .obs-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .obs-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .filter-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .filter-chip:hover {
            border-color: #94a3b8;
        }

        .filter-chip.active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .patient-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .patient-row:last-child {
            border-bottom: none;
        }

        .p-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            flex-shrink: 0;
        }

        .p-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .p-age {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 400;
            margin-left: 5px;
        }

        .p-date {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .tag {
            display: inline-flex;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .02em;
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

        .obs-status {
            margin-left: auto;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .obs-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @media (max-width: 991.98px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .two-col,
            .mini-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .dash-body {
                padding: 16px;
            }

            .kpi-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <div class="dash-body">

    {{-- ══ KPI Cards ══ --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div>
                <div class="kpi-label">Patients actifs</div>
                <div class="kpi-value">{{ $patientsActifs }}</div>
                <div class="kpi-sub">+{{ $nouveauxPatientsCeMois }} ce mois</div>
            </div>
            <div class="kpi-icon" style="background:#ecfdf5;color:#16a34a;">
                <iconify-icon icon="ph:users-three-bold"></iconify-icon>
            </div>
        </div>
        <div class="kpi-card">
            <div>
                <div class="kpi-label">Taux renouvellement</div>
                <div class="kpi-value">{{ $tauxRenouvellement }}%</div>
                <div class="kpi-sub">À temps ce mois</div>
            </div>
            <div class="kpi-icon" style="background:#eff6ff;color:#2563eb;">
                <iconify-icon icon="ph:trend-up-bold"></iconify-icon>
            </div>
        </div>
        <div class="kpi-card">
            <div>
                <div class="kpi-label">Retards critiques</div>
                <div class="kpi-value">{{ $retardsCritiques }}</div>
                <div class="kpi-sub">Action requise</div>
            </div>
            <div class="kpi-icon" style="background:#fef2f2;color:#dc2626;">
                <iconify-icon icon="ph:arrows-counter-clockwise-bold"></iconify-icon>
            </div>
        </div>
        <div class="kpi-card">
            <div>
                <div class="kpi-label">Rappels envoyés</div>
                <div class="kpi-value">{{ $rappelsEnvoyesMois }}</div>
                <div class="kpi-sub">Ce mois</div>
            </div>
            <div class="kpi-icon" style="background:#faf5ff;color:#9333ea;">
                <iconify-icon icon="ph:bell-bold"></iconify-icon>
            </div>
        </div>
    </div>

    {{-- ══ Évolution des patients suivis ══ --}}
    <div class="chart-card">
        <div class="chart-title">
            <iconify-icon icon="ph:trend-up-bold" style="color:#16a34a;"></iconify-icon>
            Évolution des patients suivis
        </div>
        <canvas id="evolutionChart" height="90"></canvas>
    </div>

    {{-- ══ Répartition + Observance globale ══ --}}
    <div class="two-col">
        <div class="chart-card mb-0">
            <div class="chart-title">
                <iconify-icon icon="ph:activity-bold" style="color:#9333ea;"></iconify-icon>
                Répartition par pathologie
            </div>
            <canvas id="repartitionChart" height="160"></canvas>
        </div>
        <div class="chart-card mb-0">
            <div class="chart-title">
                <iconify-icon icon="ph:users-bold" style="color:#2563eb;"></iconify-icon>
                Observance globale
            </div>
            <div class="donut-layout">
                <div class="donut-wrap">
                    <canvas id="observanceDonut" width="140" height="140"></canvas>
                </div>
                <div class="legend-list">
                    @foreach ($statusesDef as $key => $status)
                        @php
                            $pct = $totalPatientsObs > 0 ? round(($status['count'] / $totalPatientsObs) * 100) : 0;
                        @endphp
                        <div class="legend-row">
                            <span class="legend-dot" style="background:{{ $status['color'] }};"></span>
                            <span class="legend-label">{{ $status['label'] }}</span>
                            <span class="legend-num">{{ $status['count'] }}</span>
                            <span class="legend-pct">{{ $pct }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Activité mensuelle ══ --}}
    <div class="chart-card" style="margin-top:16px;">
        <div class="chart-title">
            <iconify-icon icon="ph:activity-bold" style="color:#f97316;"></iconify-icon>
            Activité mensuelle — Mesures & Rappels
        </div>
        <canvas id="activiteChart" height="100"></canvas>
    </div>

    {{-- ══ Mini stat cards ══ --}}
    <div class="mini-stats">
        <div class="mini-card" style="background:#f0fdf4;">
            <div class="mini-card-label" style="color:#16a34a;">Taux de lecture des messages</div>
            <div class="mini-card-value" style="color:#16a34a;">{{ $tauxLectureRappels }}%</div>
            <div class="mini-card-sub" style="color:#64748b;">{{ $rappelsLusCeMois }} sur {{ $totalRappelsCeMois }} messages lus</div>
        </div>
        <div class="mini-card" style="background:#eff6ff;">
            <div class="mini-card-label" style="color:#2563eb;">Délai moyen premier contact</div>
            <div class="mini-card-value" style="color:#2563eb;">1.8j</div>
            <div class="mini-card-sub" style="color:#64748b;">Après inscription patient</div>
        </div>
        <div class="mini-card" style="background:#fff7ed;">
            <div class="mini-card-label" style="color:#d97706;">Patients sans mesure >30j</div>
            <div class="mini-card-value" style="color:#d97706;">{{ $patientsSansMesure30j }}</div>
            <div class="mini-card-sub" style="color:#64748b;">Nécessitent un contrôle</div>
        </div>
    </div>

    {{-- ══ Observance par patient ══ --}}
    <div class="obs-card">
        <div class="obs-header">
            <div class="obs-title">
                <iconify-icon icon="ph:warning-octagon-bold" style="color:#f59e0b;font-size:1.1rem;"></iconify-icon>
                Observance par patient
            </div>
            <div class="filter-chips">
                <button class="filter-chip active" onclick="filterObs(this,'all')">Tous</button>
                <button class="filter-chip" onclick="filterObs(this,'CRITIQUE')">Critique</button>
                <button class="filter-chip" onclick="filterObs(this,'EN_RETARD')">En retard</button>
                <button class="filter-chip" onclick="filterObs(this,'BIENTOT_RETARD')">Bientôt</button>
                <button class="filter-chip" onclick="filterObs(this,'A_JOUR')">À jour</button>
            </div>
        </div>

        <div id="obs-list">
            @foreach ($patients as $patient)
                @php
                    // Logique visuelle selon le statut de la base de données
                    $statusMapping = [
                        'A_JOUR' => ['label' => 'À jour', 'color' => '#16a34a'],
                        'BIENTOT_RETARD' => ['label' => 'Bientôt retard', 'color' => '#f59e0b'],
                        'EN_RETARD' => ['label' => 'En retard', 'color' => '#f97316'],
                        'CRITIQUE' => ['label' => 'Critique', 'color' => '#dc2626']
                    ];
                    $currentStatus = $statusMapping[$patient->status] ?? ['label' => 'Inconnu', 'color' => '#64748b'];

                    // Avatar custom selon genre
                    $avatarBg = $patient->gender === 'FEMME' ? '#fce4ec' : '#e8eaf6';
                    $avatarColor = $patient->gender === 'FEMME' ? '#c2185b' : '#3949ab';
                    $initials = strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1));

                    // Calcul de l'âge
                    $age = $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age : '?';

                    // Dernière mesure date
                    $derniereMesure = $patient->mesures->first();
                    $dateMesure = $derniereMesure ? \Carbon\Carbon::parse($derniereMesure->created_at)->translatedFormat('d M.') : 'Aucune';
                @endphp

                <div class="patient-row" data-obs="{{ $patient->status }}">
                    <div class="p-avatar" style="background:{{ $avatarBg }};color:{{ $avatarColor }};">
                        {{ $initials }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div>
                            <span class="p-name">{{ $patient->gender === 'FEMME' ? 'Mme' : 'M.' }} {{ $patient->first_name }} {{ $patient->last_name }}</span>
                            <span class="p-age">{{ $age }} ans</span>
                            @foreach ($patient->pathologies as $pathologie)
                                <span class="tag tag-{{ strtolower($pathologie->code) }} ms-1">{{ $pathologie->code }}</span>
                            @endforeach
                        </div>
                        <div class="p-date">Dernière mesure : {{ $dateMesure }}</div>
                    </div>
                    <div class="obs-status" style="color:{{ $currentStatus['color'] }};">
                        <span class="obs-dot" style="background:{{ $currentStatus['color'] }};"></span>
                        {{ $currentStatus['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    // Injection sécurisée des variables PHP cryptées en JSON pour JS
    const months = @json($monthsLabels);
    const grid = '#f1f5f9';

    const baseOpts = {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: grid }, ticks: { font: { size: 11, family: 'DM Sans' } } },
            y: { grid: { color: grid }, ticks: { font: { size: 11, family: 'DM Sans' } } }
        }
    };

    /* Évolution patients */
    new Chart(document.getElementById('evolutionChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Patients',
                    data: @json($datasetPatients),
                    borderColor: '#16a34a',
                    backgroundColor: 'transparent',
                    tension: .4,
                    pointBackgroundColor: '#16a34a',
                    pointRadius: 5
                },
                {
                    label: 'Mesures',
                    data: @json($datasetMesures),
                    borderColor: '#3b82f6',
                    backgroundColor: 'transparent',
                    tension: .4,
                    borderDash: [5, 4],
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4
                },
                {
                    label: 'Rappels',
                    data: @json($datasetRappels),
                    borderColor: '#f59e0b',
                    backgroundColor: 'transparent',
                    tension: .4,
                    borderDash: [5, 4],
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 4
                }
            ]
        },
        options: {
            ...baseOpts,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { font: { size: 11, family: 'DM Sans' }, usePointStyle: true, pointStyleWidth: 10 }
                }
            }
        }
    });

    /* Répartition par pathologie */
    const pathologiesLabels = @json($pathologiesData->pluck('name'));
    const pathologiesCount = @json($pathologiesData->pluck('total'));

    new Chart(document.getElementById('repartitionChart'), {
        type: 'bar',
        data: {
            labels: pathologiesLabels,
            datasets: [{
                data: pathologiesCount,
                backgroundColor: ['#ef4444', '#f97316', '#3b82f6', '#9333ea', '#06b6d4'],
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: grid }, ticks: { font: { size: 11 } } },
                y: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    /* Observance donut */
    new Chart(document.getElementById('observanceDonut'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ $statusesDef['A_JOUR']['count'] }},
                    {{ $statusesDef['BIENTOT_RETARD']['count'] }},
                    {{ $statusesDef['EN_RETARD']['count'] }},
                    {{ $statusesDef['CRITIQUE']['count'] }}
                ],
                backgroundColor: ['#16a34a', '#f59e0b', '#f97316', '#dc2626'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: false,
            cutout: '68%',
            plugins: { legend: { display: false } }
        }
    });

    /* Activité mensuelle */
    new Chart(document.getElementById('activiteChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'mesures',
                    data: @json($datasetMesures),
                    backgroundColor: '#3b82f6',
                    borderRadius: 5
                },
                {
                    label: 'rappels',
                    data: @json($datasetRappels),
                    backgroundColor: '#f97316',
                    borderRadius: 5
                }
            ]
        },
        options: {
            ...baseOpts,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { font: { size: 11, family: 'DM Sans' }, usePointStyle: true, pointStyleWidth: 10 }
                }
            }
        }
    });

    /* Filtre observance */
    function filterObs(btn, filter) {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('#obs-list .patient-row').forEach(row => {
            row.style.display = (filter === 'all' || row.dataset.obs === filter) ? '' : 'none';
        });
    }
</script>
@endsection
