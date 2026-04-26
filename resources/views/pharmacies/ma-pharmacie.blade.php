@extends('layouts.master', ['title' => 'Ma Pharmacie'])

@section('content')
<div class="dashboard-main-body">

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Ma Pharmacie</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ url('pharma-index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Tableau de bord
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Ma Pharmacie</li>
        </ul>
    </div>

    @include('layouts.statuts')

    {{-- Card principale --}}
    <div class="card shadow-none border radius-12 p-0">

        {{-- Photo de façade --}}
        <div class="position-relative" style="height: 220px; overflow: hidden; border-radius: 12px 12px 0 0; background: #e9ecef;">
            @if($pharmacy->facade_image)
                <img src="{{ $pharmacy->facade_image }}" alt="{{ $pharmacy->name }}"
                    class="w-100 h-100" style="object-fit: cover;">
            @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-start-1">
                    <iconify-icon icon="healthicons:pharmacy-outline" style="font-size: 80px; color: #adb5bd;"></iconify-icon>
                </div>
            @endif
            {{-- Bouton changer photo via modal --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                class="btn btn-sm btn-primary position-absolute"
                style="bottom: 16px; right: 16px; border-radius: 8px;">
                <iconify-icon icon="ic:baseline-edit" class="me-1"></iconify-icon>
                Modifier
            </button>
        </div>

        <div class="card-body p-24">

            {{-- Nom et commune --}}
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-24">
                <div>
                    <h4 class="fw-bold text-primary-light mb-4">{{ $pharmacy->name ?? '—' }}</h4>
                    <p class="text-secondary-light mb-0 d-flex align-items-center gap-1">
                        <iconify-icon icon="solar:map-point-bold"></iconify-icon>
                        {{ $pharmacy->commune_name ?? '—' }} — {{ $pharmacy->address ?? '—' }}
                    </p>
                </div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                    class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-edit"></iconify-icon>
                    Modifier les informations
                </button>
            </div>

            <hr class="my-20">

            {{-- Détails --}}
            <div class="row gy-4">

                <div class="col-md-6 col-xl-4">
                    <div class="d-flex align-items-start gap-12">
                        <div class="w-40-px h-40-px bg-primary-50 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                            <iconify-icon icon="ph:phone-bold" class="text-primary-600"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-secondary-light text-sm mb-2">Téléphone</p>
                            <p class="fw-semibold text-primary-light mb-0">{{ $pharmacy->phone_number ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="d-flex align-items-start gap-12">
                        <div class="w-40-px h-40-px bg-success-focus rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                            <iconify-icon icon="ri:whatsapp-line" class="text-success-main"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-secondary-light text-sm mb-2">WhatsApp</p>
                            <p class="fw-semibold text-primary-light mb-0">{{ $pharmacy->whats_app_phone_number ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="d-flex align-items-start gap-12">
                        <div class="w-40-px h-40-px bg-warning-focus rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                            <iconify-icon icon="ph:user-bold" class="text-warning-main"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-secondary-light text-sm mb-2">Propriétaire</p>
                            <p class="fw-semibold text-primary-light mb-0">{{ $pharmacy->owner_name ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="d-flex align-items-start gap-12">
                        <div class="w-40-px h-40-px bg-info-focus rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                            <iconify-icon icon="ph:clock-bold" class="text-info-main"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-secondary-light text-sm mb-2">Horaires</p>
                            <p class="fw-semibold text-primary-light mb-0">
                                {{ $pharmacy->opening_hours ?? '—' }} — {{ $pharmacy->closing_hours ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="d-flex align-items-start gap-12">
                        <div class="w-40-px h-40-px bg-cyan-focus rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                            <iconify-icon icon="ph:map-pin-bold" class="text-cyan"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-secondary-light text-sm mb-2">Coordonnées GPS</p>
                            <a href="{{ $pharmacy->gps_coordinates ?? '#' }}" target="_blank" class="fw-semibold text-primary-light mb-0">
                                {{ $pharmacy->gps_coordinates ?? '—' }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <hr class="my-24">

            {{-- Assurances --}}
            @if($assurances->count())
            <div class="mb-24">
                <h6 class="fw-semibold text-primary-light mb-16">Assurances acceptées</h6>
                <div class="d-flex flex-wrap gap-12">
                    @foreach($assurances as $ass)
                    <div class="d-flex align-items-center gap-8 border radius-8 px-12 py-8">
                        @if($ass->assurance_picture)
                            <img src="{{ $ass->assurance_picture }}" height="28" width="28" class="rounded" alt="{{ $ass->name }}">
                        @else
                            <iconify-icon icon="ph:shield-check-bold" class="text-primary-600 text-xl"></iconify-icon>
                        @endif
                        <span class="fw-medium text-sm">{{ $ass->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Moyens de paiement --}}
            @if($paymentMethods->count())
            <div>
                <h6 class="fw-semibold text-primary-light mb-16">Moyens de paiement</h6>
                <div class="d-flex flex-wrap gap-12">
                    @foreach($paymentMethods as $pm)
                    <div class="d-flex align-items-center gap-8 border radius-8 px-12 py-8">
                        @if($pm->payment_method_picture)
                            <img src="{{ $pm->payment_method_picture }}" height="28" width="28" class="rounded" alt="{{ $pm->name }}">
                        @else
                            <iconify-icon icon="ph:credit-card-bold" class="text-primary-600 text-xl"></iconify-icon>
                        @endif
                        <span class="fw-medium text-sm">{{ $pm->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- MODAL MODIFICATION                                           --}}
{{-- ============================================================ --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content radius-16 bg-base">

            <div class="modal-header py-16 px-24 border-bottom">
                <h5 class="modal-title fw-semibold">Modifier les informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-24">
                <form action="{{ url('ma-pharmacie/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="row gy-16">

                        {{-- Photo de façade --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Photo de façade
                            </label>
                            <input type="file" name="facade_image" class="form-control radius-8"
                                accept=".jpg,.jpeg,.png">
                            @if($pharmacy->facade_image)
                                    <small class="text-secondary-light ms-8">Photo actuelle</small>
                                <div class="mt-8">
                                    <img src="{{ $pharmacy->facade_image }}" height="60" class="rounded" alt="Façade actuelle">
                                </div>
                            @endif
                        </div>

                        {{-- Nom --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Nom de la pharmacie <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="name" class="form-control radius-8"
                                value="{{ $pharmacy->name }}">
                        </div>

                        {{-- Propriétaire --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Nom du propriétaire
                            </label>
                            <input type="text" name="owner_name" class="form-control radius-8"
                                value="{{ $pharmacy->owner_name }}">
                        </div>

                        {{-- Adresse --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Adresse</label>
                            <input type="text" name="address" class="form-control radius-8"
                                value="{{ $pharmacy->address }}">
                        </div>

                        {{-- Commune --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Commune <span class="text-danger">*</span>
                            </label>
                            <select required name="commune_id" class="form-select radius-8">
                                @foreach($communes as $commune)
                                    <option value="{{ $commune->id_commune }}"
                                        {{ $pharmacy->commune_id == $commune->id_commune ? 'selected' : '' }}>
                                        {{ $commune->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Téléphone --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Téléphone</label>
                            <input type="text" name="phone_number" class="form-control radius-8"
                                value="{{ $pharmacy->phone_number }}">
                        </div>

                        {{-- WhatsApp --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">WhatsApp</label>
                            <input type="text" name="whats_app_phone_number" class="form-control radius-8"
                                value="{{ $pharmacy->whats_app_phone_number }}">
                        </div>

                        {{-- Heure ouverture --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Heure d'ouverture</label>
                            <input type="text" name="opening_hours" class="form-control radius-8"
                                placeholder="ex: 08h00" value="{{ $pharmacy->opening_hours }}">
                        </div>

                        {{-- Heure fermeture --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Heure de fermeture</label>
                            <input type="text" name="closing_hours" class="form-control radius-8"
                                placeholder="ex: 20h00" value="{{ $pharmacy->closing_hours }}">
                        </div>

                        {{-- GPS --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Coordonnées GPS
                                {{-- <small class="text-secondary-light fw-normal">(latitude,longitude)</small> --}}
                            </label>
                            <input type="text" name="gps_coordinates" class="form-control radius-8"
                                placeholder="ex: 5.3364,-4.0267" value="{{ $pharmacy->gps_coordinates }}">
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            Annuler
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-40 py-12 radius-8">
                            Enregistrer
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
