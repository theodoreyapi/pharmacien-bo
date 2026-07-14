@extends('layouts.master', ['title' => 'Campagnes santé'])

@section('content')
    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
        }

        /* ── KPI Cards ── */
        .kpi-row {
            display: flex;
            gap: 14px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .kpi-card {
            background: white;
            border-radius: 18px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-width: 160px;
            border: none;
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
            font-size: 1.7rem;
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

        /* ── Section header ── */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-header h5 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 12px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-new:hover {
            background: #15803d;
        }

        /* ── Create form panel ── */
        .create-panel {
            background: white;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 14px;
            display: none;
            border: 1.5px solid #d1fae5;
        }

        .create-panel.show {
            display: block;
        }

        .create-panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .create-panel-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #ecfdf5;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .field-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

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

        .f-input::placeholder {
            color: #cbd5e1;
        }

        .f-select {
            appearance: none;
        }

        textarea.f-input {
            resize: none;
        }

        /* Portée toggle */
        .portee-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .portee-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 11px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .portee-btn:hover {
            border-color: #94a3b8;
        }

        .portee-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        /* Estimation patients */
        .estimation-bar {
            background: #f0f4ff;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #334155;
        }

        .estimation-bar strong {
            font-weight: 700;
            color: #0f172a;
        }

        .estimation-bar small {
            font-size: 12px;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }

        /* Panel footer */
        .panel-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-annuler {
            padding: 10px 20px;
            border-radius: 11px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-annuler:hover {
            background: #f8fafc;
        }

        .btn-apercu {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 11px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-apercu:hover {
            background: #f8fafc;
        }

        .btn-planifier {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 11px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-planifier:hover {
            background: #15803d;
        }

        /* ── Campaign cards ── */
        .campaign-card {
            background: white;
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #f1f5f9;
            transition: box-shadow .15s;
        }

        .campaign-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
        }

        .camp-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #ecfdf5;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .camp-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .camp-desc {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .camp-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12px;
            color: #94a3b8;
        }

        .camp-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Tags */
        .tag {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .tag-hta {
            background: #fce4ec;
            color: #c2185b;
        }

        .tag-diab {
            background: #fff3e0;
            color: #e65100;
        }

        .tag-asth {
            background: #e0f2f1;
            color: #00695c;
        }

        .tag-dyslip {
            background: #e8eaf6;
            color: #3949ab;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .s-planifie {
            background: #eff6ff;
            color: #2563eb;
        }

        .s-encours {
            background: #ecfdf5;
            color: #16a34a;
        }

        .s-termine {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #d1fae5;
        }

        .s-brouillon {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        /* Action buttons */
        .camp-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-left: auto;
            flex-shrink: 0;
        }

        .btn-voir {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-voir:hover {
            background: #f8fafc;
        }

        .btn-arreter {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1.5px solid #fecaca;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #dc2626;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-arreter:hover {
            background: #fef2f2;
        }

        .btn-planifier-sm {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            border-radius: 10px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-planifier-sm:hover {
            background: #15803d;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .kpi-row {
                flex-direction: column;
            }

            .camp-actions {
                flex-wrap: wrap;
            }

            .portee-group {
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="dash-body">

        {{-- Affichage des messages de succès --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ══ KPI ══ --}}
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:#ecfdf5; color:#16a34a;">
                    <iconify-icon icon="ph:play-circle-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-value">{{ $enCours }}</div>
                    <div class="kpi-label">En cours</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:#eff6ff; color:#2563eb;">
                    <iconify-icon icon="ph:clock-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-value">{{ $planifiees }}</div>
                    <div class="kpi-label">Planifiées</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:#faf5ff; color:#9333ea;">
                    <iconify-icon icon="ph:users-three-bold"></iconify-icon>
                </div>
                <div>
                    <div class="kpi-value">{{ $patientsCibles }}</div>
                    <div class="kpi-label">Patients ciblés</div>
                </div>
            </div>
        </div>

        {{-- ══ Section header ══ --}}
        <div class="section-header">
            <h5>Campagnes santé</h5>
            <button class="btn-new" onclick="toggleCreatePanel()">
                <iconify-icon icon="ph:plus-bold"></iconify-icon> Nouvelle campagne
            </button>
        </div>

        {{-- ══ Create form panel ══ --}}
        <form method="POST" action="{{ route('campagnes.store') }}">
            @csrf
            <div class="create-panel" id="create-panel">
                <div class="create-panel-title">
                    <div class="create-panel-icon">
                        <iconify-icon icon="ph:megaphone-bold"></iconify-icon>
                    </div>
                    Créer une campagne
                </div>

                <div class="mb-3">
                    <div class="field-lbl">Nom de la campagne</div>
                    <input name="name" type="text" class="f-input" placeholder="Ex: Journée Mondiale Diabète 2026">
                </div>

                <div class="mb-3">
                    <div class="field-lbl">Description</div>
                    <textarea name="description" class="f-input" rows="3" placeholder="Objectif et contenu de la campagne..."></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-5">
                        <div class="field-lbl">Pathologie cible</div>
                        <select name="pathologie_id" class="f-input f-select" onchange="updateEstimation(this)">
                            @foreach ($pathologies as $pathologie)
                                <option value="{{ $pathologie->id_pathologie }}">
                                    {{ $pathologie->code }}
                                    -
                                    {{ $pathologie->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="field-lbl">Portée</div>
                        {{-- Input caché requis pour envoyer la portée sélectionnée via le formulaire --}}
                        <input type="hidden" name="portee" id="portee_input" value="MA_PHARMACIE">

                        <div class="portee-group">
                            <button type="button" class="portee-btn active"
                                onclick="setPortee(this, 'MA_PHARMACIE', 'Ma pharmacie', 45)">
                                <iconify-icon icon="ph:building-bold"></iconify-icon> Ma pharmacie
                            </button>
                            {{-- <button type="button" class="portee-btn"
                                onclick="setPortee(this, 'REGIONAL', 'Régional', 125)">
                                <iconify-icon icon="ph:map-pin-bold"></iconify-icon> Régional
                            </button>
                            <button type="button" class="portee-btn"
                                onclick="setPortee(this, 'NATIONAL', 'National', 312)">
                                <iconify-icon icon="ph:globe-bold"></iconify-icon> National
                            </button> --}}
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Modèle de message</div>
                        <select name="message_template" class="f-input f-select">
                            <option value="Renouvellement HTA">Renouvellement HTA</option>
                            <option value="Rappel glycémie">Rappel glycémie</option>
                            <option value="Journée mondiale">Journée mondiale</option>
                            <option value="Conseil santé général">Conseil santé général</option>
                            <option value="Message personnalisé">Message personnalisé</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Date / heure d'envoi</div>
                        <input name="scheduled_at" type="datetime-local" class="f-input">
                    </div>
                </div>

                <div class="estimation-bar">
                    <iconify-icon icon="ph:users-three-bold"
                        style="font-size:1.3rem;color:#6366f1;flex-shrink:0;"></iconify-icon>
                    <div>
                        <strong id="estimation-count">~45 patients seront touchés</strong>
                        <small id="estimation-sub">Basé sur la portée « Ma pharmacie » · HTA</small>
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="button" class="btn-annuler" onclick="toggleCreatePanel()">Annuler</button>
                    {{-- <button class="btn-apercu">
                        <iconify-icon icon="ph:eye-bold"></iconify-icon> Aperçu
                    </button> --}}
                    <button class="btn bt-primary btn-planifier" type="submit">
                        <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon> Enregistrer
                    </button>
                </div>
            </div>
        </form>
        {{-- ══ Campaign list ══ --}}

        @foreach ($campagnes as $campagne)
            <div class="campaign-card">

                <div class="camp-icon">
                    <iconify-icon icon="ph:megaphone-bold"></iconify-icon>
                </div>

                <div class="flex-grow-1 min-w-0">

                    <div class="camp-name">

                        {{ $campagne->name }}

                        @if ($campagne->pathologie_code)
                            <span class="tag">

                                {{ $campagne->pathologie_code }}

                            </span>
                        @endif

                        @switch($campagne->status)
                            @case('BROUILLON')
                                <span class="status-badge s-brouillon">
                                    Brouillon
                                </span>
                            @break

                            @case('PLANIFIE')
                                <span class="status-badge s-planifie">
                                    Planifiée
                                </span>
                            @break

                            @case('ENVOYE')
                                <span class="status-badge s-encours">
                                    Envoyée
                                </span>
                            @break

                            @case('TERMINE')
                                <span class="status-badge s-termine">
                                    Terminée
                                </span>
                            @break
                        @endswitch

                    </div>

                    <div class="camp-desc">

                        {{ $campagne->description }}

                    </div>

                    <div class="camp-meta">

                        <span>

                            <iconify-icon icon="ph:globe-bold">
                            </iconify-icon>

                            {{ str_replace('_', ' ', $campagne->portee) }}

                        </span>

                        <span>

                            <iconify-icon icon="ph:calendar-bold">
                            </iconify-icon>

                            {{ $campagne->scheduled_at ? \Carbon\Carbon::parse($campagne->scheduled_at)->format('d/m/Y H:i') : '-' }}

                        </span>

                        <span>

                            <iconify-icon icon="ph:users-bold">
                            </iconify-icon>

                            {{ $campagne->patients_count }}
                            patients

                        </span>

                        <span>

                            <iconify-icon icon="ph:chat-circle-bold">
                            </iconify-icon>

                            {{ $campagne->channel }}

                        </span>

                    </div>

                </div>

                {{-- <div class="camp-actions">
                    <a href="{{ route('campagnes.show', $campagne->id_campagne) }}" class="btn-voir">
                        Voir
                    </a>
                    @if ($campagne->status == 'BROUILLON')
                        <a href="{{ route('campagnes.edit', $campagne->id_campagne) }}" class="btn-planifier-sm">
                            Modifier
                        </a>
                    @endif
                </div> --}}
            </div>
        @endforeach

    </div>

    <script>
        let panelOpen = false;
        let currentPortee = 'Ma pharmacie';
        let currentPathoCount = 45;

        function toggleCreatePanel() {
            panelOpen = !panelOpen;
            document.getElementById('create-panel').classList.toggle('show', panelOpen);
            if (panelOpen) {
                document.getElementById('create-panel').scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }

        function setPortee(btn, label, count) {
            document.querySelectorAll('.portee-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPortee = label;
            updateEstimationDisplay();
        }

        function updateEstimation(select) {
            const pathLabel = select.options[select.selectedIndex].text.split('(')[0].trim();
            currentPathoCount = parseInt(select.value);
            updateEstimationDisplay();
        }

        function updateEstimationDisplay() {
            const patho = document.querySelector('.f-select').options[document.querySelector('.f-select').selectedIndex]
                ?.text?.split('(')[0]?.trim() || 'HTA';
            const total = currentPortee === 'National' ? 312 : currentPortee === 'Régional' ? 125 : currentPathoCount;
            document.getElementById('estimation-count').textContent = '~' + total + ' patients seront touchés';
            document.getElementById('estimation-sub').textContent = 'Basé sur la portée « ' + currentPortee + ' » · ' +
                patho;
        }
    </script>
@endsection
