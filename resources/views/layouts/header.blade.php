<style>
    .navbar-header {

        position: sticky;
        top: 0;
        z-index: 999;
    }

    @media(max-width:992px) {

        .navbar-header {

            padding: 15px;

        }

    }

    @media(max-width:576px) {

        .navbar-header h5 {

            font-size: 18px;

        }

        .navbar-header small {

            font-size: 12px;

        }

    }
</style>

<div class="navbar-header bg-white border-bottom px-3 px-lg-4 py-3">

    <div class="d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center">

            {{-- Bouton Menu Mobile --}}
            <button class="btn border-0 p-0 me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu">

                <iconify-icon icon="solar:hamburger-menu-outline" width="26">
                </iconify-icon>

            </button>

            <div>
                <h5 class="fw-bold mb-0">
                    Tableau de bord
                </h5>

                <small class="text-muted">
                    Bonjour,
                    {{ Auth::guard('pharmacien')->user()->first_name }}

                    ·

                    {{ \Carbon\Carbon::now()->locale('fr')->translatedFormat('l j F Y') }}
                </small>
            </div>

        </div>

        <div class="d-flex align-items-center">

            {{-- Notification --}}

            <div class="dropdown me-3">

                <button class="btn position-relative border-0 bg-transparent p-0" data-bs-toggle="dropdown">

                    <iconify-icon icon="solar:bell-bing-outline" width="24">
                    </iconify-icon>

                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>

                </button>

                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4" style="width:320px">

                    <div class="p-3 border-bottom">

                        <h6 class="fw-bold mb-0">
                            Notifications
                        </h6>

                    </div>

                    <div class="p-4 text-center text-muted">

                        Aucune notification

                    </div>

                </div>

            </div>

            {{-- Avatar --}}

            <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center"
                style="width:42px;height:42px;">

                {{ strtoupper(substr(Auth::guard('pharmacien')->user()->first_name, 0, 1)) }}
                {{ strtoupper(substr(Auth::guard('pharmacien')->user()->last_name, 0, 1)) }}

            </div>

        </div>

    </div>

</div>
