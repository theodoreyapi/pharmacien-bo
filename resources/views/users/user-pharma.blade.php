@extends('layouts.master', ['title' => 'Équipe'])

@push('scripts')
    <script>
        // DataTable retiré — on utilise notre propre UI
    </script>
@endpush

@section('content')

    <style>
        .dash-body {
            padding: 28px;
            min-height: 100%;
        }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 2px;
        }

        .page-sub {
            font-size: 13px;
            color: #94a3b8;
            margin: 0;
        }

        .btn-invite {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 20px;
            border-radius: 12px;
            border: none;
            background: #16a34a;
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .15s;
            white-space: nowrap;
        }

        .btn-invite:hover {
            background: #15803d;
        }

        /* ── Invite panel ── */
        .invite-panel {
            background: white;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 14px;
            border: 1.5px solid #d1fae5;
            display: none;
        }

        .invite-panel.show {
            display: block;
        }

        .invite-panel-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
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

        /* Rôle toggle */
        .role-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .role-btn {
            padding: 11px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: 'DM Sans', sans-serif;
        }

        .role-btn:hover {
            border-color: #94a3b8;
        }

        .role-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .panel-footer {
            display: flex;
            justify-content: flex-end;
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
        }

        .btn-annuler:hover {
            background: #f8fafc;
        }

        .btn-envoyer {
            padding: 10px 22px;
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

        .btn-envoyer:hover {
            background: #15803d;
        }

        /* ── Section cards ── */
        .section-card {
            background: white;
            border-radius: 18px;
            padding: 22px 24px;
            margin-bottom: 14px;
        }

        .section-label {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: .04em;
            margin-bottom: 16px;
        }

        .section-label.inactive {
            color: #cbd5e1;
        }

        /* ── Member rows ── */
        .member-row {
            display: flex;
            align-items: center;
            padding: 14px 0;
            gap: 14px;
            border-bottom: 1px solid #f8fafc;
            transition: background .15s;
        }

        .member-row:last-child {
            border-bottom: none;
        }

        .m-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            background: #d1fae5;
            color: #065f46;
        }

        .m-avatar.inactive {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .m-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 3px;
        }

        .m-name.inactive {
            color: #94a3b8;
        }

        .m-contact {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Role badges */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .rb-pharmacien {
            background: #ecfdf5;
            color: #16a34a;
        }

        .rb-assistant {
            background: #eff6ff;
            color: #2563eb;
        }

        .rb-support {
            background: #fefce8;
            color: #ca8a04;
        }

        .rb-proprio {
            background: #faf5ff;
            color: #9333ea;
        }

        /* Last seen */
        .m-last {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #94a3b8;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .online-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #16a34a;
            flex-shrink: 0;
        }

        /* Inactive actions */
        .m-actions {
            margin-left: auto;
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-inactif {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            cursor: default;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-reactiver {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
        }

        .btn-reactiver:hover {
            border-color: #16a34a;
            color: #16a34a;
            background: #f0fdf4;
        }

        /* ── Rôles & permissions ── */
        .roles-section {
            margin-top: 4px;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 32px;
        }

        .roles-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .role-perm-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .rp-badge {
            display: inline-flex;
            padding: 3px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            min-width: 90px;
            justify-content: center;
        }

        .rp-desc {
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 767.98px) {
            .dash-body {
                padding: 16px;
            }

            .role-group {
                grid-template-columns: 1fr;
            }

            .roles-grid {
                grid-template-columns: 1fr;
            }

            .m-last {
                display: none;
            }
        }
    </style>

    <div class="dash-body">

        @include('layouts.statuts')

        {{-- ── Page header ── --}}
        <div class="page-header">
            <div>
                <h5 class="page-title">Membres de l'équipe</h5>
                <p class="page-sub">{{ $admins->where('active', 'ACTIVE')->count() }} actifs</p>
            </div>
            <button class="btn-invite" onclick="toggleInvitePanel()">
                <iconify-icon icon="ph:plus-bold"></iconify-icon> Inviter un membre
            </button>
        </div>

        {{-- ── Invite panel ── --}}
        <div class="invite-panel" id="invite-panel">
            <div class="invite-panel-title">Inviter un nouveau membre</div>

            <form action="{{ route('equipes.store') }}" method="post">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Nom</div>
                        <input type="text" name="firstname" required class="f-input" placeholder="NOM">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Prénom</div>
                        <input type="text" name="lastname" required class="f-input" placeholder="Prénom">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Téléphone</div>
                        <input type="text" name="phone" class="f-input" placeholder="+225 07 00 00 00 00">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Email</div>
                        <input type="email" name="email" required class="f-input" placeholder="email@pharmacie.ci">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="field-lbl">Mot de passe</div>
                        <input type="password" name="password" class="f-input" placeholder="••••••••">
                    </div>
                </div>

                <div class="field-lbl mb-2">Rôle</div>
                <div class="role-group mb-1" id="role-group">
                    <button type="button" class="role-btn" onclick="setRole(this,'PHARMACIEN')">
                        <iconify-icon icon="ph:user-circle-bold"></iconify-icon> Pharmacien
                    </button>
                    <button type="button" class="role-btn active" onclick="setRole(this,'ASSISTANT')">
                        <iconify-icon icon="ph:user-bold"></iconify-icon> Assistant(e)
                    </button>
                    <button type="button" class="role-btn" onclick="setRole(this,'SUPPORT')">
                        <iconify-icon icon="ph:headset-bold"></iconify-icon> Support
                    </button>
                </div>
                <input type="hidden" name="profil" id="selected-role" value="ASSISTANT">

                <div class="panel-footer">
                    <button type="button" class="btn-annuler" onclick="toggleInvitePanel()">Annuler</button>
                    <button type="submit" class="btn-envoyer">Envoyer l'invitation</button>
                </div>
            </form>
        </div>

        {{-- ── Membres actifs ── --}}
        <div class="section-card">
            <div class="section-label">Membres actifs</div>

            @foreach ($admins->where('active', 'ACTIVE') as $item)
                <div class="member-row">
                    <div class="m-avatar">
                        {{ strtoupper(substr($item->first_name, 0, 1)) }}{{ strtoupper(substr($item->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="m-name">
                            {{ $item->first_name }} {{ $item->last_name }}
                            @if ($item->role === 'PHARMACIEN')
                                <span class="role-badge rb-pharmacien">
                                    <iconify-icon icon="ph:user-circle-bold" style="font-size:.85rem;"></iconify-icon>
                                    Pharmacien
                                </span>
                            @elseif($item->role === 'GESTIONNAIRE')
                                <span class="role-badge rb-assistant">
                                    <iconify-icon icon="ph:user-bold" style="font-size:.85rem;"></iconify-icon> Gestionnaire
                                </span>
                            @else
                                <span class="role-badge rb-support">
                                    <iconify-icon icon="ph:headset-bold" style="font-size:.85rem;"></iconify-icon>
                                    Caissier(e)
                                </span>
                            @endif
                        </div>
                        <div class="m-contact">
                            {{ $item->phone_number }}
                            @if ($item->email)
                                · {{ $item->email }}
                            @endif
                        </div>
                    </div>
                    <div class="m-last">
                        <iconify-icon icon="ph:clock-bold" style="font-size:.9rem;"></iconify-icon>
                        <span>12 mars, 07:45</span>
                        <div class="online-dot"></div>
                    </div>

                    {{-- Actions edit/delete --}}
                    <div style="display:flex;gap:6px;margin-left:12px;">
                        <a href="javascript:void(0)" class="d-inline-flex align-items-center justify-content-center"
                            style="width:32px;height:32px;border-radius:50%;background:#ecfdf5;color:#16a34a;"
                            data-bs-toggle="modal" data-bs-target="#edit{{ $item->id_pharmacien }}">
                            <iconify-icon icon="ph:pencil-bold"></iconify-icon>
                        </a>
                        <a href="javascript:void(0)" class="d-inline-flex align-items-center justify-content-center"
                            style="width:32px;height:32px;border-radius:50%;background:#fef2f2;color:#dc2626;"
                            data-bs-toggle="modal" data-bs-target="#delete{{ $item->id_pharmacien }}">
                            <iconify-icon icon="ph:trash-bold"></iconify-icon>
                        </a>
                    </div>
                </div>

                {{-- Modal Edit --}}
                <div class="modal fade" id="edit{{ $item->id_pharmacien }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content" style="border-radius:16px;border:none;">
                            <div class="modal-header"
                                style="background:#16a34a;border-radius:16px 16px 0 0;padding:16px 24px;">
                                <h5 class="modal-title text-white fw-bold" style="font-size:15px;">Modifier le membre</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form action="{{ route('equipes.update', $item->id_pharmacien) }}" method="post">
                                    @csrf @method('PATCH')
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="field-lbl">Nom</div>
                                            <input type="text" name="firstname" required class="f-input"
                                                value="{{ $item->first_name }}">
                                        </div>
                                        <div class="col-6">
                                            <div class="field-lbl">Prénom</div>
                                            <input type="text" name="lastname" required class="f-input"
                                                value="{{ $item->last_name }}">
                                        </div>
                                        <div class="col-6">
                                            <div class="field-lbl">Email</div>
                                            <input type="email" name="email" required class="f-input"
                                                value="{{ $item->email }}">
                                        </div>
                                        <div class="col-6">
                                            <div class="field-lbl">Téléphone</div>
                                            <input type="text" name="phone" class="f-input"
                                                value="{{ $item->phone_number }}">
                                        </div>
                                        <div class="col-6">
                                            <div class="field-lbl">Mot de passe</div>
                                            <input type="password" name="password" class="f-input"
                                                placeholder="Laisser vide pour ne pas changer">
                                        </div>
                                        <div class="col-6">
                                            <div class="field-lbl">Profil</div>
                                            <select name="profil" required class="f-input" style="appearance:none;">
                                                <option @if ($item->role == 'PHARMACIEN') selected @endif
                                                    value="PHARMACIEN">Pharmacien</option>
                                                <option @if ($item->role == 'GESTIONNAIRE') selected @endif
                                                    value="GESTIONNAIRE">Gestionnaire</option>
                                                <option @if ($item->role == 'CAISSIERE') selected @endif
                                                    value="CAISSIERE">Caissier(e)</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <div class="field-lbl">Statut</div>
                                            <select name="active" required class="f-input" style="appearance:none;">
                                                <option @if ($item->active == 'ACTIVE') selected @endif value="ACTIVE">
                                                    Actif</option>
                                                <option @if ($item->active == 'INACTIVE') selected @endif value="INACTIVE">
                                                    Inactif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <button type="button" class="btn-annuler"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn-envoyer">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Delete --}}
                <div class="modal fade" id="delete{{ $item->id_pharmacien }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
                        <div class="modal-content" style="border-radius:16px;border:none;">
                            <div class="modal-header"
                                style="background:#dc2626;border-radius:16px 16px 0 0;padding:16px 24px;">
                                <h5 class="modal-title text-white fw-bold" style="font-size:15px;">Supprimer le membre
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p style="font-size:14px;color:#334155;">Êtes-vous sûr de vouloir supprimer
                                    <strong>{{ $item->first_name }} {{ $item->last_name }}</strong> ?<br><span
                                        style="color:#94a3b8;font-size:12px;">Cette action est irréversible.</span></p>
                                <form action="{{ route('equipes.destroy', $item->id_pharmacien) }}" method="post">
                                    @csrf @method('DELETE')
                                    <div class="panel-footer">
                                        <button type="button" class="btn-annuler"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit"
                                            style="padding:10px 22px;border-radius:11px;border:none;background:#dc2626;color:white;font-size:13px;font-weight:600;cursor:pointer;">Supprimer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Membres inactifs ── --}}
        @if ($admins->where('active', 'INACTIVE')->count() > 0)
            <div class="section-card">
                <div class="section-label inactive">Membres inactifs</div>

                @foreach ($admins->where('active', 'INACTIVE') as $item)
                    <div class="member-row">
                        <div class="m-avatar inactive">
                            {{ strtoupper(substr($item->first_name, 0, 1)) }}{{ strtoupper(substr($item->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="m-name inactive">{{ $item->first_name }} {{ $item->last_name }}</div>
                            <div class="m-contact" style="font-size:12px;color:#cbd5e1;">
                                @if ($item->role === 'PHARMACIEN')
                                    Pharmacien
                                @elseif($item->role === 'GESTIONNAIRE')
                                    Gestionnaire
                                @else
                                    Assistant(e)
                                @endif
                            </div>
                        </div>
                        <div class="m-actions">
                            <span class="btn-inactif">Inactif</span>
                            <form action="{{ route('equipes.update', $item->id_pharmacien) }}" method="post"
                                style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="firstname" value="{{ $item->first_name }}">
                                <input type="hidden" name="lastname" value="{{ $item->last_name }}">
                                <input type="hidden" name="email" value="{{ $item->email }}">
                                <input type="hidden" name="profil" value="{{ $item->role }}">
                                <input type="hidden" name="active" value="ACTIVE">
                                <button type="submit" class="btn-reactiver">Réactiver</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ── Rôles et permissions ── --}}
        <div class="section-card roles-section">
            <div class="roles-label">Rôles et permissions</div>
            <div class="roles-grid">
                <div>
                    <div class="role-perm-row">
                        <span class="rp-badge" style="background:#faf5ff;color:#9333ea;">Propriétaire</span>
                        <span class="rp-desc">Accès total</span>
                    </div>
                    <div class="role-perm-row">
                        <span class="rp-badge" style="background:#eff6ff;color:#2563eb;">Assistant(e)</span>
                        <span class="rp-desc">Accueil + saisies</span>
                    </div>
                </div>
                <div>
                    <div class="role-perm-row">
                        <span class="rp-badge" style="background:#ecfdf5;color:#16a34a;">Pharmacien</span>
                        <span class="rp-desc">Dossiers + mesures</span>
                    </div>
                    <div class="role-perm-row">
                        <span class="rp-badge" style="background:#fffbeb;color:#d97706;">Support</span>
                        <span class="rp-desc">Lecture seule</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        let inviteOpen = false;

        function toggleInvitePanel() {
            inviteOpen = !inviteOpen;
            document.getElementById('invite-panel').classList.toggle('show', inviteOpen);
            if (inviteOpen) {
                document.getElementById('invite-panel').scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }

        function setRole(btn, role) {
            document.querySelectorAll('#role-group .role-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('selected-role').value = role;
        }
    </script>

@endsection
