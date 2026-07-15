<aside class="sidebar d-none d-lg-flex"
    style="background-color: #ffffff; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; height: 100vh;">
    <button type="button" class="sidebar-close-btn d-lg-none">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div class="px-24 py-20 d-flex align-items-center gap-3">
        <div
            style="background-color: #4caf50; width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
            <iconify-icon icon="solar:heart-bold" style="color: #ffffff; font-size: 1.5rem;"></iconify-icon>
        </div>
        <div>
            <span class="mb-0 fw-bold text-dark" style="font-size: 1rem;">Pharmaconsults</span>
            <span style="color: #4caf50; font-size: 0.8rem; font-weight: 500;">Suivi maladies chroniques</span>
        </div>
    </div>

    <div class="mx-20 my-12 p-12 d-flex align-items-center gap-3"
        style="background-color: #e8f7f0; border-radius: 16px;">
        @if (session('pharmacy_logo'))
            <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; overflow: hidden;">
                <img src="{{ session('pharmacy_logo') }}" alt="logo"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        @endif
        <div class="flex-grow-1">
            <div class="fw-bold text-dark" style="font-size: 0.55rem; color: #0f5132 !important;">
                {{ session('pharmacy_name', 'PharmaConsults') }}
            </div>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.75rem; color: #198754; font-weight: 500;">
                <span
                    style="width: 6px; height: 6px; background-color: #198754; border-radius: 50%; display: inline-block;"></span>
                Plan Pro · Actif
            </div>
        </div>
    </div>

    <div class="sidebar-menu-area flex-grow-1 overflow-y-auto px-20">
        <ul class="sidebar-menu d-flex flex-column gap-1 list-unstyled" id="sidebar-menu" style="padding-left: 0;">

            @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                <li class="menu-item">
                    <a href="{{ url('pharma-index') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('pharma-index') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500; transition: all 0.2s;">
                        <iconify-icon icon="solar:widget-4-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Tableau de bord</span>
                    </a>
                </li>
            @endif

            <li class="menu-item">
                <a href="{{ url('patients') }}"
                    class="d-flex align-items-center justify-content-between px-16 py-12 {{ Route::is('patients') ? 'active-link' : '' }}
                    {{ Route::is('add-patient') ? 'active-link' : '' }}{{ Route::is('view-patient') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <div class="d-flex align-items-center gap-3">
                        <iconify-icon icon="solar:users-group-two-rounded-outline"
                            style="font-size: 1rem;"></iconify-icon>
                        <span>Patients</span>
                    </div>
                    {{-- <span class="badge text-dark bg-light px-8 py-4 rounded-pill"
                        style="font-size: 0.75rem; font-weight: 600;">148</span> --}}
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('rendez-vous') }}"
                    class="d-flex align-items-center justify-content-between px-16 py-12 {{ Route::is('rendezvous.index') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <div class="d-flex align-items-center gap-3">
                        <iconify-icon icon="solar:calendar-date-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Rendez-vous</span>
                    </div>
                    {{-- <span
                        class="badge bg-danger px-8 py-4 rounded-pill text-white d-flex align-items-center justify-content-center"
                        style="font-size: 0.75rem; min-width: 20px; height: 20px;">5</span> --}}
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('rappels') }}"
                    class="d-flex align-items-center justify-content-between px-16 py-12 {{ Route::is('rappels') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <div class="d-flex align-items-center gap-3">
                        <iconify-icon icon="solar:bell-bing-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Rappels</span>
                    </div>
                    {{-- <span
                        class="badge bg-danger px-8 py-4 rounded-pill text-white d-flex align-items-center justify-content-center"
                        style="font-size: 0.75rem; min-width: 20px; height: 20px;">5</span> --}}
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('messages') }}"
                    class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('messages') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <iconify-icon icon="solar:letter-outline" style="font-size: 1rem;"></iconify-icon>
                    <span>Messages</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('campagnes') }}"
                    class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('campagnes') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <iconify-icon icon="solar:megaphone-louder-outline" style="font-size: 1rem;"></iconify-icon>
                    <span>Campagnes santé</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('statistiques') }}"
                    class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('statistiques') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <iconify-icon icon="solar:chart-2-outline" style="font-size: 1rem;"></iconify-icon>
                    <span>Statistiques</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('equipes') }}"
                    class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('equipes') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <iconify-icon icon="solar:user-speak-rounded-outline" style="font-size: 1rem;"></iconify-icon>
                    <span>Équipe</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('ma-pharmacie') }}"
                    class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('ma-pharmacie') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <iconify-icon icon="solar:shop-outline" style="font-size: 1rem;"></iconify-icon>
                    <span>Ma pharmacie</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ url('company') }}"
                    class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('company') ? 'active-link' : '' }}"
                    style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                    <iconify-icon icon="solar:settings-outline" style="font-size: 1rem;"></iconify-icon>
                    <span>Paramètres</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-20 mt-auto border-top" style="border-color: #f1f5f9 !important;">
        {{-- <div class="d-flex flex-column gap-2 mb-20">
            <button class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                style="background-color: #4caf50; color: #ffffff; border-radius: 12px; height: 46px; border: none;">
                <iconify-icon icon="heroicons:plus-16-solid" style="font-size: 1.2rem;"></iconify-icon>
                Ajouter patient
            </button>
            <button class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                style="background-color: #ffffff; color: #334155; border: 1px solid #e2e8f0; border-radius: 12px; height: 46px;">
                <iconify-icon icon="solar:qr-code-outline" style="font-size: 1.2rem;"></iconify-icon>
                Scanner QR
            </button>
        </div> --}}

        <div class="d-flex align-items-center justify-content-between p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center fw-bold"
                    style="width: 30px; height: 30px; background-color: #ccf1e1; color: #0ea5e9; border-radius: 50%; font-size: 0.7rem;">
                    {{ strtoupper(substr(Auth::guard('pharmacien')->user()->first_name ?? 'Y', 0, 1)) }}{{ strtoupper(substr(Auth::guard('pharmacien')->user()->last_name ?? 'T', 0, 1)) }}
                </div>
                <div>
                    <p class="mb-0 fw-bold text-dark" style="font-size: 0.6rem;">
                        {{ Auth::guard('pharmacien')->user()->first_name ?? 'Yapi' }}
                        {{ Auth::guard('pharmacien')->user()->last_name ?? 'T' }}
                    </p>
                    <span class="text-muted" style="font-size: 0.8rem;">
                        {{ ucfirst(strtolower(Auth::guard('pharmacien')->user()->role ?? 'Pharmacien')) }}
                    </span>
                </div>
            </div>
            <a href="{{ url('logout') }}" class="text-secondary"
                style="font-size: 1rem; display: flex; align-items: center; transition: color 0.2s;">
                <iconify-icon icon="solar:logout-3-outline" style="color: #94a3b8;"></iconify-icon>
            </a>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarMenu">

    <div class="offcanvas-header">

        <div class="px-24 py-20 d-flex align-items-center gap-3">
            <div
                style="background-color: #4caf50; width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <iconify-icon icon="solar:heart-bold" style="color: #ffffff; font-size: 1.5rem;"></iconify-icon>
            </div>
            <div>
                <span class="mb-0 fw-bold text-dark" style="font-size: 1rem;">Pharmaconsults</span>
                <span style="color: #4caf50; font-size: 0.8rem; font-weight: 500;">Suivi maladies chroniques</span>
            </div>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
        </button>

    </div>

    <div class="offcanvas-body">

        <div class="mx-20 my-12 p-12 d-flex align-items-center gap-3"
            style="background-color: #e8f7f0; border-radius: 16px;">
            @if (session('pharmacy_logo'))
                <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; overflow: hidden;">
                    <img src="{{ session('pharmacy_logo') }}" alt="logo"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @endif
            <div class="flex-grow-1">
                <div class="fw-bold text-dark" style="font-size: 0.55rem; color: #0f5132 !important;">
                    {{ session('pharmacy_name', 'PharmaConsults') }}
                </div>
                <div class="d-flex align-items-center gap-1"
                    style="font-size: 0.75rem; color: #198754; font-weight: 500;">
                    <span
                        style="width: 6px; height: 6px; background-color: #198754; border-radius: 50%; display: inline-block;"></span>
                    Plan Pro · Actif
                </div>
            </div>
        </div>

        <div class="sidebar-menu-area flex-grow-1 overflow-y-auto px-20">
            <ul class="sidebar-menu d-flex flex-column gap-1 list-unstyled" id="sidebar-menu"
                style="padding-left: 0;">

                @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                    <li class="menu-item">
                        <a href="{{ url('pharma-index') }}"
                            class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('pharma-index') ? 'active-link' : '' }}"
                            style="border-radius: 12px; text-decoration: none; font-weight: 500; transition: all 0.2s;">
                            <iconify-icon icon="solar:widget-4-outline" style="font-size: 1rem;"></iconify-icon>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                @endif

                <li class="menu-item">
                    <a href="{{ url('patients') }}"
                        class="d-flex align-items-center justify-content-between px-16 py-12 {{ Route::is('patients') ? 'active-link' : '' }}
                    {{ Route::is('add-patient') ? 'active-link' : '' }}{{ Route::is('view-patient') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <div class="d-flex align-items-center gap-3">
                            <iconify-icon icon="solar:users-group-two-rounded-outline"
                                style="font-size: 1rem;"></iconify-icon>
                            <span>Patients</span>
                        </div>
                        {{-- <span class="badge text-dark bg-light px-8 py-4 rounded-pill"
                        style="font-size: 0.75rem; font-weight: 600;">148</span> --}}
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('rendez-vous') }}"
                        class="d-flex align-items-center justify-content-between px-16 py-12 {{ Route::is('rendezvous.index') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <div class="d-flex align-items-center gap-3">
                            <iconify-icon icon="solar:calendar-date-outline" style="font-size: 1rem;"></iconify-icon>
                            <span>Rendez-vous</span>
                        </div>
                        {{-- <span
                        class="badge bg-danger px-8 py-4 rounded-pill text-white d-flex align-items-center justify-content-center"
                        style="font-size: 0.75rem; min-width: 20px; height: 20px;">5</span> --}}
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('rappels') }}"
                        class="d-flex align-items-center justify-content-between px-16 py-12 {{ Route::is('rappels') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <div class="d-flex align-items-center gap-3">
                            <iconify-icon icon="solar:bell-bing-outline" style="font-size: 1rem;"></iconify-icon>
                            <span>Rappels</span>
                        </div>
                        {{-- <span
                        class="badge bg-danger px-8 py-4 rounded-pill text-white d-flex align-items-center justify-content-center"
                        style="font-size: 0.75rem; min-width: 20px; height: 20px;">5</span> --}}
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('messages') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('messages') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <iconify-icon icon="solar:letter-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Messages</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('campagnes') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('campagnes') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <iconify-icon icon="solar:megaphone-louder-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Campagnes santé</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('statistiques') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('statistiques') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <iconify-icon icon="solar:chart-2-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Statistiques</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('equipes') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('equipes') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <iconify-icon icon="solar:user-speak-rounded-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Équipe</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('ma-pharmacie') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('ma-pharmacie') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <iconify-icon icon="solar:shop-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Ma pharmacie</span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ url('company') }}"
                        class="d-flex align-items-center gap-3 px-16 py-12 {{ Route::is('company') ? 'active-link' : '' }}"
                        style="border-radius: 12px; text-decoration: none; font-weight: 500;">
                        <iconify-icon icon="solar:settings-outline" style="font-size: 1rem;"></iconify-icon>
                        <span>Paramètres</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="p-20 mt-auto border-top" style="border-color: #f1f5f9 !important;">
            {{-- <div class="d-flex flex-column gap-2 mb-20">
            <button class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                style="background-color: #4caf50; color: #ffffff; border-radius: 12px; height: 46px; border: none;">
                <iconify-icon icon="heroicons:plus-16-solid" style="font-size: 1.2rem;"></iconify-icon>
                Ajouter patient
            </button>
            <button class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                style="background-color: #ffffff; color: #334155; border: 1px solid #e2e8f0; border-radius: 12px; height: 46px;">
                <iconify-icon icon="solar:qr-code-outline" style="font-size: 1.2rem;"></iconify-icon>
                Scanner QR
            </button>
        </div> --}}

            <div class="d-flex align-items-center justify-content-between p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center fw-bold"
                        style="width: 30px; height: 30px; background-color: #ccf1e1; color: #0ea5e9; border-radius: 50%; font-size: 0.7rem;">
                        {{ strtoupper(substr(Auth::guard('pharmacien')->user()->first_name ?? 'Y', 0, 1)) }}{{ strtoupper(substr(Auth::guard('pharmacien')->user()->last_name ?? 'T', 0, 1)) }}
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark" style="font-size: 0.6rem;">
                            {{ Auth::guard('pharmacien')->user()->first_name ?? 'Yapi' }}
                            {{ Auth::guard('pharmacien')->user()->last_name ?? 'T' }}
                        </p>
                        <span class="text-muted" style="font-size: 0.8rem;">
                            {{ ucfirst(strtolower(Auth::guard('pharmacien')->user()->role ?? 'Pharmacien')) }}
                        </span>
                    </div>
                </div>
                <a href="{{ url('logout') }}" class="text-secondary"
                    style="font-size: 1rem; display: flex; align-items: center; transition: color 0.2s;">
                    <iconify-icon icon="solar:logout-3-outline" style="color: #94a3b8;"></iconify-icon>
                </a>
            </div>
        </div>

    </div>

</div>

<style>
    .sidebar .menu-item a {
        color: #475569;
    }

    .sidebar .menu-item a:hover {
        background-color: #f8fafc;
        color: #1e293b;
    }

    /* Style de l'onglet actif (Sélectionné) */
    .sidebar .menu-item a.active-link {
        background-color: #4caf50 !important;
        color: #ffffff !important;
    }

    .sidebar .menu-item a.active-link iconify-icon {
        color: #ffffff !important;
    }
</style>
