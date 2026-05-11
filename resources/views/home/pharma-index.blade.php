@extends('layouts.master', ['title' => 'Tableau de bord'])

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Tableau de bord</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Tableau de bord
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Accueil</li>
            </ul>
        </div>

        {{-- ══════════════════════════════════════
     SECTION — RENDEZ-VOUS
══════════════════════════════════════ --}}
        <p class="fw-semibold text-secondary-light text-sm mb-12">💉 Rendez-vous vaccination</p>

        <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mb-24">

            {{-- Total --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Total RDV</p>
                                <h6 class="mb-0">{{ $statistiques['totalAppointments'] }}</h6>
                            </div>

                            <div
                                class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:calendar-plus-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- En attente --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">En attente</p>
                                <h6 class="mb-0 text-warning-main">
                                    {{ $statistiques['totalPendingAppointments'] }}
                                </h6>
                            </div>

                            <div
                                class="w-50-px h-50-px bg-warning-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:clock-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Confirmés --}}
            <div class="col">
                <div class="card shadow-none border bg-gradient-start-3 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Confirmés</p>
                                <h6 class="mb-0 text-success-main">
                                    {{ $statistiques['totalConfirmedAppointments'] }}
                                </h6>
                            </div>

                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:check-circle-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terminés --}}
            <div class="col">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Terminés</p>
                                <h6 class="mb-0 text-info-main">
                                    {{ $statistiques['totalCompletedAppointments'] }}
                                </h6>
                            </div>

                            <div
                                class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:syringe-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Annulés --}}
            <div class="col">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Annulés</p>
                                <h6 class="mb-0 text-danger-main">
                                    {{ $statistiques['totalCancelledAppointments'] }}
                                </h6>
                            </div>

                            <div
                                class="w-50-px h-50-px bg-danger-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:x-circle-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════
         SECTION 1 — REQUÊTES
    ══════════════════════════════════════ --}}
        {{-- <p class="fw-semibold text-secondary-light text-sm mb-12 mt-4">📋 Requêtes</p>
        <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mb-24">

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Requêtes reçues</p>
                                <h6 class="mb-0">{{ $statistiques['totalReceived'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:inbox-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">En attente</p>
                                <h6 class="mb-0 text-warning-main">{{ $statistiques['totalEnAttente'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-warning-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:clock-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-3 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Acceptées</p>
                                <h6 class="mb-0 text-success-main">{{ $statistiques['totalAcceptees'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:check-circle-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Refusées</p>
                                <h6 class="mb-0 text-danger-main">{{ $statistiques['totalRefusees'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-danger-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:x-circle-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Traitées</p>
                                <h6 class="mb-0">{{ $statistiques['totalSent'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:paper-plane-right-bold"
                                    class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- ══════════════════════════════════════
         SECTION 2 — RÉSERVATIONS
    ══════════════════════════════════════ --}}
        {{-- <p class="fw-semibold text-secondary-light text-sm mb-12">📦 Réservations</p>
        <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mb-24">

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Total réservations</p>
                                <h6 class="mb-0">{{ $statistiques['totalReservations'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:calendar-check-bold"
                                    class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">En cours</p>
                                <h6 class="mb-0 text-info-main">{{ $statistiques['totalReserve'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:hourglass-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-3 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Servies</p>
                                <h6 class="mb-0 text-success-main">{{ $statistiques['totalServi'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:bag-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Expirées</p>
                                <h6 class="mb-0 text-danger-main">{{ $statistiques['totalExpire'] }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-danger-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:calendar-x-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- ══════════════════════════════════════
         SECTION 3 — WALLET & AVIS
    ══════════════════════════════════════ --}}
        <p class="fw-semibold text-secondary-light text-sm mb-12">💰 Wallet & Avis</p>
        <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4 mb-24">

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-4 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Solde actuel</p>
                                <h6 class="mb-0 fw-bold">{{ $solde }}</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Total reçu</p>
                                <h6 class="mb-0 text-success-main">{{ $statistiques['totalCredit'] }} FCFA</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:arrow-down-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Total envoyé</p>
                                <h6 class="mb-0 text-danger-main">{{ $statistiques['totalDebit'] }} FCFA</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:arrow-up-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Rechargements</p>
                                <h6 class="mb-0">{{ $statistiques['totalRechargements'] }} FCFA</h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:plus-circle-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-none border bg-gradient-start-3 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Note moyenne</p>
                                <h6 class="mb-0">
                                    {{ $statistiques['noteMoyenne'] }}/5
                                    <span class="text-warning-main">★</span>
                                    <span class="text-secondary-light text-sm fw-normal">({{ $statistiques['totalAvis'] }}
                                        avis)</span>
                                </h6>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-warning-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:star-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
         GRAPHIQUE
    ══════════════════════════════════════ --}}
        {{-- <div class="row gy-4 mt-1">
            <div class="col-xxl-12 col-xl-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <h6 class="text-lg mb-0">Statistiques des requêtes {{ date('Y') }}</h6>
                        </div>
                        <div class="row mt-8">
                            <div class="col-auto">
                                @php $total = array_sum(array_column($souscriptions, 'requestCount')); @endphp
                                <h6 class="mb-0 text-success">{{ number_format($total, 0, ',', ' ') }} Requêtes</h6>
                            </div>
                            <div class="col-auto">
                                @php $totalres = array_sum(array_column($souscriptions, 'responseCount')); @endphp
                                <h6 class="mb-0 text-primary">{{ number_format($totalres, 0, ',', ' ') }} Réponses</h6>
                            </div>
                            <div class="col-auto">
                                @php $totalreser = array_sum(array_column($souscriptions, 'reservationCount')); @endphp
                                <h6 class="mb-0" style="color:#FF9800;">{{ number_format($totalreser, 0, ',', ' ') }}
                                    Réservations</h6>
                            </div>
                        </div>
                        <br>
                        <canvas id="statistiquesChart"></canvas>
                        <script>
                            const statistiques = @json($souscriptions);
                            const labels = statistiques.map(item => item.mois);
                            const requestData = statistiques.map(item => item.requestCount);
                            const responseData = statistiques.map(item => item.responseCount);
                            const reservationData = statistiques.map(item => item.reservationCount);
                            const ctx = document.getElementById('statistiquesChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'Requêtes',
                                            data: requestData,
                                            borderColor: '#4CAF50',
                                            backgroundColor: 'rgba(76,175,80,0.2)',
                                            fill: true,
                                            tension: 0.4
                                        },
                                        {
                                            label: 'Réponses',
                                            data: responseData,
                                            borderColor: '#2196F3',
                                            backgroundColor: 'rgba(33,150,243,0.2)',
                                            fill: true,
                                            tension: 0.4
                                        },
                                        {
                                            label: 'Réservations',
                                            data: reservationData,
                                            borderColor: '#FF9800',
                                            backgroundColor: 'rgba(255,152,0,0.2)',
                                            fill: true,
                                            tension: 0.4
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'bottom'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: v => v.toLocaleString()
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
@endsection
