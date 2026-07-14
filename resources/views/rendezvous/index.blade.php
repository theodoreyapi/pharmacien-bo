@extends('layouts.master', ['title' => 'Rendez-vous mesures'])

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        .dash-body {
            padding: 24px 28px;
            min-height: 100%;
        }

        .rdv-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .rdv-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
        }

        .rdv-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .btn-nouveau-rdv {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 12px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-nouveau-rdv:hover {
            background: #15803d;
        }

        /* ── KPI ── */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .kpi-card {
            background: white;
            border-radius: 18px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .kpi-icon.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .kpi-icon.green {
            background: #ecfdf5;
            color: #16a34a;
        }

        .kpi-icon.red {
            background: #fef2f2;
            color: #dc2626;
        }

        .kpi-number {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .kpi-label {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ── Alerte ── */
        .rdv-alert {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 16px;
        }

        .rdv-alert-head {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .rdv-alert-icon {
            color: #dc2626;
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .rdv-alert-title {
            font-size: 13px;
            font-weight: 700;
            color: #dc2626;
        }

        .rdv-alert-sub {
            font-size: 12px;
            color: #b91c1c;
            margin-top: 2px;
        }

        .rdv-alert-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-left: 26px;
        }

        .rdv-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            background: white;
            border: 1px solid #fecaca;
            font-size: 12px;
            font-weight: 600;
            color: #7f1d1d;
        }

        /* ── Layout 2 colonnes ── */
        .rdv-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 16px;
            align-items: start;
        }

        @media (max-width: 991.98px) {
            .rdv-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Tabs ── */
        .tabs-bar {
            background: white;
            border-radius: 16px;
            padding: 6px;
            display: flex;
            gap: 2px;
            margin-bottom: 16px;
            overflow-x: auto;
        }

        .tab-btn {
            padding: 10px 16px;
            border-radius: 12px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }

        .tab-btn:hover {
            background: #f8fafc;
            color: #334155;
        }

        .tab-btn.active {
            background: #16a34a;
            color: white;
        }

        .tab-count {
            background: rgba(0, 0, 0, .08);
            color: inherit;
            font-size: 11px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 10px;
        }

        .tab-btn.active .tab-count {
            background: rgba(255, 255, 255, .25);
        }

        .tab-count.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .tab-btn.active .tab-count.danger {
            background: rgba(255, 255, 255, .3);
            color: white;
        }

        .content-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 14px;
        }

        /* ── RDV item row ── */
        .rdv-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .rdv-item:last-child {
            border-bottom: none;
        }

        .rdv-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fce4ec;
            color: #c2185b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .rdv-item-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rdv-item-time {
            font-size: 12px;
            color: #94a3b8;
            margin: 3px 0 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rdv-item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .rdv-item-note {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .rdv-item-actions {
            display: flex;
            gap: 8px;
            margin-left: auto;
            flex-shrink: 0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-prevu {
            background: #eff6ff;
            color: #2563eb;
        }

        .status-today {
            background: #ecfdf5;
            color: #16a34a;
        }

        .status-manque {
            background: #fef2f2;
            color: #dc2626;
        }

        .mesure-tag-sm {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-effectue {
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-effectue:hover {
            background: #15803d;
        }

        .btn-absent {
            padding: 8px 16px;
            border-radius: 10px;
            border: 1.5px solid #fecaca;
            background: white;
            color: #dc2626;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-absent:hover {
            background: #fef2f2;
        }

        .btn-fiche {
            padding: 8px 16px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: white;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all .15s;
        }

        .btn-fiche:hover {
            background: #f8fafc;
        }

        .rdv-notif {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 5px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
            font-size: 13px;
        }

        /* ── Sidebar : Calendrier ── */
        .sidebar-card {
            background: white;
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 14px;
        }

        .cal-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .cal-nav-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .cal-nav-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: none;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background .15s;
        }

        .cal-nav-btn:hover {
            background: #f1f5f9;
        }

        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            text-align: center;
        }

        .cal-dow {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            padding: 4px 0;
        }

        .cal-day {
            position: relative;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            padding: 7px 0;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            transition: background .15s;
        }

        .cal-day:hover {
            background: #f8fafc;
        }

        .cal-day.out-month {
            color: #cbd5e1;
        }

        .cal-day.today {
            background: #16a34a;
            color: white;
            font-weight: 800;
        }

        .cal-day.selected:not(.today) {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
        }

        .cal-dots {
            display: flex;
            justify-content: center;
            gap: 2px;
            margin-top: 2px;
        }

        .cal-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
        }

        .cal-dot.green {
            background: #16a34a;
        }

        .cal-dot.red {
            background: #dc2626;
        }

        .cal-day.today .cal-dot.green {
            background: white;
        }

        .cal-day.today .cal-dot.red {
            background: #fecaca;
        }

        /* ── Sidebar : Plannings actifs ── */
        .sidebar-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .mini-planning-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            text-decoration: none;
        }

        .mini-planning-row:last-child {
            border-bottom: none;
        }

        .mini-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .mini-planning-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .mini-planning-meta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px;
        }

        .mini-planning-arrow {
            margin-left: auto;
            color: #cbd5e1;
            font-size: 1rem;
        }

        /* ── Modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            background: white;
            border-radius: 20px;
            width: 460px;
            max-width: 95vw;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
        }

        .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .modal-sub {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #f8fafc;
            cursor: pointer;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .modal-body {
            padding: 20px 24px;
        }

        .field-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 5px;
        }

        .f-input {
            width: 100%;
            padding: 10px 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            color: #334155;
            background: white;
            outline: none;
            transition: border-color .15s;
            font-family: 'DM Sans', sans-serif;
            margin-bottom: 16px;
        }

        .f-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        .f-select {
            appearance: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .mesure-toggle-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .mesure-toggle-btn {
            padding: 9px 16px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .mesure-toggle-btn.active {
            border-color: #16a34a;
            background: #16a34a;
            color: white;
        }

        .panel-btns {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 6px;
        }

        .btn-annuler {
            padding: 9px 18px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-enregistrer {
            padding: 9px 20px;
            border-radius: 10px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-enregistrer:hover {
            background: #15803d;
        }

        .btn-enregistrer:disabled {
            background: #86efac;
            cursor: not-allowed;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .kpi-row {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="dash-body">

        @include('layouts.statuts')

        @if (session('success'))
            <div style="position:fixed;top:20px;right:20px;z-index:9999;background:#16a34a;color:white;padding:14px 20px;border-radius:14px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 8px 24px rgba(22,163,74,.3);max-width:380px;"
                id="flash-success">
                <iconify-icon icon="ph:check-circle-bold" style="font-size:1.3rem;"></iconify-icon>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()"
                    style="background:none;border:none;color:white;cursor:pointer;margin-left:auto;"><iconify-icon
                        icon="ph:x-bold"></iconify-icon></button>
            </div>
        @endif
        @if (session('error'))
            <div style="position:fixed;top:20px;right:20px;z-index:9999;background:#dc2626;color:white;padding:14px 20px;border-radius:14px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 8px 24px rgba(220,38,38,.3);max-width:380px;"
                id="flash-error">
                <iconify-icon icon="ph:warning-bold" style="font-size:1.3rem;"></iconify-icon>
                {{ session('error') }}
                <button onclick="this.parentElement.remove()"
                    style="background:none;border:none;color:white;cursor:pointer;margin-left:auto;"><iconify-icon
                        icon="ph:x-bold"></iconify-icon></button>
            </div>
        @endif
        <script>
            setTimeout(() => {
                ['flash-success', 'flash-error'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.style.transition = 'opacity .4s';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 400);
                    }
                });
            }, 4000);
        </script>

        {{-- ══ Header ══ --}}
        <div class="rdv-header">
            <div>
                <div class="rdv-title">Rendez-vous mesures</div>
                <div class="rdv-subtitle">Gérez les RDV de mesure des constantes</div>
            </div>
            <button class="btn-nouveau-rdv" onclick="openRdvModal()">
                <iconify-icon icon="ph:plus-bold"></iconify-icon> Nouveau RDV
            </button>
        </div>

        {{-- ══ KPI ══ --}}
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-icon blue"><iconify-icon icon="ph:calendar-blank-bold"></iconify-icon></div>
                <div>
                    <div class="kpi-number">{{ $aujourdHui }}</div>
                    <div class="kpi-label">Aujourd'hui</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon green"><iconify-icon icon="ph:check-circle-bold"></iconify-icon></div>
                <div>
                    <div class="kpi-number">{{ $programmes }}</div>
                    <div class="kpi-label">Programmés</div>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon red"><iconify-icon icon="ph:x-circle-bold"></iconify-icon></div>
                <div>
                    <div class="kpi-number">{{ $manques }}</div>
                    <div class="kpi-label">Manqués</div>
                </div>
            </div>
        </div>

        {{-- ══ Alerte ══ --}}
        @if ($patientsAbsents->count() > 0)
            <div class="rdv-alert">
                <div class="rdv-alert-head">
                    <iconify-icon icon="ph:warning-circle-bold" class="rdv-alert-icon"></iconify-icon>
                    <div>
                        <div class="rdv-alert-title">{{ $patientsAbsents->count() }}
                            patient{{ $patientsAbsents->count() > 1 ? 's' : '' }}
                            absent{{ $patientsAbsents->count() > 1 ? 's' : '' }} à leur RDV</div>
                        <div class="rdv-alert-sub">Ces patients n'ont pas effectué leur mesure prévue. Pensez à les
                            recontacter.</div>
                    </div>
                </div>
                <div class="rdv-alert-chips">
                    @foreach ($patientsAbsents as $rdv)
                        <a href="{{ route('patients.show', $rdv->patient_id) }}#tab-rdv" class="rdv-chip">
                            <iconify-icon icon="ph:user-bold"></iconify-icon>
                            {{ $rdv->patient->first_name }} {{ $rdv->patient->last_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rdv-layout">
            {{-- ══ COLONNE PRINCIPALE ══ --}}
            <div>
                {{-- Tabs --}}
                <div class="tabs-bar">
                    <a href="{{ route('rendezvous.index', ['tab' => 'aujourdhui']) }}"
                        class="tab-btn {{ $tabActif === 'aujourdhui' ? 'active' : '' }}">
                        Aujourd'hui <span class="tab-count">{{ $rdvAujourdHui->count() }}</span>
                    </a>
                    <a href="{{ route('rendezvous.index', ['tab' => 'avenir']) }}"
                        class="tab-btn {{ $tabActif === 'avenir' ? 'active' : '' }}">
                        À venir <span class="tab-count">{{ $rdvAVenir->count() }}</span>
                    </a>
                    <a href="{{ route('rendezvous.index', ['tab' => 'manques']) }}"
                        class="tab-btn {{ $tabActif === 'manques' ? 'active' : '' }}">
                        Manqués <span class="tab-count danger">{{ $rdvManques->count() }}</span>
                    </a>
                    <a href="{{ route('rendezvous.index', ['tab' => 'calendrier']) }}"
                        class="tab-btn {{ $tabActif === 'calendrier' ? 'active' : '' }}">
                        Calendrier
                    </a>
                </div>

                @php
                    $mesuresMap = [
                        'PRESSION_ARTERIELLE' => ['Tension', 'ph:heart-bold', '#fce4ec', '#c2185b'],
                        'FREQUENCE_CARDIAQUE' => ['Fréq. C.', 'ph:activity-bold', '#fdf4ff', '#9333ea'],
                        'GLYCEMIE' => ['Glycémie', 'ph:drop-bold', '#fff7ed', '#d97706'],
                        'POIDS_IMC' => ['Poids', 'ph:scales-bold', '#eff6ff', '#2563eb'],
                    ];

                    function renderRdvTags($rdv, $mesuresMap)
                    {
                        $html = '';
                        foreach ($rdv->mesures_types as $mt) {
                            [$lbl, $ic, $bg, $col] = $mesuresMap[$mt] ?? ['—', 'ph:pulse-bold', '#f1f5f9', '#64748b'];
                            $html .=
                                '<span class="mesure-tag-sm" style="background:' .
                                $bg .
                                ';color:' .
                                $col .
                                ';"><iconify-icon icon="' .
                                $ic .
                                '"></iconify-icon>' .
                                $lbl .
                                '</span>';
                        }
                        return $html;
                    }
                @endphp

                {{-- ══ Onglet Aujourd'hui ══ --}}
                <div class="content-card" style="{{ $tabActif === 'aujourdhui' ? '' : 'display:none;' }}"
                    id="panel-aujourdhui">
                    @forelse($rdvAujourdHui as $rdv)
                        <div class="rdv-item">
                            <div class="rdv-avatar">
                                {{ strtoupper(mb_substr($rdv->patient->first_name, 0, 1)) . strtoupper(mb_substr($rdv->patient->last_name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="rdv-item-name">
                                    {{ $rdv->patient->first_name }} {{ $rdv->patient->last_name }}
                                    <span class="status-pill status-prevu"><iconify-icon
                                            icon="ph:clock-bold"></iconify-icon> Prévu</span>
                                    <span class="status-pill status-today">Aujourd'hui</span>
                                </div>
                                <div class="rdv-item-time">
                                    <iconify-icon icon="ph:clock-bold"></iconify-icon>
                                    {{ \Carbon\Carbon::parse($rdv->date)->translatedFormat('l j F') }} à
                                    {{ \Carbon\Carbon::parse($rdv->heure)->format('H:i') }}
                                </div>
                                <div class="rdv-item-tags">{!! renderRdvTags($rdv, $mesuresMap) !!}</div>
                            </div>
                            <div class="rdv-item-actions">
                                <form action="{{ route('rendezvous.marquer', $rdv->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="EFFECTUE">
                                    <button type="submit" class="btn-effectue"><iconify-icon
                                            icon="ph:check-circle-bold"></iconify-icon> Effectué</button>
                                </form>
                                <form action="{{ route('rendezvous.marquer', $rdv->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="ABSENT">
                                    <button type="submit" class="btn-absent"><iconify-icon
                                            icon="ph:x-circle-bold"></iconify-icon> Absent</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Aucun RDV prévu aujourd'hui</div>
                    @endforelse
                </div>

                {{-- ══ Onglet À venir ══ --}}
                <div class="content-card" style="{{ $tabActif === 'avenir' ? '' : 'display:none;' }}" id="panel-avenir">
                    @forelse($rdvAVenir as $rdv)
                        <div class="rdv-item">
                            <div class="rdv-avatar">
                                {{ strtoupper(mb_substr($rdv->patient->first_name, 0, 1)) . strtoupper(mb_substr($rdv->patient->last_name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="rdv-item-name">
                                    {{ $rdv->patient->first_name }} {{ $rdv->patient->last_name }}
                                    <span class="status-pill status-prevu"><iconify-icon
                                            icon="ph:clock-bold"></iconify-icon> Prévu</span>
                                </div>
                                <div class="rdv-item-time">
                                    <iconify-icon icon="ph:clock-bold"></iconify-icon>
                                    {{ \Carbon\Carbon::parse($rdv->date)->translatedFormat('l j F') }} à
                                    {{ \Carbon\Carbon::parse($rdv->heure)->format('H:i') }}
                                </div>
                                <div class="rdv-item-tags">{!! renderRdvTags($rdv, $mesuresMap) !!}</div>
                            </div>
                            <div class="rdv-item-actions">
                                <a href="{{ route('patients.show', $rdv->patient_id) }}#tab-rdv" class="btn-fiche">
                                    <iconify-icon icon="ph:arrow-right-bold"></iconify-icon> Fiche
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Aucun RDV à venir</div>
                    @endforelse
                </div>

                {{-- ══ Onglet Manqués ══ --}}
                <div class="content-card" style="{{ $tabActif === 'manques' ? '' : 'display:none;' }}"
                    id="panel-manques">
                    @forelse($rdvManques as $rdv)
                        <div class="rdv-item">
                            <div class="rdv-avatar">
                                {{ strtoupper(mb_substr($rdv->patient->first_name, 0, 1)) . strtoupper(mb_substr($rdv->patient->last_name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="rdv-item-name">
                                    {{ $rdv->patient->first_name }} {{ $rdv->patient->last_name }}
                                    <span class="status-pill status-manque"><iconify-icon
                                            icon="ph:x-circle-bold"></iconify-icon> Manqué</span>
                                </div>
                                <div class="rdv-item-time">
                                    <iconify-icon icon="ph:clock-bold"></iconify-icon>
                                    {{ \Carbon\Carbon::parse($rdv->date)->translatedFormat('l j F') }} à
                                    {{ \Carbon\Carbon::parse($rdv->heure)->format('H:i') }}
                                </div>
                                <div class="rdv-item-tags">{!! renderRdvTags($rdv, $mesuresMap) !!}</div>
                                <div class="rdv-notif">
                                    <iconify-icon icon="ph:bell-bold"></iconify-icon>
                                    Notification envoyée le
                                    {{ \Carbon\Carbon::parse($rdv->created_at)->translatedFormat('l j F \à H:i') }}
                                </div>
                            </div>
                            <div class="rdv-item-actions">
                                <a href="{{ route('patients.show', $rdv->patient_id) }}#tab-rdv" class="btn-fiche">
                                    <iconify-icon icon="ph:arrow-right-bold"></iconify-icon> Fiche
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Aucun RDV manqué</div>
                    @endforelse
                </div>

                {{-- ══ Onglet Calendrier ══ --}}
                <div class="content-card" style="{{ $tabActif === 'calendrier' ? '' : 'display:none;' }}"
                    id="panel-calendrier">
                    <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px;">
                        RDV du {{ $dateSelectionnee->translatedFormat('l j F Y') }}
                    </div>
                    @forelse($rdvDateSelectionnee as $rdv)
                        @php
                            $estPasse = \Carbon\Carbon::parse($rdv->date)->isPast() && !$rdv->date->isToday();
                            $pillClass =
                                $rdv->status === 'MANQUE'
                                    ? 'status-manque'
                                    : ($rdv->date->isToday()
                                        ? 'status-today'
                                        : 'status-prevu');
                            $pillLabel =
                                $rdv->status === 'MANQUE'
                                    ? 'Manqué'
                                    : ($rdv->status === 'EFFECTUE'
                                        ? 'Effectué'
                                        : 'Prévu');
                        @endphp
                        <div class="rdv-item">
                            <div class="rdv-avatar">
                                {{ strtoupper(mb_substr($rdv->patient->first_name, 0, 1)) . strtoupper(mb_substr($rdv->patient->last_name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="rdv-item-name">
                                    {{ $rdv->patient->first_name }} {{ $rdv->patient->last_name }}
                                    <span class="status-pill {{ $pillClass }}">{{ $pillLabel }}</span>
                                    @if ($rdv->date->isToday())
                                        <span class="status-pill status-today">Aujourd'hui</span>
                                    @endif
                                </div>
                                <div class="rdv-item-time">
                                    <iconify-icon icon="ph:clock-bold"></iconify-icon>
                                    {{ \Carbon\Carbon::parse($rdv->heure)->format('H:i') }}
                                </div>
                                <div class="rdv-item-tags">{!! renderRdvTags($rdv, $mesuresMap) !!}</div>
                            </div>
                            @if ($rdv->status === 'ATTENTE')
                                <div class="rdv-item-actions">
                                    <form action="{{ route('rendezvous.marquer', $rdv->id_rendez_vous) }}" method="POST">
                                        @csrf<input type="hidden" name="status" value="EFFECTUE">
                                        <button type="submit" class="btn-effectue"><iconify-icon
                                                icon="ph:check-circle-bold"></iconify-icon> Effectué</button>
                                    </form>
                                    <form action="{{ route('rendezvous.marquer', $rdv->id_rendez_vous) }}" method="POST">
                                        @csrf<input type="hidden" name="status" value="ABSENT">
                                        <button type="submit" class="btn-absent"><iconify-icon
                                                icon="ph:x-circle-bold"></iconify-icon> Absent</button>
                                    </form>
                                </div>
                            @else
                                <div class="rdv-item-actions">
                                    <a href="{{ route('patients.show', $rdv->patient_id) }}#tab-rdv" class="btn-fiche">
                                        <iconify-icon icon="ph:arrow-right-bold"></iconify-icon> Fiche
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">Aucun RDV ce jour-là</div>
                    @endforelse
                </div>
            </div>

            {{-- ══ SIDEBAR ══ --}}
            <div>
                {{-- Mini calendrier --}}
                <div class="sidebar-card">
                    @php
                        $moisPrec = $moisCourant->copy()->subMonth();
                        $moisSuiv = $moisCourant->copy()->addMonth();
                    @endphp
                    <div class="cal-nav">
                        <a href="{{ route('rendezvous.index', ['tab' => 'calendrier', 'mois' => $moisPrec->month, 'annee' => $moisPrec->year]) }}"
                            class="cal-nav-btn">
                            <iconify-icon icon="ph:caret-left-bold"></iconify-icon>
                        </a>
                        <div class="cal-nav-title">{{ ucfirst($moisCourant->translatedFormat('F Y')) }}</div>
                        <a href="{{ route('rendezvous.index', ['tab' => 'calendrier', 'mois' => $moisSuiv->month, 'annee' => $moisSuiv->year]) }}"
                            class="cal-nav-btn">
                            <iconify-icon icon="ph:caret-right-bold"></iconify-icon>
                        </a>
                    </div>
                    <div class="cal-grid">
                        @foreach (['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'] as $dow)
                            <div class="cal-dow">{{ $dow }}</div>
                        @endforeach

                        @foreach ($semainesCalendrier as $semaine)
                            @foreach ($semaine as $jour)
                                @php
                                    $estSelectionne = $jour['date']->isSameDay($dateSelectionnee);
                                    $classes = 'cal-day';
                                    if (!$jour['in_month']) {
                                        $classes .= ' out-month';
                                    }
                                    if ($jour['is_today']) {
                                        $classes .= ' today';
                                    } elseif ($estSelectionne) {
                                        $classes .= ' selected';
                                    }
                                @endphp
                                <a href="{{ route('rendezvous.index', ['tab' => 'calendrier', 'date' => $jour['date']->format('Y-m-d'), 'mois' => $moisCourant->month, 'annee' => $moisCourant->year]) }}"
                                    class="{{ $classes }}">
                                    {{ $jour['date']->day }}
                                    <div class="cal-dots">
                                        @if ($jour['has_attente'])
                                            <span class="cal-dot green"></span>
                                        @endif
                                        @if ($jour['has_manque'])
                                            <span class="cal-dot red"></span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                {{-- Plannings actifs --}}
                <div class="sidebar-card">
                    <div class="sidebar-title">Plannings actifs</div>
                    @forelse($planningsActifs as $pl)
                        @php
                            $jours = is_array($pl->jours) ? $pl->jours : json_decode($pl->jours, true);
                            $freqLabel =
                                $pl->frequency_type === '1x'
                                    ? '1x/sem'
                                    : ($pl->frequency_type === '2x'
                                        ? '2x/sem'
                                        : 'Perso');
                            $initials =
                                strtoupper(mb_substr($pl->patient->first_name, 0, 1)) .
                                strtoupper(mb_substr($pl->patient->last_name, 0, 1));
                        @endphp
                        <a href="{{ route('patients.show', $pl->patient_id) }}#tab-rdv" class="mini-planning-row">
                            <div class="mini-avatar" style="background:#ecfdf5;color:#16a34a;">{{ $initials }}</div>
                            <div>
                                <div class="mini-planning-name">{{ $pl->patient->first_name }}
                                    {{ $pl->patient->last_name }}</div>
                                <div class="mini-planning-meta">{{ $freqLabel }} · {{ implode(', ', $jours ?? []) }} ·
                                    {{ \Carbon\Carbon::parse($pl->heure)->format('H:i') }}</div>
                            </div>
                            <iconify-icon icon="ph:caret-right-bold" class="mini-planning-arrow"></iconify-icon>
                        </a>
                    @empty
                        <div style="font-size:12px;color:#94a3b8;text-align:center;padding:12px 0;">Aucun planning actif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MODAL NOUVEAU RDV ══ --}}
    <div class="modal-overlay" id="rdv-modal">
        <div class="modal-box">
            <div class="modal-header">
                <div>
                    <span class="modal-title">Nouveau rendez-vous</span>
                    <div class="modal-sub">Programmez une mesure de constantes</div>
                </div>
                <button class="modal-close" onclick="closeRdvModal()"><iconify-icon
                        icon="ph:x-bold"></iconify-icon></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('rendezvous.store') }}" method="POST" id="rdvForm">
                    @csrf

                    <div class="field-lbl">Patient *</div>
                    <select name="patient_id" class="f-input f-select" required>
                        <option value="">Choisir un patient...</option>
                        @foreach ($patients as $p)
                            <option value="{{ $p->id_patient }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                        @endforeach
                    </select>

                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Date *</div>
                            <input type="date" name="date" class="f-input" value="{{ now()->format('Y-m-d') }}"
                                required>
                        </div>
                        <div>
                            <div class="field-lbl">Heure *</div>
                            <input type="time" name="heure" class="f-input" value="09:00" required>
                        </div>
                    </div>

                    <div class="field-lbl">Mesures à effectuer *</div>
                    <div class="mesure-toggle-grid">
                        <button type="button" class="mesure-toggle-btn active" onclick="toggleMesureType(this)"
                            data-value="PRESSION_ARTERIELLE">
                            <iconify-icon icon="ph:heart-bold"></iconify-icon> Tension
                        </button>
                        <button type="button" class="mesure-toggle-btn" onclick="toggleMesureType(this)"
                            data-value="FREQUENCE_CARDIAQUE">
                            <iconify-icon icon="ph:activity-bold"></iconify-icon> Fréq. C.
                        </button>
                        <button type="button" class="mesure-toggle-btn" onclick="toggleMesureType(this)"
                            data-value="GLYCEMIE">
                            <iconify-icon icon="ph:drop-bold"></iconify-icon> Glycémie
                        </button>
                        <button type="button" class="mesure-toggle-btn" onclick="toggleMesureType(this)"
                            data-value="POIDS_IMC">
                            <iconify-icon icon="ph:scales-bold"></iconify-icon> Poids
                        </button>
                    </div>
                    <div id="rdv-mesures-inputs"></div>

                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Rappel avant</div>
                            <select name="rappel_avant" class="f-input f-select">
                                <option value="24H">La veille (24h)</option>
                                <option value="2H">2 heures avant</option>
                                <option value="1H">1 heure avant</option>
                                <option value="AUCUN">Aucun rappel</option>
                            </select>
                        </div>
                        <div>
                            <div class="field-lbl">Canal</div>
                            <select name="canal" class="f-input f-select">
                                <option value="WHATSAPP">WhatsApp</option>
                                <option value="SMS">SMS</option>
                            </select>
                        </div>
                    </div>

                    <div class="field-lbl">Notes (optionnel)</div>
                    <textarea name="notes" class="f-input" rows="2" style="resize:none;"
                        placeholder="Instructions spécifiques..."></textarea>

                    <div class="panel-btns">
                        <button type="button" class="btn-annuler" onclick="closeRdvModal()">Annuler</button>
                        <button type="submit" class="btn-enregistrer" id="btn-submit-rdv" disabled>Programmer le
                            RDV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /* ── TABS (fallback JS si navigation sans reload nécessaire — ici on utilise des liens, donc pas indispensable) ── */

        /* ── MODAL ── */
        function openRdvModal() {
            document.getElementById('rdv-modal')?.classList.add('show');
        }

        function closeRdvModal() {
            document.getElementById('rdv-modal')?.classList.remove('show');
        }
        document.getElementById('rdv-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRdvModal();
        });

        /* ── Mesures toggle ── */
        function toggleMesureType(btn) {
            btn.classList.toggle('active');
            syncRdvMesuresInputs();
        }

        function syncRdvMesuresInputs() {
            const container = document.getElementById('rdv-mesures-inputs');
            container.innerHTML = '';
            const actifs = document.querySelectorAll('#rdvForm .mesure-toggle-btn.active');
            actifs.forEach(b => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'mesures_types[]';
                input.value = b.dataset.value;
                container.appendChild(input);
            });
            document.getElementById('btn-submit-rdv').disabled = actifs.length === 0;
        }

        document.addEventListener('DOMContentLoaded', syncRdvMesuresInputs);
    </script>

@endsection
