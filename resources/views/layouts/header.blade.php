<div class="navbar-header" style="background-color: #ffffff; padding: 16px 24px; border-bottom: 1px solid #f1f5f9;">
    <div class="d-flex align-items-center justify-content-between w-100">

        <div>
            <p class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Tableau de bord</p>
            <span class="text-muted" style="font-size: 0.85rem;">
                Bonjour, {{ Auth::guard('pharmacien')->user()->first_name ?? 'Yapi' }} · {{ \Carbon\Carbon::now()->locale('fr')->translatedFormat('l j F') }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-3">

            <button type="button" class="btn p-0 d-flex justify-content-center align-items-center"
                style="width: 40px; height: 40px; background-color: transparent; border: none; color: #64748b;">
                <iconify-icon icon="solar:qr-code-outline" style="font-size: 1.4rem;"></iconify-icon>
            </button>

            <div class="dropdown">
                <button class="position-relative p-0 d-flex justify-content-center align-items-center" type="button"
                    data-bs-toggle="dropdown"
                    style="width: 40px; height: 40px; background-color: transparent; border: none; color: #64748b;">
                    <iconify-icon icon="solar:bell-bing-outline" style="font-size: 1.4rem;"></iconify-icon>
                    <span
                        class="position-absolute top-2 start-65 translate-middle badge rounded-circle bg-danger d-flex align-items-center justify-content-center text-white"
                        style="font-size: 0.65rem; min-width: 16px; height: 16px; padding: 0;">
                        9
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu-end p-0 shadow-sm border-0"
                    style="border-radius: 12px; width: 320px;">
                    <div class="py-12 px-16 bg-light d-flex align-items-center justify-content-between"
                        style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Notifications</h6>
                        <span class="badge bg-danger-subtle text-danger px-8 py-4 rounded-pill"
                            style="font-size: 0.75rem;">9 Nouvelles</span>
                    </div>
                    <div class="max-h-300-px overflow-y-auto">
                        <a href="javascript:void(0)"
                            class="dropdown-item px-16 py-12 border-bottom text-wrap d-flex gap-3"
                            style="font-size: 0.85rem;">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 36px; height: 36px;">
                                <iconify-icon icon="solar:bell-linear"></iconify-icon>
                            </div>
                            <div>
                                <p class="mb-0 text-dark fw-medium">Nouveau message reçu</p>
                                <small class="text-muted">Il y a 5 min</small>
                            </div>
                        </a>
                    </div>
                    <div class="text-center py-10">
                        <a href="{{ url('rappels') }}" class="text-success fw-semibold"
                            style="font-size: 0.8rem; text-decoration: none;">Gérer les rappels</a>
                    </div>
                </div>
            </div>

            <span class="d-flex justify-content-center align-items-center rounded-circle border-0 p-0"
                style="width: 40px; height: 40px; background-color: #ccf1e1; color: #0ea5e9; font-weight: 700; font-size: 0.9rem;">
                {{ strtoupper(substr(Auth::guard('pharmacien')->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(Auth::guard('pharmacien')->user()->last_name ?? 'K', 0, 1)) }}
            </span>

        </div>
    </div>
</div>
