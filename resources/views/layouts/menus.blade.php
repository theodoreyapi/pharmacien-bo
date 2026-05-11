<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="#" class="sidebar-logo">
            <img src="{{ URL::asset('') }}assets/images/logo.png" alt="site logo" class="light-logo">
            <img src="{{ URL::asset('') }}assets/images/logo-light.png" alt="site logo" class="dark-logo">
            <img src="{{ URL::asset('') }}assets/images/logo-icon.png" alt="site logo" class="logo-icon">
        </a>
    </div>

    <div style="background: #5dbb5b1c;"
        class="alert  alert-dismissible fade show d-flex align-items-center shadow-sm px-4" role="alert"
        style="margin: 5px;">

        {{-- Logo avec un conteneur pour garder une taille fixe --}}
        @if (session('pharmacy_logo'))
            <div class="me-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                <img src="{{ session('pharmacy_logo') }}" alt="{{ session('pharmacy_name') }}"
                    class="img-fluid border border-white shadow-sm"
                    style="object-fit: cover; width: 100%; height: 100%;">
            </div>
        @endif

        {{-- Texte organisé (Nom en gras, Adresse en petit) --}}
        <div class="flex-grow-1">
            <span class="mb-0 fw-bold text-dark" style="color: #115010 !important; font-size: x-small;">
                {{ session('pharmacy_name') }}
            </span>
            <small class="text-muted d-block" style="font-size: 0.85rem; font-size: xx-small;">
                <i class="bi bi-geo-alt-fill me-1"></i> {{ session('pharmacy_address') }}
            </small>
        </div>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                <li class="dropdown {{ Route::is('pharma-index') ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Tableau de bord</span>
                    </a>
                    <ul class="sidebar-submenu {{ Route::is('pharma-index') ? 'show' : '' }}">
                        <li class="{{ Route::is('index') ? 'active-page' : '' }}">
                            <a href="{{ url('pharma-index') }}"
                                class="{{ Route::is('pharma-index') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                Tableau de bord</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="sidebar-menu-group-title">Menus</li>
            <li
                class="dropdown {{ Route::is('requete', 'reponse', 'reservations', 'paiement', 'rechargements', 'transactions') ? 'open' : '' }}">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Pharmacies</span>
                </a>
                <ul
                    class="sidebar-submenu {{ Route::is('requete', 'reponse', 'reservations', 'paiement', 'rechargements', 'transactions') ? 'show' : '' }}">
                    {{-- @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN' || Auth::guard('pharmacien')->user()->role == 'GESTIONNAIRE')
                        <li class="{{ Route::is('requete') ? 'active-page' : '' }}">
                            <a href="{{ url('requete') }}" class="{{ Route::is('requete') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                Requêtes</a>
                        </li> --}}
                    {{-- <li class="{{ Route::is('reponse') ? 'active-page' : '' }}">
                            <a href="{{ url('reponse') }}" class="{{ Route::is('reponse') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                Reponses</a>
                        </li> --}}
                    {{-- @endif --}}
                    {{-- @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN' || Auth::guard('pharmacien')->user()->role == 'GESTIONNAIRE')
                        <li class="{{ Route::is('reservations') ? 'active-page' : '' }}">
                            <a href="{{ url('reservations') }}"
                                class="{{ Route::is('reservations') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                Réservations</a>
                        </li>
                    @endif --}}
                    @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN' ||
                            Auth::guard('pharmacien')->user()->role == 'GESTIONNAIRE')
                        <li class="{{ Route::is('appointments') ? 'active-page' : '' }}">
                            <a href="{{ url('appointments') }}"
                                class="{{ Route::is('appointments') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                Rendez-vous</a>
                        </li>
                    @endif
                    @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                        <li class="{{ Route::is('ma-pharmacie') ? 'active-page' : '' }}">
                            <a href="{{ url('ma-pharmacie') }}"
                                class="{{ Route::is('ma-pharmacie') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                Ma pharmacie</a>
                        </li>
                    @endif
                    @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                        <li class="{{ Route::is('reviews') ? 'active-page' : '' }}">
                            <a href="{{ url('reviews') }}" class="{{ Route::is('reviews') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                Notes & Commentaires</a>
                        </li>
                    @endif
                    @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                        <li class="{{ Route::is('rechargements') ? 'active-page' : '' }}">
                            <a href="{{ url('rechargements') }}"
                                class="{{ Route::is('rechargements') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                Rechargements</a>
                        </li>
                    @endif
                    @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN' || Auth::guard('pharmacien')->user()->role == 'CAISSIERE')
                        <li class="{{ Route::is('transactions') ? 'active-page' : '' }}">
                            <a href="{{ url('transactions') }}"
                                class="{{ Route::is('transactions') ? 'active-page' : '' }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                Transactions</a>
                        </li>
                    @endif
                </ul>
            </li>

            <li class="{{ Route::is('terms-about') ? 'active-page' : '' }}">
                <a href="{{ url('terms-about') }}" class="{{ Route::is('terms-about') ? 'active-page' : '' }}">
                    <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                    <span>Apropos de nous</span>
                </a>
            </li>
            <li class="{{ Route::is('terms-politicy') ? 'active-page' : '' }}">
                <a href="{{ url('terms-politicy') }}" class="{{ Route::is('terms-politicy') ? 'active-page' : '' }}">
                    <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                    <span>Politique de confidentialite</span>
                </a>
            </li>
            <li
                class="{{ Route::is('terms-mention') ? 'active-page' : '' }}
            {{ Route::is('add-mention') ? 'active-page' : '' }}
            {{ Route::is('edit-mention') ? 'active-page' : '' }}">
                <a href="{{ url('terms-mention') }}" class="{{ Route::is('terms-mention') ? 'active-page' : '' }}">
                    <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                    <span>Mention legales</span>
                </a>
            </li>
            <li class="{{ Route::is('terms-aide') ? 'active-page' : '' }}">
                <a href="{{ url('terms-aide') }}" class="{{ Route::is('terms-aide') ? 'active-page' : '' }}">
                    <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                    <span>Aide</span>
                </a>
            </li>
            <li class="{{ Route::is('terms-condition') ? 'active-page' : '' }}">
                <a href="{{ url('terms-condition') }}"
                    class="{{ Route::is('terms-condition') ? 'active-page' : '' }}=">
                    <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                    <span>Conditions generales d'utilisation</span>
                </a>
            </li>

            @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                <li
                    class="dropdown {{ Route::is('company', 'equipes', 'notification', 'notification-alert', 'payment-gateway') ? 'show' : '' }}">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                        <span>Paramètres</span>
                    </a>
                    <ul
                        class="sidebar-submenu {{ Route::is('company', 'notification', 'notification-alert', 'payment-gateway') ? 'show' : '' }}">
                        @if (Auth::guard('pharmacien')->user()->role == 'PHARMACIEN')
                            <li class="{{ Route::is('equipes') ? 'active-page' : '' }}">
                                <a href="{{ url('equipes') }}"
                                    class="{{ Route::is('equipes') ? 'active-page' : '' }}"><i
                                        class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Equipes</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</aside>
