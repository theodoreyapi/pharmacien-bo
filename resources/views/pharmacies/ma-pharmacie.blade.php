@extends('layouts.master', ['title' => 'Ma Pharmacie'])

@section('content')
    <style>
        .dash-body {
            margin-left: 100px;
            margin-right: 100px;
            padding: 28px;
            min-height: 100%;
        }

        /* ── Section card ── */
        .p-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 16px;
            border: none;
        }

        /* ── Section titles ── */
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .section-title iconify-icon {
            font-size: 1.1rem;
        }

        /* ── Modifier button ── */
        .btn-modifier {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 11px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            text-decoration: none;
        }

        .btn-modifier:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
        }

        /* ── Pharmacy identity block ── */
        .pharma-identity {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .pharma-logo {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #16a34a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .pharma-name {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .pharma-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-plan {
            display: inline-flex;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #ecfdf5;
            color: #16a34a;
        }

        .pharma-status {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #16a34a;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 24px;
        }

        .info-item-label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .info-item-label iconify-icon {
            font-size: .9rem;
        }

        .info-item-value {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }

        /* ── Abonnement plan card ── */
        .plan-card {
            background: linear-gradient(135deg, #16a34a 0%, #059669 100%);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            color: white;
        }

        .plan-label {
            font-size: 11px;
            font-weight: 600;
            opacity: .75;
            margin-bottom: 4px;
        }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .plan-renew {
            font-size: 12px;
            opacity: .7;
        }

        .plan-price-label {
            font-size: 11px;
            opacity: .7;
            text-align: right;
            margin-bottom: 4px;
        }

        .plan-price {
            font-size: 1.3rem;
            font-weight: 800;
            text-align: right;
        }

        /* ── Usage stats ── */
        .usage-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .usage-item {}

        .usage-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .usage-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .usage-check {
            color: #16a34a;
            font-size: 1rem;
        }

        .usage-values {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .usage-values span {
            font-weight: 400;
            color: #94a3b8;
        }

        .usage-bar {
            height: 5px;
            border-radius: 10px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .usage-fill {
            height: 100%;
            border-radius: 10px;
            background: #16a34a;
        }

        /* ── Gérer button ── */
        .btn-gerer {
            width: 100%;
            padding: 13px;
            border-radius: 13px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-gerer:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* ── Réseau affilié ── */
        .reseau-desc {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .partner-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
        }

        .partner-row:last-of-type {
            border-bottom: none;
        }

        .partner-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #16a34a;
            flex-shrink: 0;
        }

        .partner-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .partner-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .badge-partenaire {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            flex-shrink: 0;
        }

        /* ── Modal inputs ── */
        .f-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            font-size: 13px;
            color: #334155;
            background: #f8fafc;
            outline: none;
            transition: border-color .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .f-input:focus {
            border-color: #16a34a;
            background: white;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        .field-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .info-grid,
            .usage-grid {
                grid-template-columns: 1fr;
            }

            .plan-card {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>

    <div class="dash-body">

        @include('layouts.statuts')

        {{-- ══ Profil de la pharmacie ══ --}}
        <div class="p-card">
            <div class="section-head">
                <div class="section-title">
                    <iconify-icon icon="ph:buildings-bold" style="color:#16a34a;"></iconify-icon>
                    Profil de la pharmacie
                </div>
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editModal" class="btn-modifier">
                    <iconify-icon icon="ph:pencil-bold"></iconify-icon> Modifier
                </a>
            </div>

            {{-- Identity --}}
            <div class="pharma-identity">
                <div class="pharma-logo">
                    <iconify-icon icon="ph:heart-bold"></iconify-icon>
                </div>
                <div>
                    <div class="pharma-name">{{ $pharmacy->name ?? 'Pharmacie du Centre Plateau' }}</div>
                    <div class="pharma-badges">
                        <span class="badge-plan">Plan Pro</span>
                        <span class="pharma-status">
                            <span class="status-dot"></span>
                            Actif · Code: PCP-001
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info grid --}}
            <div class="info-grid">
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:phone-bold"></iconify-icon> Téléphone
                    </div>
                    <div class="info-item-value">{{ $pharmacy->phone_number ?? '+225 27 22 44 56 78' }}</div>
                </div>
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:envelope-bold"></iconify-icon> Email
                    </div>
                    <div class="info-item-value">{{ $pharmacy->email ?? 'contact@pharmacie-plateau.ci' }}</div>
                </div>
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:map-pin-bold"></iconify-icon> Adresse
                    </div>
                    <div class="info-item-value">
                        {{ $pharmacy->address ?? '12 Avenue Terrasson de Fougères, Plateau, Abidjan' }}</div>
                </div>
                <div>
                    <div class="info-item-label">
                        <iconify-icon icon="ph:globe-bold"></iconify-icon> Ville
                    </div>
                    <div class="info-item-value">{{ $pharmacy->commune_name ?? 'Abidjan' }}</div>
                </div>
            </div>
        </div>

        {{-- ══ Abonnement ══ --}}
        <div class="p-card">
            <div class="section-head mb-3">
                <div class="section-title">
                    <iconify-icon icon="ph:credit-card-bold" style="color:#9333ea;"></iconify-icon>
                    Abonnement
                </div>
            </div>

            {{-- Plan card --}}
            <div class="plan-card">
                <div>
                    <div class="plan-label">Plan actuel</div>
                    <div class="plan-name">Plan Pro</div>
                    <div class="plan-renew">Renouvellement: 15 janv. 2027</div>
                </div>
                <div>
                    <div class="plan-price-label">Mensuel</div>
                    <div class="plan-price">45 000 XOF</div>
                </div>
            </div>

            {{-- Usage stats --}}
            <div class="usage-grid">
                <div class="usage-item">
                    <div class="usage-head">
                        <span class="usage-label">Patients max</span>
                        <iconify-icon icon="ph:check-circle-bold" class="usage-check"></iconify-icon>
                    </div>
                    <div class="usage-values">148 <span>/ 500</span></div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width:{{ (148 / 500) * 100 }}%;"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-head">
                        <span class="usage-label">Messages/mois</span>
                        <iconify-icon icon="ph:check-circle-bold" class="usage-check"></iconify-icon>
                    </div>
                    <div class="usage-values">214 <span>/ 2000</span></div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width:{{ (214 / 2000) * 100 }}%;"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-head">
                        <span class="usage-label">Campagnes actives</span>
                        <iconify-icon icon="ph:check-circle-bold" class="usage-check"></iconify-icon>
                    </div>
                    <div class="usage-values">1 <span>/ 5</span></div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width:{{ (1 / 5) * 100 }}%;"></div>
                    </div>
                </div>
                <div class="usage-item">
                    <div class="usage-head">
                        <span class="usage-label">Membres équipe</span>
                        <iconify-icon icon="ph:check-circle-bold" class="usage-check"></iconify-icon>
                    </div>
                    <div class="usage-values">4 <span>/ 10</span></div>
                    <div class="usage-bar">
                        <div class="usage-fill" style="width:{{ (4 / 10) * 100 }}%;"></div>
                    </div>
                </div>
            </div>

            <button class="btn-gerer">
                <iconify-icon icon="ph:credit-card-bold"></iconify-icon> Gérer l'abonnement
            </button>
        </div>

        {{-- ══ Réseau affilié ══ --}}
        <div class="p-card">
            <div class="section-head mb-2">
                <div class="section-title">
                    <iconify-icon icon="ph:users-three-bold" style="color:#2563eb;"></iconify-icon>
                    Réseau affilié
                </div>
            </div>

            <p class="reseau-desc">Pharmacies partenaires pouvant accéder aux dossiers avec consentement réseau actif.</p>

            <div class="partner-row">
                <div class="d-flex align-items-center gap-3">
                    <div class="partner-dot"></div>
                    <div>
                        <div class="partner-name">Pharmacie Koumassi Centre</div>
                        <div class="partner-sub">Koumassi, Abidjan · 8 patients partagés</div>
                    </div>
                </div>
                <span class="badge-partenaire">Partenaire</span>
            </div>

            <div class="partner-row">
                <div class="d-flex align-items-center gap-3">
                    <div class="partner-dot"></div>
                    <div>
                        <div class="partner-name">Pharmacie Les 2 Plateaux</div>
                        <div class="partner-sub">Cocody, Abidjan · 3 patients partagés</div>
                    </div>
                </div>
                <span class="badge-partenaire">Partenaire</span>
            </div>

            <button class="btn-gerer mt-3">
                <iconify-icon icon="ph:chat-circle-dots-bold"></iconify-icon> Gérer les affiliations
            </button>
        </div>

    </div>

    {{-- ══ Modal modification ══ --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:20px;border:none;">
                <div class="modal-header" style="background:#16a34a;border-radius:20px 20px 0 0;padding:18px 26px;">
                    <h5 class="modal-title text-white fw-bold"
                        style="font-size:15px;display:flex;align-items:center;gap:8px;">
                        <iconify-icon icon="ph:pencil-bold"></iconify-icon> Modifier les informations
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ url('ma-pharmacie/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('POST')
                        <div class="row g-3">

                            <div class="col-12">
                                <div class="field-lbl">Photo de façade</div>
                                <input type="file" name="facade_image" class="f-input" accept=".jpg,.jpeg,.png">
                                @if ($pharmacy->facade_image ?? false)
                                    <div class="mt-2">
                                        <img src="{{ $pharmacy->facade_image }}" height="60" class="rounded"
                                            alt="Façade">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="field-lbl">Nom de la pharmacie <span style="color:#dc2626;">*</span></div>
                                <input required type="text" name="name" class="f-input"
                                    value="{{ $pharmacy->name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Nom du propriétaire</div>
                                <input type="text" name="owner_name" class="f-input"
                                    value="{{ $pharmacy->owner_name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Adresse</div>
                                <input type="text" name="address" class="f-input"
                                    value="{{ $pharmacy->address ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Commune <span style="color:#dc2626;">*</span></div>
                                <select required name="commune_id" class="f-input" style="appearance:none;">
                                    @foreach ($communes as $commune)
                                        <option value="{{ $commune->id_commune }}"
                                            {{ ($pharmacy->commune_id ?? '') == $commune->id_commune ? 'selected' : '' }}>
                                            {{ $commune->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Téléphone</div>
                                <input type="text" name="phone_number" class="f-input"
                                    value="{{ $pharmacy->phone_number ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">WhatsApp</div>
                                <input type="text" name="whats_app_phone_number" class="f-input"
                                    value="{{ $pharmacy->whats_app_phone_number ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Heure d'ouverture</div>
                                <input type="text" name="opening_hours" class="f-input" placeholder="ex: 08h00"
                                    value="{{ $pharmacy->opening_hours ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="field-lbl">Heure de fermeture</div>
                                <input type="text" name="closing_hours" class="f-input" placeholder="ex: 20h00"
                                    value="{{ $pharmacy->closing_hours ?? '' }}">
                            </div>
                            <div class="col-12">
                                <div class="field-lbl">Coordonnées GPS</div>
                                <input type="text" name="gps_coordinates" class="f-input"
                                    placeholder="ex: 5.3364,-4.0267" value="{{ $pharmacy->gps_coordinates ?? '' }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" data-bs-dismiss="modal"
                                style="padding:10px 22px;border-radius:11px;border:1.5px solid #e2e8f0;background:white;font-size:13px;font-weight:600;color:#475569;cursor:pointer;">
                                Annuler
                            </button>
                            <button type="submit"
                                style="padding:10px 24px;border-radius:11px;border:none;background:#16a34a;color:white;font-size:13px;font-weight:600;cursor:pointer;">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
