@extends('layouts.master', ['title' => 'Requêtes reçues'])

@section('content')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Requêtes reçues</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ url('pharma-index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Tableau de bord
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Requêtes</li>
        </ul>
    </div>

    @include('layouts.statuts')

    {{-- Filtres --}}
    <div class="card shadow-none border mb-24 p-16">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-3">
            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="EN_ATTENTE"  {{ request('status') == 'EN_ATTENTE'  ? 'selected' : '' }}>En attente</option>
                <option value="ACCEPTEE"    {{ request('status') == 'ACCEPTEE'    ? 'selected' : '' }}>Acceptée</option>
                <option value="REFUSEE"     {{ request('status') == 'REFUSEE'     ? 'selected' : '' }}>Refusée</option>
                <option value="RESERVE"     {{ request('status') == 'RESERVE'     ? 'selected' : '' }}>Réservée</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}"
                class="form-control form-control-sm w-auto" placeholder="Rechercher un médicament...">
            <button type="submit" class="btn btn-sm btn-primary px-16 py-8 radius-8">Filtrer</button>
            <a href="{{ url('requete') }}" class="btn btn-sm btn-outline-secondary px-16 py-8 radius-8">Réinitialiser</a>
        </form>
    </div>

    <div class="card shadow-none border radius-12 p-0">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="fw-semibold mb-0">Requêtes ({{ $requests->total() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-base border-bottom">
                        <tr>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">#</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Médicament</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Patient</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Date</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Statut</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td class="px-24 py-16 text-sm text-secondary-light">{{ $req->id_request }}</td>
                            <td class="px-24 py-16">
                                <div class="d-flex align-items-center gap-10">
                                    @if($req->photo)
                                        <img src="{{ $req->photo }}" height="36" width="36"
                                            class="rounded object-fit-cover flex-shrink-0" alt="">
                                    @elseif($req->medicament_picture)
                                        <img src="{{ $req->medicament_picture }}" height="36" width="36"
                                            class="rounded object-fit-cover flex-shrink-0" alt="">
                                    @else
                                        <div class="w-36-px h-36-px bg-primary-50 rounded d-flex align-items-center justify-content-center flex-shrink-0">
                                            <iconify-icon icon="healthicons:medicines" class="text-primary-600"></iconify-icon>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="fw-semibold text-sm text-primary-light mb-0">
                                            {{ $req->med_name ?? $req->medicament_name ?? 'Médicament inconnu' }}
                                        </p>
                                        @if($req->comment)
                                            <span class="text-xs text-secondary-light">{{ Str::limit($req->comment, 40) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-24 py-16">
                                <p class="fw-medium text-sm text-primary-light mb-0">
                                    {{ $req->first_name ? $req->first_name . ' ' . $req->last_name : $req->username }}
                                </p>
                                <span class="text-xs text-secondary-light">{{ $req->username }}</span>
                            </td>
                            <td class="px-24 py-16 text-sm text-secondary-light">
                                {{ \Carbon\Carbon::parse($req->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-24 py-16">
                                @php
                                    $statusMap = [
                                        'EN_ATTENTE' => ['class' => 'bg-warning-focus text-warning-main', 'label' => 'En attente'],
                                        'ACCEPTEE'   => ['class' => 'bg-success-focus text-success-main', 'label' => 'Acceptée'],
                                        'REFUSEE'    => ['class' => 'bg-danger-focus text-danger-main',   'label' => 'Refusée'],
                                        'RESERVE'    => ['class' => 'bg-info-focus text-info-main',       'label' => 'Réservée'],
                                    ];
                                    $s = $statusMap[$req->pharmacy_status ?? 'EN_ATTENTE'] ?? $statusMap['EN_ATTENTE'];
                                @endphp
                                <span class="px-12 py-4 rounded-pill text-xs fw-semibold {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-24 py-16 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-8">
                                    {{-- Accepter --}}
                                    @if(in_array($req->pharmacy_status, ['EN_ATTENTE', null]))
                                    <form action="{{ url('requete/' . $req->id_pharmacy_request . '/accepter') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-flex align-items-center justify-content-center border-0"
                                            title="Accepter">
                                            <iconify-icon icon="ph:check-bold"></iconify-icon>
                                        </button>
                                    </form>
                                    {{-- Refuser --}}
                                    <form action="{{ url('requete/' . $req->id_pharmacy_request . '/refuser') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-flex align-items-center justify-content-center border-0"
                                            title="Refuser">
                                            <iconify-icon icon="ph:x-bold"></iconify-icon>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-40 text-secondary-light">
                                <iconify-icon icon="ph:inbox-duotone" style="font-size:48px"></iconify-icon>
                                <p class="mt-12 mb-0">Aucune requête trouvée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($requests->hasPages())
            <div class="d-flex justify-content-between align-items-center px-24 py-16 border-top">
                <p class="text-sm text-secondary-light mb-0">
                    Page {{ $requests->currentPage() }} sur {{ $requests->lastPage() }} — {{ $requests->total() }} requête(s)
                </p>
                <div class="d-flex gap-2">
                    @if($requests->onFirstPage())
                        <button class="btn btn-sm btn-outline-secondary" disabled>Précédent</button>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Précédent</a>
                    @endif
                    @if($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Suivant</a>
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
