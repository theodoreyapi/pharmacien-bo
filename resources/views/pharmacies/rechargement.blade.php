@extends('layouts.master', ['title' => 'Rechargements'])

@section('content')
    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Rechargements</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ url('pharma-index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Tableau de bord
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Rechargements</li>
            </ul>
        </div>

        @include('layouts.statuts')

        {{-- Résumé --}}
        <div class="row gy-4 mb-24">
            <div class="col-md-4">
                <div class="card shadow-none border bg-gradient-start-4 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Total rechargé</p>
                                <h5 class="mb-0 fw-bold">
                                    {{ number_format($totalSuccess, 0, ',', ' ') }} FCFA
                                </h5>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-none border bg-gradient-start-1 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">Rechargements réussis</p>
                                <h5 class="mb-0 fw-bold">{{ $countSuccess }}</h5>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:check-circle-bold" class="text-white text-2xl"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-none border bg-gradient-start-2 h-100">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-medium text-primary-light mb-1">En attente / Échoués</p>
                                <h5 class="mb-0 fw-bold">{{ $countPending }} / {{ $countFailed }}</h5>
                            </div>
                            <div
                                class="w-50-px h-50-px bg-warning-main rounded-circle d-flex justify-content-center align-items-center">
                                <iconify-icon icon="ph:clock-bold" class="text-white text-2xl"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="card shadow-none border mb-24 p-16">
            <form method="GET" class="d-flex flex-wrap align-items-center gap-3">
                <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Réussi</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                </select>
                <select name="payment_method" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Tous les moyens</option>
                    <option value="wave" {{ request('payment_method') == 'wave' ? 'selected' : '' }}>Wave</option>
                    <option value="orange" {{ request('payment_method') == 'orange' ? 'selected' : '' }}>Orange Money
                    </option>
                    <option value="moov" {{ request('payment_method') == 'moov' ? 'selected' : '' }}>Moov Money
                    </option>
                    <option value="mtn" {{ request('payment_method') == 'mtn' ? 'selected' : '' }}>MTN MoMo</option>
                </select>
                <a href="{{ url('rechargements') }}" class="btn btn-sm btn-outline-secondary px-16 py-8 radius-8">
                    Réinitialiser
                </a>

                {{-- Bouton initier un rechargement --}}
                <div class="ms-auto">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#rechargeModal"
                        class="btn btn-primary px-16 py-8 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus"></iconify-icon>
                        Recharger mon wallet
                    </button>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card shadow-none border radius-12 p-0">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="fw-semibold mb-0">Historique des rechargements ({{ $rechargements->total() }})</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-base border-bottom">
                            <tr>
                                <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">#</th>
                                <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Montant</th>
                                <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Moyen</th>
                                <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Transaction ID</th>
                                <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Statut</th>
                                <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rechargements as $r)
                                <tr>
                                    <td class="px-24 py-16 text-sm text-secondary-light">{{ $r->id_rechargement }}</td>
                                    <td class="px-24 py-16 fw-bold text-sm text-success-main">
                                        +{{ number_format($r->montant, 0, ',', ' ') }} {{ $r->currency }}
                                    </td>
                                    <td class="px-24 py-16">
                                        @php
                                            $methodMap = [
                                                'wave' => ['color' => '#1E90FF', 'label' => 'Wave'],
                                                'orange' => ['color' => '#FF6600', 'label' => 'Orange Money'],
                                                'moov' => ['color' => '#00B140', 'label' => 'Moov Money'],
                                                'mtn' => ['color' => '#FFCC00', 'label' => 'MTN MoMo'],
                                            ];
                                            $m = $methodMap[$r->payment_method] ?? [
                                                'color' => '#6c757d',
                                                'label' => strtoupper($r->payment_method),
                                            ];
                                        @endphp
                                        <span class="px-12 py-4 rounded-pill text-xs fw-bold"
                                            style="background: {{ $m['color'] }}22; color: {{ $m['color'] }}; border: 1px solid {{ $m['color'] }}44;">
                                            {{ $m['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-24 py-16 text-sm text-secondary-light font-monospace">
                                        {{ $r->transaction_id ?? '—' }}
                                    </td>
                                    <td class="px-24 py-16">
                                        @php
                                            $statusMap = [
                                                'success' => [
                                                    'class' => 'bg-success-focus text-success-main',
                                                    'label' => '✓ Réussi',
                                                ],
                                                'pending' => [
                                                    'class' => 'bg-warning-focus text-warning-main',
                                                    'label' => '⏳ En attente',
                                                ],
                                                'failed' => [
                                                    'class' => 'bg-danger-focus text-danger-main',
                                                    'label' => '✗ Échoué',
                                                ],
                                            ];
                                            $s = $statusMap[$r->status] ?? [
                                                'class' => 'bg-neutral-focus text-neutral-main',
                                                'label' => $r->status,
                                            ];
                                        @endphp
                                        <span class="px-12 py-4 rounded-pill text-xs fw-semibold {{ $s['class'] }}">
                                            {{ $s['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-24 py-16 text-sm text-secondary-light">
                                        {{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-40 text-secondary-light">
                                        <iconify-icon icon="ph:wallet-duotone" style="font-size:48px"></iconify-icon>
                                        <p class="mt-12 mb-0">Aucun rechargement trouvé.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($rechargements->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-24 py-16 border-top">
                        <p class="text-sm text-secondary-light mb-0">
                            Page {{ $rechargements->currentPage() }} sur {{ $rechargements->lastPage() }}
                            — {{ $rechargements->total() }} rechargement(s)
                        </p>
                        <div class="d-flex gap-2">
                            @if ($rechargements->onFirstPage())
                                <button class="btn btn-sm btn-outline-secondary" disabled>Précédent</button>
                            @else
                                <a href="{{ $rechargements->previousPageUrl() }}"
                                    class="btn btn-sm btn-outline-primary">Précédent</a>
                            @endif
                            @if ($rechargements->hasMorePages())
                                <a href="{{ $rechargements->nextPageUrl() }}"
                                    class="btn btn-sm btn-outline-primary">Suivant</a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>Suivant</button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL RECHARGEMENT — Wave uniquement, autres grisés        --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="rechargeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border-bottom">
                    <h5 class="modal-title fw-semibold">Recharger mon wallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-24">

                    {{-- Montant --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Montant (FCFA) <span class="text-danger">*</span>
                        </label>
                        <input required type="number" id="recharge_amount" min="100" max="200000"
                            class="form-control radius-8 fw-bold" style="font-size: 20px; text-align: center;"
                            placeholder="Ex: 5000">
                        <small class="text-secondary-light">Min : 100 FCFA — Max : 200 000 FCFA</small>
                    </div>

                    {{-- Moyen de paiement --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Moyen de paiement <span class="text-danger">*</span>
                        </label>
                        <div class="row g-2">

                            {{-- Wave — seul actif --}}
                            <div class="col-6">
                                <input type="radio" name="payment_method" id="pm_wave" value="wave"
                                    class="d-none payment-radio" checked>
                                <label for="pm_wave"
                                    class="d-flex align-items-center justify-content-center gap-8 border radius-8 py-12 px-16 w-100 payment-label"
                                    style="cursor: pointer; border-color: #1E90FF; background: #e8f3ff;">
                                    <span class="w-12-px h-12-px rounded-circle"
                                        style="background: #1E90FF; flex-shrink:0;"></span>
                                    <span class="fw-semibold text-sm" style="color: #1E90FF;">Wave</span>
                                </label>
                            </div>

                            {{-- Autres grisés --}}
                            @foreach ([['value' => 'orange', 'label' => 'Orange Money', 'color' => '#FF6600'], ['value' => 'moov', 'label' => 'Moov Money', 'color' => '#00B140'], ['value' => 'mtn', 'label' => 'MTN MoMo', 'color' => '#FFCC00']] as $method)
                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-center gap-8 border radius-8 py-12 px-16 w-100 position-relative"
                                        style="cursor: not-allowed; opacity: 0.45; background: #f5f5f5; border-color: #dee2e6;">
                                        <span class="w-12-px h-12-px rounded-circle"
                                            style="background: {{ $method['color'] }}; flex-shrink:0;"></span>
                                        <span
                                            class="fw-semibold text-sm text-secondary-light">{{ $method['label'] }}</span>
                                        <span class="position-absolute top-0 end-0 mt-4 me-6"
                                            style="font-size: 9px; color: #999; background: #eee; padding: 1px 5px; border-radius: 4px; line-height: 1.4;">
                                            Bientôt
                                        </span>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- Erreur --}}
                    <div id="rechargeError" class="alert alert-danger py-10 px-16 radius-8 text-sm mb-16"
                        style="display:none;"></div>

                    {{-- Actions --}}
                    <div class="d-flex gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8 flex-fill">
                            Annuler
                        </button>
                        <button type="button" id="rechargeBtn" onclick="lancerRechargement()"
                            class="btn btn-primary text-md px-40 py-12 radius-8 flex-fill d-flex align-items-center justify-content-center gap-8">
                            <iconify-icon icon="ph:wave-sawtooth-bold"></iconify-icon>
                            Payer via Wave
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        async function lancerRechargement() {
            const amount = document.getElementById('recharge_amount').value;
            const btn = document.getElementById('rechargeBtn');
            const errDiv = document.getElementById('rechargeError');
            errDiv.style.display = 'none';

            // Validation côté client
            if (!amount || parseFloat(amount) < 100) {
                errDiv.textContent = 'Veuillez saisir un montant minimum de 100 FCFA.';
                errDiv.style.display = 'block';
                return;
            }
            if (parseFloat(amount) > 200000) {
                errDiv.textContent = 'Le montant ne peut pas dépasser 200 000 FCFA.';
                errDiv.style.display = 'block';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-8"></span>Connexion à Wave...';

            try {
                const response = await fetch('{{ url('rechargements/initier') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        username: '{{ Auth::guard('pharmacien')->user()->username }}',
                        deposit_amount: parseFloat(amount),
                        payment_method: 'wave',
                    }),
                });

                const data = await response.json();

                if (response.ok && data.success && data.rechargement_url) {
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-8"></span>Ouverture Wave...';

                    // Ouvrir Wave dans un popup centré
                    const popupWidth = 500;
                    const popupHeight = 700;
                    const left = Math.round((window.screen.width - popupWidth) / 2);
                    const top = Math.round((window.screen.height - popupHeight) / 2);

                    const popup = window.open(
                        data.rechargement_url,
                        'WavePayment',
                        `width=${popupWidth},height=${popupHeight},left=${left},top=${top},toolbar=no,menubar=no,scrollbars=yes,resizable=no`
                    );

                    // Si le popup est bloqué par le navigateur → ouvrir un nouvel onglet
                    if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                        window.open(data.rechargement_url, '_blank');
                    }

                    // Surveiller la fermeture du popup pour recharger la page
                    const checkClosed = setInterval(() => {
                        if (popup && popup.closed) {
                            clearInterval(checkClosed);
                            window.location.reload();
                        }
                    }, 1000);

                    btn.disabled = false;
                    btn.innerHTML = '<iconify-icon icon="ph:wave-sawtooth-bold"></iconify-icon> Payer via Wave';
                } else {
                    // Afficher les erreurs de validation ou serveur
                    const msg = Array.isArray(data.message) ?
                        data.message.join('<br>') :
                        (data.message ?? 'Une erreur est survenue.');
                    errDiv.innerHTML = msg;
                    errDiv.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = '<iconify-icon icon="ph:wave-sawtooth-bold"></iconify-icon> Payer via Wave';
                }
            } catch (err) {
                errDiv.textContent = 'Erreur de connexion. Veuillez réessayer.';
                errDiv.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<iconify-icon icon="ph:wave-sawtooth-bold"></iconify-icon> Payer via Wave';
            }
        }
    </script>

    <style>
        .payment-radio:checked+.payment-label {
            border-color: #4f46e5;
            background: #eef2ff;
            color: #4f46e5;
        }
    </style>

    <script>
        document.querySelectorAll('.payment-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.payment-label').forEach(l => {
                    l.style.borderColor = '';
                    l.style.background = '';
                });
                if (radio.checked) {
                    radio.nextElementSibling.style.borderColor = '#4f46e5';
                    radio.nextElementSibling.style.background = '#eef2ff';
                }
            });
        });
    </script>
@endsection
