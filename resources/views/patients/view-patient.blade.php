@extends('layouts.master', ['title' => 'Dossier Patient'])

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        .dash-body {
            padding: 24px 28px;
            min-height: 100%;
        }

        /* ── Back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            margin-bottom: 18px;
            transition: color .15s;
        }

        .back-link:hover {
            color: #0f172a;
        }

        /* ── Patient Header Card ── */
        .patient-header {
            background: white;
            border-radius: 20px;
            padding: 24px 28px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .p-avatar-lg {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            background: #fce4ec;
            color: #c2185b;
        }

        .p-header-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
        }

        .p-header-meta {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 3px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .p-header-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .p-patho-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            margin-right: 6px;
        }

        .p-patho-hta {
            background: #fce4ec;
            color: #c2185b;
        }

        .p-patho-diab {
            background: #fff3e0;
            color: #e65100;
        }

        /* QR Code */
        .qr-block {
            text-align: center;
            flex-shrink: 0;
        }

        .qr-img {
            width: 90px;
            height: 90px;
            background: #1a1a2e;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .qr-code-svg {
            width: 100%;
            height: 100%;
        }

        .qr-id {
            font-size: 11px;
            font-family: 'DM Mono', monospace;
            color: #94a3b8;
            margin-top: 5px;
        }

        /* Action buttons */
        .action-btns {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid #e2e8f0;
            background: white;
            color: #334155;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-action:hover {
            background: #f8fafc;
        }

        .btn-action.green {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .btn-action.green:hover {
            background: #15803d;
        }

        .btn-action.orange {
            background: #f97316;
            border-color: #f97316;
            color: white;
        }

        .btn-action.orange:hover {
            background: #ea6d0e;
        }

        .btn-action.outline-green {
            border-color: #16a34a;
            color: #16a34a;
        }

        .btn-action.outline-green:hover {
            background: #f0fdf4;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .s-retard {
            background: #fff7ed;
            color: #d97706;
        }

        .s-ok {
            background: #ecfdf5;
            color: #16a34a;
        }

        .s-critique {
            background: #fef2f2;
            color: #dc2626;
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
            flex: 1;
            min-width: 110px;
            padding: 10px 14px;
            border-radius: 12px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .tab-btn:hover {
            background: #f8fafc;
            color: #334155;
        }

        .tab-btn.active {
            background: #16a34a;
            color: white;
        }

        /* ── Content cards ── */
        .content-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 14px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-sub {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 18px;
        }

        /* ── Bilan clinique ── */
        .bilan-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }

        .bilan-item {
            border: 1.5px dashed #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .bilan-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .bilan-warn {
            font-size: 11px;
            color: #f59e0b;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .btn-saisir {
            width: 100%;
            border: 1.5px dashed #86efac;
            border-radius: 12px;
            padding: 12px;
            background: transparent;
            color: #16a34a;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
        }

        .btn-saisir:hover {
            background: #f0fdf4;
        }

        /* ── Mesure cards ── */
        .mesure-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .mesure-card {
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            background: white;
            padding: 18px;
        }

        .mesure-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .mesure-label {
            font-size: 12px;
            color: #94a3b8;
            margin: 8px 0 2px;
        }

        .mesure-value {
            font-size: 1.7rem;
            font-weight: 700;
            color: #0f172a;
        }

        .mesure-unit {
            font-size: 13px;
            font-weight: 400;
            color: #94a3b8;
        }

        .mesure-status {
            display: inline-flex;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 8px;
        }

        /* ── Pathologie rows ── */
        .patho-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            background: white;
            margin-bottom: 10px;
        }

        .patho-tag {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            flex-shrink: 0;
        }

        /* ── Traitement rows ── */
        .trait-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f5f3ff;
            color: #9333ea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .trait-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .trait-meta {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .trait-retard {
            font-size: 12px;
            font-weight: 600;
            color: #dc2626;
        }

        .btn-renouveler {
            display: inline-flex;
            align-items: center;
            gap: 6px;
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

        .btn-renouveler:hover {
            background: #f8fafc;
        }

        /* ── Add form panel ── */
        .add-panel {
            border: 1.5px solid #d1fae5;
            border-radius: 16px;
            background: #f0fdf4;
            padding: 20px;
            margin-bottom: 14px;
            display: none;
        }

        .add-panel.show {
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
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
        }

        .f-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        .f-select {
            appearance: none;
        }

        .fin-estimee {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #16a34a;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .panel-btns {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
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

        /* ── Mesure type selector ── */
        .mesure-type-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .mesure-type-btn {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 10px;
            background: white;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .mesure-type-btn:hover {
            border-color: #94a3b8;
        }

        .mesure-type-btn.active {
            border-color: #16a34a;
            border-width: 2px;
        }

        .mesure-type-btn.active .mtype-icon {
            color: var(--ac);
        }

        .mesure-type-btn.active .mtype-label {
            color: #0f172a;
        }

        .mtype-icon {
            font-size: 1.4rem;
            color: #94a3b8;
        }

        .info-bar {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-bar.red {
            background: #fef2f2;
            color: #dc2626;
        }

        .info-bar.amber {
            background: #fffbeb;
            color: #d97706;
        }

        .info-bar.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .last-mesure {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .big-input {
            width: 100%;
            text-align: center;
            padding: 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            background: white;
            outline: none;
            font-family: 'DM Sans', sans-serif;
        }

        .big-input:focus {
            border-color: #16a34a;
        }

        .context-bar {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #16a34a;
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        /* ── Charts ── */
        .chart-wrap {
            padding: 0 0 8px;
        }

        .chart-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        canvas {
            max-width: 100%;
        }

        /* ── Historique mesures ── */
        .histo-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .histo-row:last-child {
            border-bottom: none;
        }

        .histo-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .histo-value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .histo-date {
            font-size: 11px;
            font-family: 'DM Mono', monospace;
            color: #94a3b8;
        }

        .histo-note {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ── Messages ── */
        .msg-row {
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 16px;
            background: white;
            margin-bottom: 10px;
        }

        .msg-badges {
            display: flex;
            gap: 6px;
            margin-bottom: 8px;
        }

        .msg-badge {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-wa {
            background: #ecfdf5;
            color: #16a34a;
        }

        .badge-sms {
            background: #eff6ff;
            color: #2563eb;
        }

        .badge-trans {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-livre {
            background: #f0fdf4;
            color: #16a34a;
        }

        .msg-text {
            font-size: 13px;
            color: #334155;
            line-height: 1.5;
            margin-bottom: 4px;
        }

        .msg-time {
            font-size: 11px;
            font-family: 'DM Mono', monospace;
            color: #94a3b8;
        }

        /* Canal toggle */
        .canal-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            margin-bottom: 14px;
        }

        .canal-btn {
            padding: 11px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            background: white;
            color: #64748b;
            border: none;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .canal-btn.active {
            background: #16a34a;
            color: white;
        }

        /* ── Réseau ── */
        .reseau-log {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
        }

        .reseau-log:last-child {
            border-bottom: none;
        }

        .reseau-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .reseau-action {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }

        .reseau-meta {
            font-size: 12px;
            color: #94a3b8;
        }

        .reseau-time {
            font-size: 11px;
            font-family: 'DM Mono', monospace;
            color: #94a3b8;
            white-space: nowrap;
        }

        .pharma-linked {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            margin-bottom: 8px;
        }

        .pharma-linked.main {
            border-color: #d1fae5;
            background: #f0fdf4;
        }

        .pl-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #94a3b8;
            flex-shrink: 0;
        }

        .pl-dot.green {
            background: #16a34a;
        }

        .pl-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .pl-badge.main-b {
            background: #ecfdf5;
            color: #16a34a;
        }

        .pl-badge.sec-b {
            background: #f1f5f9;
            color: #64748b;
        }

        /* ── Rappel inline ── */
        .rappel-panel {
            background: white;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 14px;
            display: none;
        }

        .rappel-panel.show {
            display: block;
        }

        .rappel-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        /* ── Modal overlay ── */
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
            width: 520px;
            max-width: 95vw;
            max-height: 90vh;
            overflow-y: auto;
            padding: 0;
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

        /* Modal tabs */
        .modal-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .modal-tab {
            padding: 10px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: white;
            color: #64748b;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .modal-tab.active {
            background: #16a34a;
            color: white;
        }

        /* Période chips */
        .periode-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .periode-chip {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .periode-chip.active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        /* Section toggles */
        .section-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
        }

        .section-row:last-child {
            border-bottom: none;
        }

        .section-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .section-count {
            font-size: 12px;
            color: #94a3b8;
        }

        .toggle-switch {
            width: 40px;
            height: 22px;
            border-radius: 20px;
            background: #16a34a;
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
            border: none;
            transition: background .2s;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 3px;
            right: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            transition: right .2s;
        }

        .toggle-switch.off {
            background: #d1d5db;
        }

        .toggle-switch.off::after {
            right: auto;
            left: 3px;
        }

        /* Données summary */
        .donnees-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 14px 0;
        }

        .donnee-item {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .donnee-num {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .donnee-lbl {
            font-size: 11px;
            color: #94a3b8;
        }

        .mesure-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .mesure-tag {
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Preview bilan */
        .bilan-preview {
            background: #16a34a;
            border-radius: 14px;
            padding: 18px 20px;
            color: white;
            margin-bottom: 16px;
        }

        .bilan-preview-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            opacity: .7;
            margin-bottom: 8px;
        }

        .bilan-preview-name {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .bilan-preview-sub {
            font-size: 12px;
            opacity: .75;
            margin-bottom: 12px;
        }

        .bilan-preview-pharma {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .preview-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .preview-stat {
            text-align: center;
        }

        .preview-stat-num {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .preview-stat-lbl {
            font-size: 10px;
            opacity: .75;
        }

        .bilan-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            font-size: 11px;
            opacity: .7;
        }

        /* Export / Transfer */
        .btn-export {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            background: #16a34a;
            border: none;
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'DM Sans', sans-serif;
            margin-bottom: 16px;
            transition: background .15s;
        }

        .btn-export:hover {
            background: #15803d;
        }

        .transfer-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .transfer-btns {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .transfer-btn {
            padding: 12px 8px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .transfer-btn:hover {
            border-color: #16a34a;
            background: #f0fdf4;
            color: #16a34a;
        }

        .transfer-btn.selected {
            border-color: #2563eb;
            background: #eff6ff;
            color: #2563eb;
        }

        /* Add btn */
        .btn-add-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
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

        .btn-add-sm:hover {
            background: #15803d;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .bilan-grid,
            .mesure-grid,
            .mesure-type-grid {
                grid-template-columns: 1fr 1fr;
            }

            .form-row,
            .preview-stats,
            .donnees-grid {
                grid-template-columns: 1fr;
            }

            .patient-header {
                flex-direction: column;
            }

            .qr-block {
                align-self: flex-end;
            }
        }
    </style>

    <div class="dash-body">

        @include('layouts.statuts')

        <a href="{{ route('patients.index') }}" class="back-link">
            <iconify-icon icon="ph:arrow-left-bold"></iconify-icon> Retour à la liste
        </a>

        {{--
    ══════════════════════════════════════════════════════
    AFFICHAGE DES MESSAGES FLASH (à mettre dans layouts/statuts.blade.php
    ou directement dans la vue après @include('layouts.statuts'))
    ══════════════════════════════════════════════════════
--}}

        @if (session('success'))
            <div style="
    position:fixed; top:20px; right:20px; z-index:9999;
    background:#16a34a; color:white;
    padding:14px 20px; border-radius:14px;
    font-size:13px; font-weight:600;
    display:flex; align-items:center; gap:10px;
    box-shadow: 0 8px 24px rgba(22,163,74,.3);
    animation: slideIn .3s ease;
    max-width: 380px;
"
                id="flash-success">
                <iconify-icon icon="ph:check-circle-bold" style="font-size:1.3rem;flex-shrink:0;"></iconify-icon>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()"
                    style="background:none;border:none;color:white;cursor:pointer;margin-left:auto;font-size:1.1rem;">
                    <iconify-icon icon="ph:x-bold"></iconify-icon>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div style="
    position:fixed; top:20px; right:20px; z-index:9999;
    background:#dc2626; color:white;
    padding:14px 20px; border-radius:14px;
    font-size:13px; font-weight:600;
    display:flex; align-items:center; gap:10px;
    box-shadow: 0 8px 24px rgba(220,38,38,.3);
    max-width: 380px;
"
                id="flash-error">
                <iconify-icon icon="ph:warning-bold" style="font-size:1.3rem;flex-shrink:0;"></iconify-icon>
                {{ session('error') }}
                <button onclick="this.parentElement.remove()"
                    style="background:none;border:none;color:white;cursor:pointer;margin-left:auto;font-size:1.1rem;">
                    <iconify-icon icon="ph:x-bold"></iconify-icon>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div
                style="
    position:fixed; top:20px; right:20px; z-index:9999;
    background:#dc2626; color:white;
    padding:14px 20px; border-radius:14px;
    font-size:13px; font-weight:600;
    box-shadow: 0 8px 24px rgba(220,38,38,.3);
    max-width: 380px;
">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <iconify-icon icon="ph:warning-bold" style="font-size:1.2rem;"></iconify-icon>
                    Erreur de validation
                </div>
                <ul style="margin:0;padding-left:16px;font-weight:400;font-size:12px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Auto-dismiss flash après 4 secondes --}}
        <style>
            @keyframes slideIn {
                from {
                    transform: translateX(120%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        </style>
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

            // Ouvre automatiquement le bon onglet si ancre dans l'URL
            const hash = window.location.hash;
            if (hash && hash.startsWith('#tab-')) {
                switchTab(hash.replace('#tab-', ''));
            }
        </script>

        {{-- ══ Header patient ══ --}}
        @php
            $statusClass = match ($patient->status) {
                'A_JOUR' => 's-ok',
                'EN_RETARD' => 's-retard',
                'CRITIQUE' => 's-critique',
                'BIENTOT_RETARD' => 's-bientot',
                default => 's-retard',
            };
            $statusLabel = match ($patient->status) {
                'A_JOUR' => 'À jour',
                'EN_RETARD' => 'En retard',
                'CRITIQUE' => 'Critique',
                'BIENTOT_RETARD' => 'Bientôt retard',
                default => 'En retard',
            };
            $avatarBg = '#ecfdf5';
            $avatarColor = '#16a34a';
        @endphp

        <div class="patient-header">
            <div class="d-flex gap-3 align-items-start flex-grow-1">
                <div class="p-avatar-lg" style="background:{{ $avatarBg }};color:{{ $avatarColor }};">
                    {{ $patient->initials }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="p-header-name">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                        @if ($patient->age)
                            <span style="color:#94a3b8;font-size:13px;">{{ $patient->age }} ans</span>
                        @endif
                        @if ($patient->height_cm)
                            <span style="color:#94a3b8;font-size:13px;">· {{ $patient->height_cm }} cm</span>
                        @endif
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="p-header-meta">
                        <span><iconify-icon icon="ph:phone-bold"></iconify-icon> {{ $patient->phone_number }}</span>
                        @if ($patient->city)
                            <span><iconify-icon icon="ph:map-pin-bold"></iconify-icon>
                                {{ $patient->city }}{{ $patient->commune ? ', ' . $patient->commune : '' }}
                            </span>
                        @endif
                    </div>
                    <div>
                        @foreach ($pathologies as $p)
                            @php
                                $ptClass = match ($p->code) {
                                    'HTA' => 'p-patho-hta',
                                    'DIAB' => 'p-patho-diab',
                                    'DYSLIP' => 'p-patho-dyslip',
                                    'ASTH' => 'p-patho-asth',
                                    default => 'p-patho-hta',
                                };
                            @endphp
                            <span class="p-patho-tag {{ $ptClass }}">{{ $p->name }}</span>
                        @endforeach
                    </div>
                    <div class="action-btns">
                        <button class="btn-action green" onclick="showMesurePanel()">
                            <iconify-icon icon="ph:heartbeat-bold"></iconify-icon> Mesure
                        </button>
                        <button class="btn-action" onclick="switchTab('traitements'); showAddTrait()">
                            <iconify-icon icon="ph:pill-bold"></iconify-icon> Traitement
                        </button>
                        <button class="btn-action orange" onclick="switchTab('messages'); showSendMsg()">
                            <iconify-icon icon="ph:bell-bold"></iconify-icon> Rappel
                        </button>
                        <button class="btn-action outline-green" onclick="openBilanModal()">
                            <iconify-icon icon="ph:file-text-bold"></iconify-icon> Bilan
                        </button>
                    </div>
                </div>
            </div>
            <div class="qr-block">
                <div class="qr-img">
                    {!! QrCode::size(180)->generate($patient->qr_code) !!}
                </div>

                <div class="qr-id">
                    {{ $patient->qr_code }}
                </div>
            </div>
        </div>

        {{-- ══ Tabs ══ --}}
        <div class="tabs-bar">
            <button class="tab-btn active" onclick="switchTab('generale')">Vue générale</button>
            <button class="tab-btn" onclick="switchTab('pathologies')">Pathologies</button>
            <button class="tab-btn" onclick="switchTab('traitements')">Traitements</button>
            <button class="tab-btn" onclick="switchTab('mesures')">Mesures</button>
            <button class="tab-btn" onclick="switchTab('messages')">Messages</button>
            <button class="tab-btn" onclick="switchTab('reseau')">Réseau</button>
        </div>

        {{-- ════════ TAB : VUE GÉNÉRALE ════════ --}}
        <div id="tab-generale">
            {{-- Bilan clinique requis --}}
            <div class="content-card">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div
                        style="width:32px;height:32px;border-radius:50%;background:#ecfdf5;color:#16a34a;display:flex;align-items:center;justify-content:center;">
                        <iconify-icon icon="ph:clipboard-text-bold"></iconify-icon>
                    </div>
                    <div>
                        <div class="card-title mb-0">Bilan clinique requis</div>
                        <div class="card-sub mb-0">Mesures selon les pathologies · Période recommandée : 30 jours</div>
                    </div>
                </div>
                <div class="bilan-grid mt-3">
                    @php
                        $bilanTypes = [
                            ['PRESSION_ARTERIELLE', 'ph:heart-bold', '#fce4ec', '#c2185b', 'PA'],
                            ['FREQUENCE_CARDIAQUE', 'ph:activity-bold', '#fdf4ff', '#9333ea', 'Pouls'],
                            ['GLYCEMIE', 'ph:drop-bold', '#fff7ed', '#d97706', 'Glycémie'],
                            ['POIDS_IMC', 'ph:scales-bold', '#eff6ff', '#2563eb', 'Poids'],
                        ];
                        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
                    @endphp
                    @foreach ($bilanTypes as [$type, $icon, $bg, $color, $label])
                        @php
                            $m = $derniereMesures[$type] ?? null;
                            $measuredThisMonth = $m && \Carbon\Carbon::parse($m->created_at)->gte($startOfMonth);
                        @endphp
                        <div class="bilan-item">
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    style="width:28px;height:28px;border-radius:8px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;">
                                    <iconify-icon icon="{{ $icon }}" style="font-size:.9rem;"></iconify-icon>
                                </div>
                                <span class="bilan-label">{{ $label }}</span>
                            </div>
                            @if ($measuredThisMonth)
                                <div class="bilan-ok"><iconify-icon icon="ph:check-circle-bold"></iconify-icon> Mesuré ce
                                    mois</div>
                            @else
                                <div class="bilan-warn"><iconify-icon icon="ph:warning-bold"></iconify-icon> Non mesuré ce
                                    mois</div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button class="btn-saisir" onclick="switchTab('mesures'); showMesurePanel()">
                    <iconify-icon icon="ph:plus-bold"></iconify-icon> Saisir une mesure
                </button>
            </div>

            {{-- Dernières mesures --}}
            @php
                $pa = $derniereMesures['PRESSION_ARTERIELLE'] ?? null;
                $fc = $derniereMesures['FREQUENCE_CARDIAQUE'] ?? null;
                $glyc = $derniereMesures['GLYCEMIE'] ?? null;
                $poids = $derniereMesures['POIDS_IMC'] ?? null;
            @endphp
            <div class="mesure-grid mb-3">
                <div class="mesure-card">
                    <div class="mesure-icon" style="background:#fce4ec;color:#c2185b;">
                        <iconify-icon icon="ph:heart-bold"></iconify-icon>
                    </div>
                    <div class="mesure-label">Dernière Pression
                        Artérielle{{ $pa ? ' · ' . \Carbon\Carbon::parse($pa->created_at)->translatedFormat('j M.') : '' }}
                    </div>
                    @if ($pa)
                        <div class="mesure-value">{{ $pa->systolic }}/{{ $pa->diastolic }} <span
                                class="mesure-unit">mmHg</span></div>
                        <span class="mesure-status"
                            style="background:{{ $pa->status_label === 'Critique' ? '#fef2f2' : '#fff7ed' }};color:{{ $pa->status_label === 'Critique' ? '#dc2626' : '#d97706' }};">{{ $pa->status_label }}</span>
                    @else
                        <div class="mesure-value" style="color:#cbd5e1;">—</div>
                    @endif
                </div>
                <div class="mesure-card">
                    <div class="mesure-icon" style="background:#fdf4ff;color:#9333ea;">
                        <iconify-icon icon="ph:activity-bold"></iconify-icon>
                    </div>
                    <div class="mesure-label">Dernier
                        Pouls{{ $fc ? ' · ' . \Carbon\Carbon::parse($fc->created_at)->translatedFormat('j M.') : '' }}
                    </div>
                    @if ($fc)
                        <div class="mesure-value">{{ $fc->heart_rate_bpm }} <span class="mesure-unit">bpm</span></div>
                        <span class="mesure-status"
                            style="background:#ecfdf5;color:#16a34a;">{{ $fc->status_label ?? 'Normal' }}</span>
                    @else
                        <div class="mesure-value" style="color:#cbd5e1;">—</div>
                    @endif
                </div>
                <div class="mesure-card">
                    <div class="mesure-icon" style="background:#fff7ed;color:#d97706;">
                        <iconify-icon icon="ph:drop-bold"></iconify-icon>
                    </div>
                    <div class="mesure-label">Dernière
                        Glycémie{{ $glyc ? ' · ' . \Carbon\Carbon::parse($glyc->created_at)->translatedFormat('j M.') : '' }}{{ $glyc?->is_fasting ? ' · À jeun' : '' }}
                    </div>
                    @if ($glyc)
                        <div class="mesure-value">{{ $glyc->glycemia_mmol }} <span class="mesure-unit">mmol/L</span>
                        </div>
                        <span class="mesure-status"
                            style="background:#fff7ed;color:#d97706;">{{ $glyc->status_label ?? 'Élevé' }}</span>
                    @else
                        <div class="mesure-value" style="color:#cbd5e1;">—</div>
                    @endif
                </div>
                <div class="mesure-card">
                    <div class="mesure-icon" style="background:#eff6ff;color:#2563eb;">
                        <iconify-icon icon="ph:scales-bold"></iconify-icon>
                    </div>
                    <div class="mesure-label">Dernier Poids /
                        IMC{{ $poids ? ' · ' . \Carbon\Carbon::parse($poids->created_at)->translatedFormat('j M.') : '' }}
                    </div>
                    @if ($poids)
                        <div class="mesure-value">{{ $poids->weight_kg }} <span class="mesure-unit">kg</span>
                            @if ($poids->imc)
                                <span style="font-size:1rem;color:#64748b;">IMC {{ $poids->imc }}</span>
                            @endif
                        </div>
                        <span class="mesure-status"
                            style="background:#fff7ed;color:#d97706;">{{ $poids->status_label ?? 'Surpoids' }}</span>
                    @else
                        <div class="mesure-value" style="color:#cbd5e1;">—</div>
                    @endif
                </div>
            </div>

            {{-- Renouvellement + Dernier rappel --}}
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <div class="content-card h-100 mb-0">
                        <div class="card-title"><iconify-icon icon="ph:pill-bold" style="color:#9333ea;"></iconify-icon>
                            Prochain renouvellement</div>
                        @forelse($traitements->where('status','ACTIF') as $t)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom"
                                style="font-size:13px;">
                                <span style="color:#334155;">{{ $t->medication_name }}</span>
                                @if ($t->days_late > 0)
                                    <span class="trait-retard">{{ $t->days_late }}j retard</span>
                                @else
                                    <span style="color:#16a34a;font-size:12px;font-weight:600;">À jour</span>
                                @endif
                            </div>
                        @empty
                            <span style="font-size:13px;color:#94a3b8;">Aucun traitement actif</span>
                        @endforelse
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="content-card h-100 mb-0">
                        <div class="card-title"><iconify-icon icon="ph:bell-bold" style="color:#d97706;"></iconify-icon>
                            Dernier rappel</div>
                        @if ($messages->first())
                            @php $lastMsg = $messages->first(); @endphp
                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                                {{ ucfirst(strtolower($lastMsg->type)) }}</div>
                            <div style="font-size:12px;color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($lastMsg->created_at)->translatedFormat('j M.') }} ·
                                <span style="color:#16a34a;font-weight:600;">{{ $lastMsg->channel }}</span>
                            </div>
                        @else
                            <span style="font-size:13px;color:#94a3b8;">Aucun rappel envoyé</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Consentements --}}
            <div class="content-card">
                <div class="card-title">Consentements</div>
                <div class="d-flex flex-wrap gap-2">
                    @if ($patient->consent_suivi)
                        <span
                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:20px;background:#ecfdf5;color:#16a34a;font-size:12px;font-weight:600;"><iconify-icon
                                icon="ph:check-circle-bold"></iconify-icon> Suivi santé</span>
                    @endif
                    @if ($patient->consent_whatsapp)
                        <span
                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:20px;background:#ecfdf5;color:#16a34a;font-size:12px;font-weight:600;"><iconify-icon
                                icon="ph:check-circle-bold"></iconify-icon> WhatsApp</span>
                    @endif
                    @if ($patient->consent_sms)
                        <span
                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:20px;background:#ecfdf5;color:#16a34a;font-size:12px;font-weight:600;"><iconify-icon
                                icon="ph:check-circle-bold"></iconify-icon> SMS</span>
                    @endif
                    @if ($patient->consent_reseau)
                        <span
                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 13px;border-radius:20px;background:#ecfdf5;color:#16a34a;font-size:12px;font-weight:600;"><iconify-icon
                                icon="ph:check-circle-bold"></iconify-icon> Réseau pharmacies</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ════════ TAB : PATHOLOGIES ════════ --}}
        <div id="tab-pathologies" style="display:none;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size:13px;font-weight:600;color:#64748b;">{{ $pathologies->count() }} pathologie(s)
                    active(s)</span>
                <button class="btn-add-sm" onclick="togglePanel('add-patho-panel')">
                    <iconify-icon icon="ph:plus-bold"></iconify-icon> Ajouter pathologie
                </button>
            </div>

            <div class="add-panel" id="add-patho-panel">
                <form action="{{ route('patients.pathologie.store', $patient->id_patient) }}" method="POST">
                    @csrf
                    <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px;">Nouvelle pathologie</div>
                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Pathologie</div>
                            <select name="pathologie_id" class="f-input f-select">
                                @foreach ($allPathologies as $ap)
                                    <option value="{{ $ap->id_pathologie }}">{{ $ap->name }} ({{ $ap->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <div class="field-lbl">Priorité</div>
                            <select name="priority" class="f-input f-select">
                                <option value="ELEVEE">Élevée</option>
                                <option value="HAUTE">Haute</option>
                                <option value="MOYENNE" selected>Moyenne</option>
                                <option value="FAIBLE">Faible</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Date d'entrée</div>
                            <input type="date" name="start_date" class="f-input" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <div class="field-lbl">Médecin référent</div>
                            <input type="text" name="doctor_name" class="f-input" placeholder="Dr. Nom">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="field-lbl">Notes</div>
                        <textarea name="notes" class="f-input" rows="2" style="resize:none;"></textarea>
                    </div>
                    <div class="panel-btns">
                        <button type="button" class="btn-annuler"
                            onclick="togglePanel('add-patho-panel')">Annuler</button>
                        <button type="submit" class="btn-enregistrer">Enregistrer</button>
                    </div>
                </form>
            </div>

            @foreach ($pathologies as $p)
                @php
                    $ptBg = match ($p->code) {
                        'HTA' => '#fce4ec',
                        'DIAB' => '#fff3e0',
                        'DYSLIP' => '#e8eaf6',
                        'ASTH' => '#e0f2f1',
                        default => '#f1f5f9',
                    };
                    $ptColor = match ($p->code) {
                        'HTA' => '#c2185b',
                        'DIAB' => '#e65100',
                        'DYSLIP' => '#3949ab',
                        'ASTH' => '#00695c',
                        default => '#64748b',
                    };
                    $prioLabel = match ($p->priority) {
                        'ELEVEE' => 'Priorité élevée',
                        'HAUTE' => 'Priorité haute',
                        'MOYENNE' => 'Priorité moyenne',
                        'FAIBLE' => 'Priorité faible',
                        default => 'Priorité moyenne',
                    };
                    $prioBg = match ($p->priority) {
                        'ELEVEE', 'HAUTE' => '#fff7ed',
                        'MOYENNE' => '#eff6ff',
                        'FAIBLE' => '#f1f5f9',
                        default => '#eff6ff',
                    };
                    $prioColor = match ($p->priority) {
                        'ELEVEE', 'HAUTE' => '#d97706',
                        'MOYENNE' => '#2563eb',
                        'FAIBLE' => '#64748b',
                        default => '#2563eb',
                    };
                @endphp
                <div class="patho-row">
                    <div class="patho-tag" style="background:{{ $ptBg }};color:{{ $ptColor }};">
                        {{ $p->code }}</div>
                    <div class="flex-grow-1">
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">
                            {{ $p->name }}
                            <span
                                style="display:inline-flex;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700;background:{{ $prioBg }};color:{{ $prioColor }};margin-left:8px;">{{ $prioLabel }}</span>
                            <span
                                style="display:inline-flex;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700;background:#ecfdf5;color:#16a34a;margin-left:4px;">{{ ucfirst(strtolower($p->status)) }}</span>
                        </div>
                        <div style="font-size:12px;color:#94a3b8;">
                            Suivi depuis :
                            {{ $p->start_date ? \Carbon\Carbon::parse($p->start_date)->translatedFormat('M Y') : '—' }}
                            @if ($p->doctor_name)
                                &nbsp; Médecin : {{ $p->doctor_name }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ════════ TAB : TRAITEMENTS ════════ --}}
        <div id="tab-traitements" style="display:none;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span
                    style="font-size:13px;font-weight:600;color:#64748b;">{{ $traitements->where('status', 'ACTIF')->count() }}
                    traitement(s) actif(s)</span>
                <button class="btn-add-sm" id="btn-add-trait" onclick="togglePanel('add-trait-panel')">
                    <iconify-icon icon="ph:plus-bold"></iconify-icon> Ajouter traitement
                </button>
            </div>

            <div class="add-panel" id="add-trait-panel">
                <form action="{{ route('patients.traitement.store', $patient->id_patient) }}" method="POST">
                    @csrf
                    <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px;">Nouveau traitement</div>
                    <div class="mb-3">
                        <div class="field-lbl">Médicament</div>
                        <input type="text" name="medication_name" class="f-input"
                            placeholder="Rechercher un médicament...">
                    </div>
                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Dosage</div><input type="text" name="dosage" class="f-input"
                                placeholder="Ex: 5 mg/j">
                        </div>
                        <div>
                            <div class="field-lbl">Fréquence/jour</div><input type="number" name="frequency_per_day"
                                class="f-input" placeholder="1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Quantité délivrée</div><input type="number" name="quantity_delivered"
                                class="f-input" placeholder="30">
                        </div>
                        <div>
                            <div class="field-lbl">Durée (jours)</div><input type="number" name="duration_days"
                                class="f-input" placeholder="30">
                        </div>
                    </div>
                    <div class="form-row">
                        <div>
                            <div class="field-lbl">Date dispensation</div><input type="date" name="dispensed_at"
                                class="f-input" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <div class="field-lbl">Lien pathologie</div>
                            <select name="pathologie_id" class="f-input f-select">
                                @foreach ($pathologies as $pp)
                                    <option value="{{ $pp->pathologie_id }}">{{ $pp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fin-estimee">
                        <iconify-icon icon="ph:calendar-bold"></iconify-icon>
                        La date de fin estimée sera calculée automatiquement après enregistrement
                    </div>
                    <div class="panel-btns">
                        <button type="button" class="btn-annuler"
                            onclick="togglePanel('add-trait-panel')">Annuler</button>
                        <button type="submit" class="btn-enregistrer">Enregistrer</button>
                    </div>
                </form>
            </div>

            @forelse($traitements as $t)
                @php
                    $tBg = match ($t->patho_code ?? '') {
                        'HTA' => '#fce4ec',
                        'DIAB' => '#fff3e0',
                        'DYSLIP' => '#e8eaf6',
                        'ASTH' => '#e0f2f1',
                        default => '#f5f3ff',
                    };
                    $tColor = match ($t->patho_code ?? '') {
                        'HTA' => '#c2185b',
                        'DIAB' => '#e65100',
                        'DYSLIP' => '#3949ab',
                        'ASTH' => '#00695c',
                        default => '#9333ea',
                    };
                @endphp
                <div class="content-card mb-3 d-flex align-items-center gap-3">
                    <div class="trait-icon"><iconify-icon icon="ph:pill-bold"></iconify-icon></div>
                    <div class="flex-grow-1">
                        <div class="trait-name">
                            {{ $t->medication_name }}
                            @if ($t->patho_code)
                                <span
                                    style="display:inline-flex;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:800;background:{{ $tBg }};color:{{ $tColor }};margin-left:6px;">{{ $t->patho_code }}</span>
                            @endif
                        </div>
                        <div class="trait-meta">{{ $t->dosage }} · {{ $t->frequency_per_day }}x/jour</div>
                        <div class="trait-meta">
                            Délivré : {{ \Carbon\Carbon::parse($t->dispensed_at)->format('d/m/Y') }}
                            @if ($t->estimated_end_date)
                                · Fin estimée : {{ \Carbon\Carbon::parse($t->estimated_end_date)->format('d/m/Y') }}
                            @endif
                            @if ($t->days_late > 0)
                                <span class="trait-retard ms-2">{{ $t->days_late }} jours de retard</span>
                            @endif
                        </div>
                    </div>
                    <form
                        action="{{ route('patients.traitement.renouveler', [$patient->id_patient, $t->id_traitement]) }}"
                        method="POST"
                        onsubmit="return confirm('Renouveler {{ $t->medication_name }} pour {{ $ancien->duration_days ?? 30 }} jours ?')">
                        @csrf
                        <button type="submit" class="btn-renouveler">
                            <iconify-icon icon="ph:arrows-counter-clockwise-bold"></iconify-icon> Renouveler
                        </button>
                    </form>
                </div>
            @empty
                <div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px;">Aucun traitement enregistré</div>
            @endforelse
        </div>

        {{-- ════════ TAB : MESURES ════════ --}}
        <div id="tab-mesures" style="display:none;">
            <div class="mb-3">
                <button class="btn-add-sm" onclick="togglePanel('add-mesure-panel')">
                    <iconify-icon icon="ph:plus-bold"></iconify-icon> Nouvelle mesure
                </button>
            </div>

            <div class="add-panel" id="add-mesure-panel">
                <form action="{{ route('patients.mesure.store', $patient->id_patient) }}" method="POST"
                    id="mesureForm">
                    @csrf

                    {{-- Type caché, mis à jour par JS --}}
                    <input type="hidden" name="type" id="mesure-type-input" value="PRESSION_ARTERIELLE">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="font-size:14px;font-weight:700;color:#0f172a;">Nouvelle mesure clinique</div>
                        <button type="button"
                            style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.2rem;"
                            onclick="togglePanel('add-mesure-panel')">
                            <iconify-icon icon="ph:x-bold"></iconify-icon>
                        </button>
                    </div>

                    {{-- Sélecteur de type --}}
                    <div class="mesure-type-grid">
                        <button type="button" class="mesure-type-btn active"
                            onclick="selectMesureType(this,'pa','PRESSION_ARTERIELLE')">
                            <iconify-icon icon="ph:heart-bold" class="mtype-icon" style="color:#c2185b;"></iconify-icon>
                            <span class="mtype-label">Pression Artérielle</span>
                        </button>
                        <button type="button" class="mesure-type-btn"
                            onclick="selectMesureType(this,'fc','FREQUENCE_CARDIAQUE')">
                            <iconify-icon icon="ph:activity-bold" class="mtype-icon"></iconify-icon>
                            <span class="mtype-label">Fréquence Cardiaque</span>
                        </button>
                        <button type="button" class="mesure-type-btn"
                            onclick="selectMesureType(this,'glyc','GLYCEMIE')">
                            <iconify-icon icon="ph:drop-bold" class="mtype-icon"></iconify-icon>
                            <span class="mtype-label">Glycémie à jeun</span>
                        </button>
                        <button type="button" class="mesure-type-btn"
                            onclick="selectMesureType(this,'poids','POIDS_IMC')">
                            <iconify-icon icon="ph:scales-bold" class="mtype-icon"></iconify-icon>
                            <span class="mtype-label">Poids & IMC</span>
                        </button>
                    </div>

                    {{-- ══ PA ══ --}}
                    <div id="mfields-pa">
                        <div class="info-bar red">
                            <iconify-icon icon="ph:info-bold"></iconify-icon>
                            Systolique / Diastolique · Au repos
                        </div>
                        @if ($pa)
                            <div class="last-mesure">
                                Dernière mesure : {{ $pa->systolic }}/{{ $pa->diastolic }} mmHg —
                                {{ \Carbon\Carbon::parse($pa->created_at)->format('d/m/Y') }}
                            </div>
                        @endif
                        <div class="form-row">
                            <div>
                                <div class="field-lbl">Systolique (mmHg)</div>
                                {{-- disabled quand inactif → non envoyé au serveur --}}
                                <input type="number" name="systolic" id="f-systolic" class="big-input"
                                    placeholder="120" min="50" max="300">
                            </div>
                            <div>
                                <div class="field-lbl">Diastolique (mmHg)</div>
                                <input type="number" name="diastolic" id="f-diastolic" class="big-input"
                                    placeholder="80" min="30" max="200">
                            </div>
                        </div>
                        <div class="field-lbl mt-2">Commentaire (optionnel)</div>
                        <input type="text" name="comment_pa" id="f-comment-pa" class="f-input"
                            placeholder="Ex: patient stressé">
                    </div>

                    {{-- ══ FC ══ --}}
                    <div id="mfields-fc" style="display:none;">
                        <div class="info-bar amber">
                            <iconify-icon icon="ph:info-bold"></iconify-icon>
                            Au repos · Normale : 60–100 bpm
                        </div>
                        @if ($fc)
                            <div class="last-mesure">
                                Dernière mesure : {{ $fc->heart_rate_bpm }} bpm —
                                {{ \Carbon\Carbon::parse($fc->created_at)->format('d/m/Y') }}
                            </div>
                        @endif
                        <div class="field-lbl">Fréquence cardiaque (bpm)</div>
                        <input type="number" name="heart_rate_bpm" id="f-heart-rate" class="big-input"
                            placeholder="72" min="30" max="250" readonly>
                        <div class="field-lbl mt-2">Commentaire (optionnel)</div>
                        <input type="text" name="comment_fc" id="f-comment-fc" class="f-input"
                            placeholder="Ex: après effort, stress..." readonly>
                    </div>

                    {{-- ══ GLYCÉMIE ══ --}}
                    <div id="mfields-glyc" style="display:none;">
                        <div class="info-bar amber">
                            <iconify-icon icon="ph:info-bold"></iconify-icon>
                            Mesure obligatoirement à jeun · Normale : &lt; 6,1 mmol/L
                        </div>
                        @if ($glyc)
                            <div class="last-mesure">
                                Dernière mesure : {{ $glyc->glycemia_mmol }} mmol/L —
                                {{ \Carbon\Carbon::parse($glyc->created_at)->format('d/m/Y') }}
                            </div>
                        @endif
                        <div class="field-lbl">Glycémie à jeun (mmol/L)</div>
                        <input type="number" step="0.1" name="glycemia_mmol" id="f-glycemia" class="big-input"
                            placeholder="5.5" min="1" max="50" readonly>
                        <div class="context-bar">
                            <iconify-icon icon="ph:check-bold"></iconify-icon>
                            Contexte : <strong>À jeun</strong> — Le patient ne doit pas avoir mangé depuis au moins 8h
                        </div>
                    </div>

                    {{-- ══ POIDS / IMC ══ --}}
                    <div id="mfields-poids" style="display:none;">
                        <div class="info-bar blue">
                            <iconify-icon icon="ph:info-bold"></iconify-icon>
                            Poids corporel + calcul automatique de l'IMC
                        </div>
                        @if ($poids)
                            <div class="last-mesure">
                                Dernière mesure : {{ $poids->weight_kg }} kg · IMC {{ $poids->imc }} —
                                {{ \Carbon\Carbon::parse($poids->created_at)->format('d/m/Y') }}
                            </div>
                        @endif
                        <div class="form-row">
                            <div>
                                <div class="field-lbl">Poids (kg)</div>
                                <input type="number" step="0.1" name="weight_kg" id="f-weight" class="big-input"
                                    placeholder="70" value="{{ $poids?->weight_kg ?? '' }}" readonly>
                            </div>
                            <div>
                                <div class="field-lbl">
                                    Taille
                                    (cm){{ $patient->height_cm ? ' · référence ' . $patient->height_cm . ' cm' : '' }}
                                </div>
                                <input type="number" name="height_cm" id="f-height" class="big-input"
                                    placeholder="170" value="{{ $patient->height_cm ?? '' }}" readonly>
                            </div>
                        </div>
                        {{-- IMC calculé en temps réel --}}
                        <div id="imc-preview" style="display:none; margin-top:10px;" class="context-bar">
                            <iconify-icon icon="ph:scales-bold"></iconify-icon>
                            IMC calculé : <strong id="imc-value">—</strong>
                            <span id="imc-label" style="margin-left:6px;"></span>
                        </div>
                    </div>

                    <div class="panel-btns mt-3">
                        <button type="button" class="btn-annuler"
                            onclick="togglePanel('add-mesure-panel')">Annuler</button>
                        <button type="submit" class="btn-enregistrer">
                            Enregistrer la mesure
                        </button>
                    </div>
                </form>
            </div>

            {{-- Graphiques --}}
            @php
                $paData = $mesures->where('type', 'PRESSION_ARTERIELLE')->sortBy('created_at');
                $fcData = $mesures->where('type', 'FREQUENCE_CARDIAQUE')->sortBy('created_at');
                $glycData = $mesures->where('type', 'GLYCEMIE')->sortBy('created_at');
                $poidsData = $mesures->where('type', 'POIDS_IMC')->sortBy('created_at');
            @endphp
            @foreach ([['pa-chart', 'ph:heart-bold', '#c2185b', 'Évolution Pression Artérielle'], ['fc-chart', 'ph:activity-bold', '#9333ea', 'Évolution Fréquence Cardiaque (Pouls)'], ['glyc-chart', 'ph:drop-bold', '#d97706', 'Évolution Glycémie à jeun'], ['poids-chart', 'ph:scales-bold', '#2563eb', 'Évolution Poids & IMC']] as [$cid, $icon, $color, $title])
                <div class="content-card mb-3">
                    <div class="chart-title">
                        <iconify-icon icon="{{ $icon }}" style="color:{{ $color }};"></iconify-icon>
                        {{ $title }}
                        @if ($cid === 'poids-chart' && $patient->height_cm)
                            <span style="font-size:11px;color:#94a3b8;font-weight:400;">· Taille :
                                {{ $patient->height_cm }} cm</span>
                        @endif
                    </div>
                    <canvas id="{{ $cid }}" height="100"></canvas>
                </div>
            @endforeach

            {{-- Historique --}}
            <div class="content-card">
                <div class="card-title mb-3">Historique des mesures</div>
                @forelse($mesures->sortByDesc('created_at') as $m)
                    @php
                        $mIcon = match ($m->type) {
                            'PRESSION_ARTERIELLE' => 'ph:heart-bold',
                            'FREQUENCE_CARDIAQUE' => 'ph:activity-bold',
                            'GLYCEMIE' => 'ph:drop-bold',
                            'POIDS_IMC' => 'ph:scales-bold',
                            default => 'ph:pulse-bold',
                        };
                        $mBg = match ($m->type) {
                            'PRESSION_ARTERIELLE' => '#fce4ec',
                            'FREQUENCE_CARDIAQUE' => '#fdf4ff',
                            'GLYCEMIE' => '#fff7ed',
                            'POIDS_IMC' => '#eff6ff',
                            default => '#f1f5f9',
                        };
                        $mCol = match ($m->type) {
                            'PRESSION_ARTERIELLE' => '#c2185b',
                            'FREQUENCE_CARDIAQUE' => '#9333ea',
                            'GLYCEMIE' => '#d97706',
                            'POIDS_IMC' => '#2563eb',
                            default => '#64748b',
                        };
                        $mLbl = match ($m->type) {
                            'PRESSION_ARTERIELLE' => 'Pression Artérielle',
                            'FREQUENCE_CARDIAQUE' => 'Pouls',
                            'GLYCEMIE' => 'Glycémie',
                            'POIDS_IMC' => 'Poids/IMC',
                            default => $m->type,
                        };
                        $mVal = match ($m->type) {
                            'PRESSION_ARTERIELLE' => $m->systolic . '/' . $m->diastolic . ' mmHg',
                            'FREQUENCE_CARDIAQUE' => $m->heart_rate_bpm . ' bpm',
                            'GLYCEMIE' => $m->glycemia_mmol . ' mmol/L' . ($m->is_fasting ? ' (à jeun)' : ''),
                            'POIDS_IMC' => $m->weight_kg . ' kg' . ($m->imc ? ' · IMC ' . $m->imc : ''),
                            default => '—',
                        };
                    @endphp
                    <div class="histo-row">
                        <div class="d-flex align-items-center gap-3">
                            <span class="histo-badge" style="background:{{ $mBg }};color:{{ $mCol }};">
                                <iconify-icon icon="{{ $mIcon }}"
                                    style="font-size:.85rem;margin-right:3px;"></iconify-icon>{{ $mLbl }}
                            </span>
                            <div>
                                <div class="histo-value">{{ $mVal }}</div>
                                <div class="histo-date">
                                    {{ \Carbon\Carbon::parse($m->created_at)->translatedFormat('j F Y \à H:i') }}</div>
                            </div>
                        </div>
                        @if ($m->comment)
                            <div class="histo-note">{{ $m->comment }}</div>
                        @endif
                    </div>
                @empty
                    <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">Aucune mesure enregistrée
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ════════ TAB : MESSAGES ════════ --}}
        <div id="tab-messages" style="display:none;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size:13px;font-weight:600;color:#64748b;">{{ $messages->count() }} message(s)</span>
                <button class="btn-add-sm" id="btn-send-msg" onclick="togglePanel('send-msg-panel')">
                    <iconify-icon icon="ph:plus-bold"></iconify-icon> Envoyer message
                </button>
            </div>

            <div class="add-panel" id="send-msg-panel">
                <form action="{{ route('patients.rappel.store', $patient->id_patient) }}" method="POST">
                    @csrf
                    <input type="hidden" name="channel" id="canal-input" value="WHATSAPP">
                    <input type="hidden" name="type" value="PERSONNALISE">
                    <div class="field-lbl mb-2">Canal d'envoi</div>
                    <div class="canal-toggle mb-3">
                        <button type="button" class="canal-btn active" id="canal-wa" onclick="setCanal('wa')">
                            <iconify-icon icon="ph:chat-circle-text-bold"></iconify-icon> WhatsApp
                        </button>
                        <button type="button" class="canal-btn" id="canal-sms" onclick="setCanal('sms')">
                            <iconify-icon icon="ph:device-mobile-bold"></iconify-icon> SMS
                        </button>
                    </div>
                    <div class="field-lbl">Message</div>
                    <textarea name="message" class="f-input" rows="3" id="msg-textarea" style="resize:none;"
                        oninput="updateCharCount()" placeholder="Bonjour {{ $patient->first_name }}, ..."></textarea>
                    <div style="text-align:right;font-size:11px;color:#94a3b8;margin-top:4px;" id="char-count">0
                        caractères</div>
                    <div class="panel-btns mt-2">
                        <button type="button" class="btn-annuler"
                            onclick="togglePanel('send-msg-panel')">Annuler</button>
                        <button type="submit" class="btn-enregistrer" style="display:flex;align-items:center;gap:6px;">
                            <iconify-icon icon="ph:paper-plane-tilt-bold"></iconify-icon> Envoyer
                        </button>
                    </div>
                </form>
            </div>

            @forelse($messages as $msg)
                <div class="msg-row">
                    <div class="msg-badges">
                        <span
                            class="msg-badge {{ $msg->channel === 'WHATSAPP' ? 'badge-wa' : 'badge-sms' }}">{{ $msg->channel }}</span>
                        <span class="msg-badge badge-trans">{{ ucfirst(strtolower($msg->type)) }}</span>
                        <span
                            class="msg-badge {{ $msg->status === 'LIVRE' ? 'badge-livre' : ($msg->status === 'ECHEC' ? 'badge-echec' : 'badge-trans') }}">{{ ucfirst(strtolower($msg->status)) }}</span>
                    </div>
                    <div class="msg-text">{{ $msg->message }}</div>
                    <div class="msg-time">{{ \Carbon\Carbon::parse($msg->created_at)->translatedFormat('j M. \à H:i') }}
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px;">Aucun message envoyé</div>
            @endforelse
        </div>

        {{-- ════════ TAB : RÉSEAU ════════ --}}
        <div id="tab-reseau" style="display:none;">
            <div class="content-card mb-3">
                <div class="card-title mb-3">
                    <iconify-icon icon="ph:graph-bold" style="color:#2563eb;"></iconify-icon>
                    Historique réseau pharmacies
                </div>
                @forelse($networkLogs as $log)
                    <div class="reseau-log">
                        <div class="reseau-icon"><iconify-icon icon="ph:graph-bold"></iconify-icon></div>
                        <div class="flex-grow-1">
                            <div class="reseau-action">{{ $log->action }}</div>
                            <div class="reseau-meta">{{ $log->pharmacy_name }} · {{ $log->pharmacien_name }}</div>
                        </div>
                        <div class="reseau-time">
                            {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('j M. Y, H:i') }}</div>
                    </div>
                @empty
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">Aucun accès réseau enregistré
                    </div>
                @endforelse
            </div>

            <div class="content-card">
                <div class="card-title mb-3">Pharmacies liées</div>
                @forelse($pharmaciesLiees as $ph)
                    <div class="pharma-linked {{ $ph->type === 'PRINCIPALE' ? 'main' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="pl-dot {{ $ph->type === 'PRINCIPALE' ? 'green' : '' }}"></div>
                            <div>
                                <div style="font-size:14px;font-weight:700;color:#0f172a;">{{ $ph->name }}</div>
                                <div style="font-size:12px;color:#94a3b8;">
                                    {{ $ph->address }}
                                    @if ($ph->shared_patients)
                                        · {{ $ph->shared_patients }} patients partagés
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="pl-badge {{ $ph->type === 'PRINCIPALE' ? 'main-b' : 'sec-b' }}">
                            {{ $ph->type === 'PRINCIPALE' ? 'Principale' : 'Secondaire' }}
                        </span>
                    </div>
                @empty
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px;">Aucune pharmacie affiliée
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ════════ MODAL BILAN ════════ --}}
    <div class="modal-overlay" id="bilan-modal">
        <div class="modal-box">
            <div class="modal-header">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="ph:file-text-bold" style="color:#16a34a;font-size:1.2rem;"></iconify-icon>
                        <span class="modal-title">Générer un bilan patient</span>
                    </div>
                    <div class="modal-sub">{{ $patient->first_name }}
                        {{ $patient->last_name }}{{ $patient->age ? ' · ' . $patient->age . ' ans' : '' }}</div>
                </div>
                <button class="modal-close" onclick="closeBilanModal()">
                    <iconify-icon icon="ph:x-bold"></iconify-icon>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-tabs">
                    <button class="modal-tab active" id="mtab-config" onclick="setBilanTab('config')">
                        <iconify-icon icon="ph:gear-bold"></iconify-icon> Configuration
                    </button>
                    <button class="modal-tab" id="mtab-apercu" onclick="setBilanTab('apercu')">
                        <iconify-icon icon="ph:eye-bold"></iconify-icon> Aperçu
                    </button>
                </div>
                <div id="bilan-config">
                    <div
                        style="font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                        <iconify-icon icon="ph:calendar-bold"></iconify-icon> Période du bilan
                    </div>
                    <div class="periode-chips">
                        <button class="periode-chip" onclick="setPeriode(this,'1 mois')">1 mois</button>
                        <button class="periode-chip active" onclick="setPeriode(this,'3 mois')">3 mois</button>
                        <button class="periode-chip" onclick="setPeriode(this,'6 mois')">6 mois</button>
                        <button class="periode-chip" onclick="setPeriode(this,'1 an')">1 an</button>
                        <button class="periode-chip" onclick="setPeriode(this,'Personnalisé')">Personnalisé</button>
                    </div>
                    <div class="form-row mb-4">
                        <div>
                            <div class="field-lbl">Du</div><input type="date" class="f-input"
                                value="{{ now()->subMonths(3)->format('Y-m-d') }}" id="bilan-du">
                        </div>
                        <div>
                            <div class="field-lbl">Au</div><input type="date" class="f-input"
                                value="{{ now()->format('Y-m-d') }}" id="bilan-au">
                        </div>
                    </div>
                    <div
                        style="font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;">
                        Sections à inclure</div>
                    @foreach ([['ph:activity-bold', 'Mesures cliniques', $mesures->count() . ' mesure(s)'], ['ph:pill-bold', 'Traitements', $traitements->count() . ' traitement(s)'], ['ph:warning-bold', 'Alertes cliniques', ''], ['ph:chat-circle-bold', 'Rappels envoyés', $messages->count() . ' rappel(s)']] as $s)
                        <div class="section-row">
                            <div class="section-info">
                                <iconify-icon icon="{{ $s[0] }}"
                                    style="font-size:1.1rem;color:#64748b;"></iconify-icon>
                                {{ $s[1] }} <span class="section-count">{{ $s[2] }}</span>
                            </div>
                            <button class="toggle-switch" onclick="this.classList.toggle('off')"></button>
                        </div>
                    @endforeach
                    <div style="margin-top:20px;">
                        <button class="btn-export" onclick="setBilanTab('apercu')">
                            <span style="display:flex;align-items:center;gap:8px;"><iconify-icon
                                    icon="ph:printer-bold"></iconify-icon> Exporter en PDF</span>
                            <span style="font-size:12px;opacity:.8;">Impression / Enregistrer</span>
                        </button>
                        <div class="transfer-label">Transférer le résumé</div>
                        <div class="transfer-btns">
                            <button class="transfer-btn"><iconify-icon icon="ph:chat-circle-text-bold"
                                    style="font-size:1.3rem;color:#16a34a;"></iconify-icon> WhatsApp</button>
                            <button class="transfer-btn selected"><iconify-icon icon="ph:envelope-bold"
                                    style="font-size:1.3rem;color:#2563eb;"></iconify-icon> Email</button>
                            <button class="transfer-btn"><iconify-icon icon="ph:copy-bold"
                                    style="font-size:1.3rem;color:#64748b;"></iconify-icon> Copier</button>
                        </div>
                    </div>
                </div>
                <div id="bilan-apercu" style="display:none;">
                    <div class="bilan-preview">
                        <div class="bilan-preview-label">Bilan de suivi patient</div>
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                            <div>
                                <div class="bilan-preview-name">{{ $patient->first_name }} {{ $patient->last_name }}
                                </div>
                                <div class="bilan-preview-sub">
                                    {{ $patient->age ? $patient->age . ' ans' : '' }}{{ $patient->city ? ' · ' . $patient->city : '' }}
                                </div>
                            </div>
                            <div style="text-align:right;font-size:11px;opacity:.75;">
                                <div>Période</div>
                                <div style="font-weight:700;" id="apercu-du">{{ now()->subMonths(3)->format('d M. Y') }}
                                </div>
                                <div>→ <span id="apercu-au">{{ now()->format('d M. Y') }}</span></div>
                            </div>
                        </div>
                        <div class="preview-stats">
                            <div class="preview-stat">
                                <div class="preview-stat-num">{{ $mesures->count() }}</div>
                                <div class="preview-stat-lbl">Mesures</div>
                            </div>
                            <div class="preview-stat">
                                <div class="preview-stat-num">
                                    {{ $mesures->whereIn('status_label', ['Critique'])->count() }}</div>
                                <div class="preview-stat-lbl">Critiques</div>
                            </div>
                            <div class="preview-stat">
                                <div class="preview-stat-num">{{ $traitements->count() }}</div>
                                <div class="preview-stat-lbl">Traitements</div>
                            </div>
                            <div class="preview-stat">
                                <div class="preview-stat-num">{{ $messages->count() }}</div>
                                <div class="preview-stat-lbl">Rappels</div>
                            </div>
                        </div>
                        <div class="bilan-footer">
                            <span>Généré le {{ now()->format('d M. Y') }}</span>
                            <span
                                style="background:rgba(255,255,255,.2);padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;">{{ $statusLabel }}</span>
                        </div>
                    </div>
                    <p style="font-size:12px;color:#94a3b8;text-align:center;margin-bottom:16px;">Le rapport PDF complet
                        inclura toutes les tables et graphiques détaillés.</p>
                    <button class="btn-export">
                        <span style="display:flex;align-items:center;gap:8px;"><iconify-icon
                                icon="ph:printer-bold"></iconify-icon> Exporter en PDF</span>
                        <span style="font-size:12px;opacity:.8;">Impression / Enregistrer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        /* ═══════════════════════════════════════════════════
       SCRIPT UNIFIÉ — view-patient-dynamic.blade.php
       Remplace TOUS les blocs <script> existants dans la vue
    ═══════════════════════════════════════════════════ */

        /* ── 1. TABS ── */
        const TABS = ['generale', 'pathologies', 'traitements', 'mesures', 'messages', 'reseau'];

        function switchTab(name) {
            TABS.forEach(t => {
                const el = document.getElementById('tab-' + t);
                if (el) el.style.display = (t === name) ? '' : 'none';
            });
            document.querySelectorAll('.tab-btn').forEach((btn, i) => {
                btn.classList.toggle('active', TABS[i] === name);
            });
            if (name === 'mesures') initCharts();
        }

        /* ── 2. PANELS (ouvrir/fermer) ── */
        function togglePanel(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('show');
        }

        function showAddTrait() {
            const p = document.getElementById('add-trait-panel');
            if (p && !p.classList.contains('show')) p.classList.add('show');
        }

        function showMesurePanel() {
            switchTab('mesures');
            const p = document.getElementById('add-mesure-panel');
            if (p && !p.classList.contains('show')) p.classList.add('show');
        }

        function showSendMsg() {
            switchTab('messages');
            const p = document.getElementById('send-msg-panel');
            if (p && !p.classList.contains('show')) p.classList.add('show');
        }

        /* ── 3. SÉLECTEUR TYPE MESURE ──
           Signature unifiée : selectMesureType(btn, section, typeValue)
           section   : 'pa' | 'fc' | 'glyc' | 'poids'
           typeValue : valeur envoyée au serveur
        */
        const MFIELDS = ['pa', 'fc', 'glyc', 'poids'];

        const FIELDS_MAP = {
            pa: ['f-systolic', 'f-diastolic', 'f-comment-pa'],
            fc: ['f-heart-rate', 'f-comment-fc'],
            glyc: ['f-glycemia'],
            poids: ['f-weight', 'f-height'],
        };

        const ALL_CONTROLLED = [
            'f-systolic', 'f-diastolic', 'f-comment-pa',
            'f-heart-rate', 'f-comment-fc',
            'f-glycemia',
            'f-weight', 'f-height',
        ];

        function selectMesureType(btn, section, typeValue) {
            // Bouton actif
            document.querySelectorAll('.mesure-type-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Mise à jour hidden type
            const hiddenType = document.getElementById('mesure-type-input');
            if (hiddenType) hiddenType.value = typeValue || section;

            // Affichage section
            MFIELDS.forEach(f => {
                const el = document.getElementById('mfields-' + f);
                if (el) el.style.display = (f === section) ? '' : 'none';
            });

            // Rendre TOUS les champs en lecture seule (mais envoyables au serveur)
            ALL_CONTROLLED.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = true;
            });

            // Activer l'écriture uniquement sur la section active
            (FIELDS_MAP[section] || []).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = false;
            });
        }

        /* ── 4. CANAL (WhatsApp / SMS) ── */
        function setCanal(c) {
            const wa = document.getElementById('canal-wa');
            const sms = document.getElementById('canal-sms');
            const inp = document.getElementById('canal-input');
            if (wa) wa.classList.toggle('active', c === 'wa');
            if (sms) sms.classList.toggle('active', c === 'sms');
            if (inp) inp.value = (c === 'wa') ? 'WHATSAPP' : 'SMS';
        }

        /* ── 5. COMPTEUR CARACTÈRES MESSAGE ── */
        function updateCharCount() {
            const ta = document.getElementById('msg-textarea');
            const cc = document.getElementById('char-count');
            if (!ta || !cc) return;
            const v = ta.value.length;
            cc.textContent = v + ' caractère' + (v > 1 ? 's' : '');
        }

        /* ── 6. MODAL BILAN ── */
        function openBilanModal() {
            document.getElementById('bilan-modal')?.classList.add('show');
        }

        function closeBilanModal() {
            document.getElementById('bilan-modal')?.classList.remove('show');
        }

        document.getElementById('bilan-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeBilanModal();
        });

        function setBilanTab(t) {
            document.getElementById('bilan-config').style.display = (t === 'config') ? '' : 'none';
            document.getElementById('bilan-apercu').style.display = (t === 'apercu') ? '' : 'none';
            document.getElementById('mtab-config').classList.toggle('active', t === 'config');
            document.getElementById('mtab-apercu').classList.toggle('active', t === 'apercu');
        }

        function setPeriode(btn, val) {
            document.querySelectorAll('.periode-chip').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            const today = new Date();
            const au = today.toISOString().slice(0, 10);
            const du = new Date(today);
            if (val === '1 mois') du.setMonth(du.getMonth() - 1);
            else if (val === '3 mois') du.setMonth(du.getMonth() - 3);
            else if (val === '6 mois') du.setMonth(du.getMonth() - 6);
            else if (val === '1 an') du.setFullYear(du.getFullYear() - 1);
            if (val !== 'Personnalisé') {
                const duEl = document.getElementById('bilan-du');
                const auEl = document.getElementById('bilan-au');
                if (duEl) duEl.value = du.toISOString().slice(0, 10);
                if (auEl) auEl.value = au;
            }
        }

        /* ── 7. IMC PREVIEW TEMPS RÉEL ── */
        function updateImcPreview() {
            const w = parseFloat(document.getElementById('f-weight')?.value);
            const h = parseFloat(document.getElementById('f-height')?.value);
            const preview = document.getElementById('imc-preview');
            if (!preview) return;
            if (w > 0 && h > 50) {
                const imc = (w / Math.pow(h / 100, 2)).toFixed(1);
                let label = '';
                if (imc < 18.5) label = '— Insuffisance pondérale';
                else if (imc < 25.0) label = '— Normal';
                else if (imc < 30.0) label = '— Surpoids';
                else if (imc < 35.0) label = '— Obésité modérée';
                else label = '— Obésité sévère';
                const iv = document.getElementById('imc-value');
                const il = document.getElementById('imc-label');
                if (iv) iv.textContent = imc;
                if (il) il.textContent = label;
                preview.style.display = '';
            } else {
                preview.style.display = 'none';
            }
        }

        document.getElementById('f-weight')?.addEventListener('input', updateImcPreview);
        document.getElementById('f-height')?.addEventListener('input', updateImcPreview);

        /* ── 8. GRAPHIQUES CHART.JS ── */
        let chartsInit = false;

        function initCharts() {
            if (chartsInit) return;
            chartsInit = true;

            const grid = '#f1f5f9';
            const base = {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: grid
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: grid
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                }
            };

            // PA
            const paLabels = @json($paData->map(fn($m) => \Carbon\Carbon::parse($m->created_at)->format('d M.'))->values());
            if (document.getElementById('pa-chart')) {
                new Chart(document.getElementById('pa-chart'), {
                    type: 'line',
                    data: {
                        labels: paLabels,
                        datasets: [{
                                data: @json($paData->pluck('systolic')->values()),
                                borderColor: '#ef4444',
                                backgroundColor: 'transparent',
                                tension: .3,
                                pointBackgroundColor: '#ef4444',
                                pointRadius: 5
                            },
                            {
                                data: @json($paData->pluck('diastolic')->values()),
                                borderColor: '#f97316',
                                backgroundColor: 'transparent',
                                tension: .3,
                                pointBackgroundColor: '#f97316',
                                pointRadius: 5
                            },
                        ]
                    },
                    options: {
                        ...base,
                        scales: {
                            ...base.scales,
                            y: {
                                ...base.scales.y,
                                min: 60,
                                max: 200
                            }
                        }
                    }
                });
            }

            // FC
            const fcLabels = @json($fcData->map(fn($m) => \Carbon\Carbon::parse($m->created_at)->format('d M.'))->values());
            if (document.getElementById('fc-chart')) {
                new Chart(document.getElementById('fc-chart'), {
                    type: 'line',
                    data: {
                        labels: fcLabels,
                        datasets: [{
                            data: @json($fcData->pluck('heart_rate_bpm')->values()),
                            borderColor: '#e879f9',
                            backgroundColor: 'transparent',
                            tension: .3,
                            pointBackgroundColor: '#e879f9',
                            pointRadius: 5
                        }]
                    },
                    options: {
                        ...base,
                        scales: {
                            ...base.scales,
                            y: {
                                ...base.scales.y,
                                min: 40,
                                max: 140
                            }
                        }
                    }
                });
            }

            // Glycémie
            const glycLabels = @json($glycData->map(fn($m) => \Carbon\Carbon::parse($m->created_at)->format('d M.'))->values());
            if (document.getElementById('glyc-chart')) {
                new Chart(document.getElementById('glyc-chart'), {
                    type: 'line',
                    data: {
                        labels: glycLabels,
                        datasets: [{
                            data: @json($glycData->pluck('glycemia_mmol')->values()),
                            borderColor: '#f59e0b',
                            backgroundColor: 'transparent',
                            tension: .3,
                            pointBackgroundColor: '#f59e0b',
                            pointRadius: 5
                        }]
                    },
                    options: {
                        ...base,
                        scales: {
                            ...base.scales,
                            y: {
                                ...base.scales.y,
                                min: 0,
                                max: 15
                            }
                        }
                    }
                });
            }

            // Poids
            const poidsLabels = @json($poidsData->map(fn($m) => \Carbon\Carbon::parse($m->created_at)->format('d M.'))->values());
            if (document.getElementById('poids-chart')) {
                new Chart(document.getElementById('poids-chart'), {
                    type: 'line',
                    data: {
                        labels: poidsLabels,
                        datasets: [{
                            data: @json($poidsData->pluck('weight_kg')->values()),
                            borderColor: '#3b82f6',
                            backgroundColor: 'transparent',
                            tension: .3,
                            pointBackgroundColor: '#3b82f6',
                            pointRadius: 5
                        }]
                    },
                    options: {
                        ...base,
                        scales: {
                            ...base.scales,
                            y: {
                                ...base.scales.y,
                                min: 0,
                                max: 100
                            }
                        }
                    }
                });
            }
        }

        /* ── 9. INITIALISATION AU CHARGEMENT ── */
       document.addEventListener('DOMContentLoaded', function() {

            // Passer les champs non-PA en lecture seule au chargement
            ['f-heart-rate', 'f-comment-fc', 'f-glycemia', 'f-weight', 'f-height'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.readOnly = true;
            });

            // Ouvre automatiquement le bon onglet si ancre dans l'URL
            const hash = window.location.hash;
            if (hash && hash.startsWith('#tab-')) {
                switchTab(hash.replace('#tab-', ''));
            }

            // Auto-dismiss flash messages
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
        });
    </script>

@endsection
