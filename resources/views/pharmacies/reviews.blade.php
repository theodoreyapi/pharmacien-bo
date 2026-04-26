@extends('layouts.master', ['title' => 'Avis & Commentaires'])

@section('content')
<div class="dashboard-main-body">

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Avis & Commentaires</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ url('pharma-index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Tableau de bord
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Avis</li>
        </ul>
    </div>

    {{-- Résumé des notes --}}
    <div class="row gy-4 mb-24">

        {{-- Note globale --}}
        <div class="col-md-4">
            <div class="card shadow-none border h-100">
                <div class="card-body p-24 d-flex flex-column align-items-center justify-content-center text-center">
                    <h1 class="fw-bold mb-0" style="font-size: 64px; color: #f59e0b; line-height:1;">
                        {{ number_format($ratingSummary->average ?? 0, 1) }}
                    </h1>
                    <div class="d-flex gap-1 my-10 justify-content-center">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($ratingSummary->average ?? 0))
                                <iconify-icon icon="ph:star-fill" class="text-warning text-xl"></iconify-icon>
                            @else
                                <iconify-icon icon="ph:star" class="text-warning text-xl"></iconify-icon>
                            @endif
                        @endfor
                    </div>
                    <p class="text-secondary-light mb-0">{{ $ratingSummary->counter ?? 0 }} avis au total</p>
                </div>
            </div>
        </div>

        {{-- Répartition des étoiles --}}
        <div class="col-md-8">
            <div class="card shadow-none border h-100">
                <div class="card-body p-24">
                    <h6 class="fw-semibold text-primary-light mb-16">Répartition des notes</h6>

                    @php
                        $stars = [
                            5 => $ratingSummary->counterFiveStars ?? 0,
                            4 => $ratingSummary->counterFourStars ?? 0,
                            3 => $ratingSummary->counterThreeStars ?? 0,
                            2 => $ratingSummary->counterTwoStars ?? 0,
                            1 => $ratingSummary->counterOneStars ?? 0,
                        ];
                        $total = $ratingSummary->counter ?? 1;
                    @endphp

                    @foreach($stars as $star => $count)
                    <div class="d-flex align-items-center gap-12 mb-12">
                        <span class="text-sm fw-medium text-secondary-light" style="min-width: 20px;">{{ $star }}</span>
                        <iconify-icon icon="ph:star-fill" class="text-warning"></iconify-icon>
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 8px; border-radius: 999px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $total > 0 ? round(($count / $total) * 100) : 0 }}%; border-radius: 999px;"
                                    aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $total }}">
                                </div>
                            </div>
                        </div>
                        <span class="text-sm fw-medium text-primary-light" style="min-width: 30px; text-align: right;">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des avis --}}
    <div class="card shadow-none border">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="fw-semibold mb-0">Tous les avis ({{ $reviews->total() }})</h6>

            {{-- Filtre par note --}}
            <form method="GET" action="" class="d-flex align-items-center gap-2">
                <select name="note" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Toutes les notes</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('note') == $i ? 'selected' : '' }}>
                            {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="card-body p-24">
            @forelse($reviews as $review)
            <div class="d-flex gap-16 pb-20 mb-20 {{ !$loop->last ? 'border-bottom' : '' }}">

                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    @if($review->profile_picture)
                        <img src="{{ $review->profile_picture }}" alt="{{ $review->username }}"
                            class="w-48-px h-48-px rounded-circle object-fit-cover">
                    @else
                        <div class="w-48-px h-48-px rounded-circle bg-gradient-start-1 d-flex align-items-center justify-content-center">
                            <span class="fw-bold text-primary-light text-lg">
                                {{ strtoupper(substr($review->username ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Contenu --}}
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-6">
                        <div>
                            <h6 class="fw-semibold text-primary-light mb-0">
                                {{ $review->first_name ? $review->first_name . ' ' . $review->last_name : $review->username }}
                            </h6>
                            <span class="text-sm text-secondary-light">{{ $review->username }}</span>
                        </div>
                        <span class="text-sm text-secondary-light">
                            {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y à H:i') }}
                        </span>
                    </div>

                    {{-- Étoiles --}}
                    <div class="d-flex gap-1 mb-8">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->evaluation)
                                <iconify-icon icon="ph:star-fill" class="text-warning"></iconify-icon>
                            @else
                                <iconify-icon icon="ph:star" class="text-secondary-light"></iconify-icon>
                            @endif
                        @endfor
                        <span class="ms-6 text-sm fw-medium text-primary-light">{{ $review->evaluation }}/5</span>
                    </div>

                    {{-- Commentaire --}}
                    @if($review->commentaire)
                        <p class="text-secondary-light mb-0">{{ $review->commentaire }}</p>
                    @else
                        <p class="text-secondary-light fst-italic mb-0">Aucun commentaire.</p>
                    @endif
                </div>
            </div>
            @empty
                <div class="text-center py-40">
                    <iconify-icon icon="ph:star-duotone" class="text-secondary-light" style="font-size: 48px;"></iconify-icon>
                    <p class="text-secondary-light mt-12 mb-0">Aucun avis pour le moment.</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($reviews->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-20">
                <p class="text-secondary-light text-sm mb-0">
                    Page {{ $reviews->currentPage() }} sur {{ $reviews->lastPage() }}
                </p>
                <div class="d-flex gap-2">
                    @if($reviews->onFirstPage())
                        <button class="btn btn-outline-secondary btn-sm" disabled>Précédent</button>
                    @else
                        <a href="{{ $reviews->previousPageUrl() }}" class="btn btn-outline-primary btn-sm">Précédent</a>
                    @endif

                    @if($reviews->hasMorePages())
                        <a href="{{ $reviews->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">Suivant</a>
                    @else
                        <button class="btn btn-outline-secondary btn-sm" disabled>Suivant</button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
