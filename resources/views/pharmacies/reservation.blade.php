@extends('layouts.master', ['title' => 'Réservations'])

@section('content')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Réservations</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ url('pharma-index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Tableau de bord
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Réservations</li>
        </ul>
    </div>

    @include('layouts.statuts')

    {{-- Filtres --}}
    <div class="card shadow-none border mb-24 p-16">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-3">
            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="RESERVE"   {{ request('status') == 'RESERVE'   ? 'selected' : '' }}>Réservé</option>
                <option value="SERVI"     {{ request('status') == 'SERVI'     ? 'selected' : '' }}>Servi</option>
                <option value="EXPIRE"    {{ request('status') == 'EXPIRE'    ? 'selected' : '' }}>Expiré</option>
                <option value="ANNULE"    {{ request('status') == 'ANNULE'    ? 'selected' : '' }}>Annulé</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary px-16 py-8 radius-8">Filtrer</button>
            <a href="{{ url('reservations') }}" class="btn btn-sm btn-outline-secondary px-16 py-8 radius-8">Réinitialiser</a>
        </form>
    </div>

    <div class="card shadow-none border radius-12 p-0">
        <div class="card-header border-bottom bg-base py-16 px-24">
            <h6 class="fw-semibold mb-0">Réservations ({{ $reservations->total() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-base border-bottom">
                        <tr>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">#</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Médicament</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Patient</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Réservé le</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Expire le</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Statut</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                        @php
                            $isExpired = $res->date_expiration && \Carbon\Carbon::parse($res->date_expiration)->isPast();
                        @endphp
                        <tr class="{{ $isExpired && $res->status === 'RESERVE' ? 'table-warning' : '' }}">
                            <td class="px-24 py-16 text-sm text-secondary-light">{{ $res->id_reservation }}</td>
                            <td class="px-24 py-16">
                                <div class="d-flex align-items-center gap-10">
                                    @if($res->medicament_picture)
                                        <img src="{{ $res->medicament_picture }}" height="36" width="36"
                                            class="rounded object-fit-cover" alt="">
                                    @else
                                        <div class="w-36-px h-36-px bg-primary-50 rounded d-flex align-items-center justify-content-center">
                                            <iconify-icon icon="healthicons:medicines" class="text-primary-600"></iconify-icon>
                                        </div>
                                    @endif
                                    <p class="fw-semibold text-sm text-primary-light mb-0">{{ $res->med_name ?? '—' }}</p>
                                </div>
                            </td>
                            <td class="px-24 py-16">
                                <p class="fw-medium text-sm text-primary-light mb-0">
                                    {{ $res->first_name ? $res->first_name . ' ' . $res->last_name : $res->user_name }}
                                </p>
                                <span class="text-xs text-secondary-light">{{ $res->user_name }}</span>
                            </td>
                            <td class="px-24 py-16 text-sm text-secondary-light">
                                {{ $res->date_reservation ? \Carbon\Carbon::parse($res->date_reservation)->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td class="px-24 py-16 text-sm {{ $isExpired ? 'text-danger-main fw-semibold' : 'text-secondary-light' }}">
                                {{ $res->date_expiration ? \Carbon\Carbon::parse($res->date_expiration)->format('d/m/Y H:i') : '—' }}
                                @if($isExpired && $res->status === 'RESERVE')
                                    <br><span class="text-xs text-danger-main">Expirée</span>
                                @endif
                            </td>
                            <td class="px-24 py-16">
                                @php
                                    $statusMap = [
                                        'RESERVE' => ['class' => 'bg-info-focus text-info-main',         'label' => 'Réservé'],
                                        'SERVI'   => ['class' => 'bg-success-focus text-success-main',   'label' => 'Servi'],
                                        'EXPIRE'  => ['class' => 'bg-warning-focus text-warning-main',   'label' => 'Expiré'],
                                        'ANNULE'  => ['class' => 'bg-danger-focus text-danger-main',     'label' => 'Annulé'],
                                    ];
                                    $s = $statusMap[$res->status] ?? ['class' => 'bg-neutral-focus text-neutral-main', 'label' => $res->status];
                                @endphp
                                <span class="px-12 py-4 rounded-pill text-xs fw-semibold {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>
                            <td class="px-24 py-16 text-center">
                                @if($res->status === 'RESERVE' && !$isExpired)
                                <form action="{{ url('reservations/' . $res->id_reservation . '/servir') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-success px-12 py-6 radius-8"
                                        title="Marquer comme servi">
                                        <iconify-icon icon="ph:check-circle-bold" class="me-1"></iconify-icon>
                                        Servi
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-40 text-secondary-light">
                                <iconify-icon icon="ph:calendar-x-duotone" style="font-size:48px"></iconify-icon>
                                <p class="mt-12 mb-0">Aucune réservation trouvée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservations->hasPages())
            <div class="d-flex justify-content-between align-items-center px-24 py-16 border-top">
                <p class="text-sm text-secondary-light mb-0">
                    Page {{ $reservations->currentPage() }} sur {{ $reservations->lastPage() }} — {{ $reservations->total() }} réservation(s)
                </p>
                <div class="d-flex gap-2">
                    @if($reservations->onFirstPage())
                        <button class="btn btn-sm btn-outline-secondary" disabled>Précédent</button>
                    @else
                        <a href="{{ $reservations->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Précédent</a>
                    @endif
                    @if($reservations->hasMorePages())
                        <a href="{{ $reservations->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Suivant</a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" disabled>Suivant</button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
