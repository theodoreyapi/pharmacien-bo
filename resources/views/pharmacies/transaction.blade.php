@extends('layouts.master', ['title' => 'Transactions'])

@section('content')
<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Transactions</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ url('pharma-index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Tableau de bord
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Transactions</li>
        </ul>
    </div>

    @include('layouts.statuts')

    {{-- Solde --}}
    <div class="row gy-4 mb-24">
        <div class="col-md-4">
            <div class="card shadow-none border bg-gradient-start-4 h-100">
                <div class="card-body p-20">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <p class="fw-medium text-primary-light mb-1">Solde actuel</p>
                            <h5 class="mb-0 fw-bold">{{ number_format($pharmacien->amount ?? 0, 0, ',', ' ') }} FCFA</h5>
                        </div>
                        <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
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
                            <p class="fw-medium text-primary-light mb-1">Total reçu</p>
                            <h5 class="mb-0 fw-bold text-success-main">
                                {{ number_format($totalCredit, 0, ',', ' ') }} FCFA
                            </h5>
                        </div>
                        <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="ph:arrow-down-bold" class="text-white text-2xl"></iconify-icon>
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
                            <p class="fw-medium text-primary-light mb-1">Total envoyé</p>
                            <h5 class="mb-0 fw-bold text-danger-main">
                                {{ number_format($totalDebit, 0, ',', ' ') }} FCFA
                            </h5>
                        </div>
                        <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="ph:arrow-up-bold" class="text-white text-2xl"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des transactions --}}
    <div class="card shadow-none border radius-12 p-0">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="fw-semibold mb-0">Historique ({{ $transactions->total() }})</h6>
            <div class="d-flex gap-2 align-items-center">
                <form method="GET" class="d-flex gap-2">
                    <select name="type_operation" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        <option value="CREDIT" {{ request('type_operation') == 'CREDIT' ? 'selected' : '' }}>Crédit</option>
                        <option value="DEBIT"  {{ request('type_operation') == 'DEBIT'  ? 'selected' : '' }}>Débit</option>
                    </select>
                </form>
                <button type="button" data-bs-toggle="modal" data-bs-target="#transferModal"
                    class="btn btn-primary btn-sm px-16 py-8 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ph:arrows-left-right-bold"></iconify-icon>
                    Transférer
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-base border-bottom">
                        <tr>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Type</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Montant</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Interlocuteur</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Catégorie</th>
                            <th class="px-24 py-16 text-sm fw-semibold text-secondary-light">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr>
                            <td class="px-24 py-16">
                                @if($t['typeOperation'] === 'CREDIT')
                                    <div class="d-flex align-items-center gap-8">
                                        <div class="w-32-px h-32-px bg-success-focus rounded-circle d-flex align-items-center justify-content-center">
                                            <iconify-icon icon="ph:arrow-down-bold" class="text-success-main"></iconify-icon>
                                        </div>
                                        <span class="fw-semibold text-success-main text-sm">CRÉDIT</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-8">
                                        <div class="w-32-px h-32-px bg-danger-focus rounded-circle d-flex align-items-center justify-content-center">
                                            <iconify-icon icon="ph:arrow-up-bold" class="text-danger-main"></iconify-icon>
                                        </div>
                                        <span class="fw-semibold text-danger-main text-sm">DÉBIT</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-24 py-16 fw-bold text-sm {{ $t['typeOperation'] === 'CREDIT' ? 'text-success-main' : 'text-danger-main' }}">
                                {{ $t['typeOperation'] === 'CREDIT' ? '+' : '-' }}
                                {{ $t['amount'] ? number_format($t['amount'], 0, ',', ' ') . ' FCFA' : '—' }}
                            </td>
                            <td class="px-24 py-16 text-sm text-primary-light">{{ $t['interlocuteurNom'] ?? '—' }}</td>
                            <td class="px-24 py-16">
                                @php
                                    $catMap = [
                                        'TRANSFERT'    => ['class' => 'bg-info-focus text-info-main',       'icon' => 'ph:arrows-left-right-bold'],
                                        'RECHARGEMENT' => ['class' => 'bg-success-focus text-success-main', 'icon' => 'ph:plus-circle-bold'],
                                        'ABONNEMENT'   => ['class' => 'bg-warning-focus text-warning-main', 'icon' => 'ph:star-bold'],
                                    ];
                                    $cat = $catMap[$t['category']] ?? ['class' => 'bg-neutral-focus text-neutral-main', 'icon' => 'ph:circle-bold'];
                                @endphp
                                <span class="px-10 py-4 rounded-pill text-xs fw-semibold d-inline-flex align-items-center gap-4 {{ $cat['class'] }}">
                                    <iconify-icon icon="{{ $cat['icon'] }}"></iconify-icon>
                                    {{ $t['category'] }}
                                </span>
                            </td>
                            <td class="px-24 py-16 text-sm text-secondary-light">
                                {{ \Carbon\Carbon::parse($t['date'])->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-40 text-secondary-light">
                                <iconify-icon icon="ph:receipt-duotone" style="font-size:48px"></iconify-icon>
                                <p class="mt-12 mb-0">Aucune transaction trouvée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
            <div class="d-flex justify-content-between align-items-center px-24 py-16 border-top">
                <p class="text-sm text-secondary-light mb-0">
                    Page {{ $transactions->currentPage() }} sur {{ $transactions->lastPage() }}
                </p>
                <div class="d-flex gap-2">
                    @if($transactions->onFirstPage())
                        <button class="btn btn-sm btn-outline-secondary" disabled>Précédent</button>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">Précédent</a>
                    @endif
                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">Suivant</a>
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

{{-- ============================================================ --}}
{{-- MODAL TRANSFERT                                              --}}
{{-- ============================================================ --}}
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border-bottom">
                <h5 class="modal-title fw-semibold">
                    <iconify-icon icon="ph:arrows-left-right-bold" class="me-8"></iconify-icon>
                    Transférer de l'argent
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-24">
                <form id="transferForm">
                    @csrf

                    {{-- Numéro destinataire --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Numéro du destinataire <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <input required type="text" id="receiverUsername"
                                class="form-control radius-8 pe-40"
                                placeholder="Ex: 002250XXXXXXXXX"
                                pattern="[0-9]+"
                                title="Numéro de téléphone uniquement">
                            <iconify-icon icon="ph:user-circle-bold"
                                class="position-absolute text-secondary-light"
                                style="right: 12px; top: 50%; transform: translateY(-50%); font-size: 18px;">
                            </iconify-icon>
                        </div>
                        <small class="text-secondary-light">Si le compte n'existe pas, il sera créé automatiquement.</small>
                    </div>

                    {{-- Montant --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Montant (FCFA) <span class="text-danger">*</span>
                        </label>
                        <input required type="number" id="transferAmount"
                            min="1" max="{{ $pharmacien->amount ?? 0 }}"
                            class="form-control radius-8 fw-bold"
                            style="font-size: 20px; text-align: center;"
                            placeholder="Ex: 5000">
                        <div class="d-flex justify-content-between mt-6">
                            <small class="text-secondary-light">Solde disponible :</small>
                            <small class="fw-semibold text-success-main">
                                {{ number_format($pharmacien->amount ?? 0, 0, ',', ' ') }} FCFA
                            </small>
                        </div>
                    </div>

                    {{-- Raison --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                            Motif (optionnel)
                        </label>
                        <input type="text" id="transferRaison"
                            class="form-control radius-8"
                            placeholder="Ex: Paiement consultation...">
                    </div>

                    {{-- Résumé --}}
                    <div id="transferSummary" class="p-16 radius-8 mb-20" style="background: #f0f4ff; display:none;">
                        <p class="text-sm fw-semibold text-primary-light mb-4">Récapitulatif :</p>
                        <p class="text-sm text-secondary-light mb-2">
                            <span class="fw-medium">Destinataire :</span>
                            <span id="summaryReceiver">—</span>
                        </p>
                        <p class="text-sm text-secondary-light mb-0">
                            <span class="fw-medium">Montant :</span>
                            <span id="summaryAmount" class="fw-bold text-primary-600">—</span>
                        </p>
                    </div>

                    {{-- Erreur --}}
                    <div id="transferError" class="alert alert-danger py-10 px-16 radius-8 text-sm mb-20" style="display:none;"></div>

                    <div class="d-flex gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8 flex-fill">
                            Annuler
                        </button>
                        <button type="submit" id="transferBtn"
                            class="btn btn-primary text-md px-40 py-12 radius-8 flex-fill d-flex align-items-center justify-content-center gap-8">
                            <iconify-icon icon="ph:paper-plane-right-bold"></iconify-icon>
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Afficher le résumé en temps réel
    const receiverInput = document.getElementById('receiverUsername');
    const amountInput   = document.getElementById('transferAmount');
    const summary       = document.getElementById('transferSummary');

    [receiverInput, amountInput].forEach(el => {
        el.addEventListener('input', updateSummary);
    });

    function updateSummary() {
        const receiver = receiverInput.value.trim();
        const amount   = parseFloat(amountInput.value);
        if (receiver.length >= 5 && amount > 0) {
            document.getElementById('summaryReceiver').textContent = receiver;
            document.getElementById('summaryAmount').textContent   = amount.toLocaleString('fr-FR') + ' FCFA';
            summary.style.display = 'block';
        } else {
            summary.style.display = 'none';
        }
    }

    // Soumission du formulaire
    document.getElementById('transferForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn    = document.getElementById('transferBtn');
        const errDiv = document.getElementById('transferError');
        errDiv.style.display = 'none';

        const receiver = receiverInput.value.trim();
        const amount   = parseFloat(amountInput.value);
        const raison   = document.getElementById('transferRaison').value.trim();

        if (!receiver || amount <= 0) {
            errDiv.textContent     = 'Veuillez remplir tous les champs obligatoires.';
            errDiv.style.display   = 'block';
            return;
        }

        btn.disabled      = true;
        btn.innerHTML     = '<span class="spinner-border spinner-border-sm me-8"></span>Envoi en cours...';

        try {
            const response = await fetch('{{ env("API_BASE_URL", "") }}/pharma/transfers/process', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    senderUsername:   '{{ Auth::guard("pharmacien")->user()->username }}',
                    receiverUsername: receiver,
                    amount:           amount,
                    type:             'pharmacy_to_user',
                    executeBy:        '{{ Auth::guard("pharmacien")->user()->username }}',
                    raison:           raison || 'Transfert pharmacie',
                })
            });

            const data = await response.json();

            if (response.ok) {
                bootstrap.Modal.getInstance(document.getElementById('transferModal')).hide();
                // Recharger la page pour voir la transaction
                window.location.reload();
            } else {
                errDiv.textContent   = data.message ?? 'Une erreur est survenue.';
                errDiv.style.display = 'block';
            }
        } catch (err) {
            errDiv.textContent   = 'Erreur de connexion. Veuillez réessayer.';
            errDiv.style.display = 'block';
        } finally {
            btn.disabled  = false;
            btn.innerHTML = '<iconify-icon icon="ph:paper-plane-right-bold"></iconify-icon> Envoyer';
        }
    });
</script>
