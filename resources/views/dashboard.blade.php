<!DOCTYPE html>
<html lang="es">

<head>
<title>TCC Tababela CargoCenter S.A.</title>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @guest
<div class="text-center" style="margin-top: 80px; margin-bottom: 40px;">
<img src="{{ asset('images/logo-tam-2.png') }}" alt="Logo TCC" style="max-width: 300px;">
</div>
        @yield('content')
    @else
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">TCC Tababela CargoCenter S.A.</a>
<div class="navbar-nav">
<div class="nav-item text-nowrap">
<a class="nav-link px-3" href="#">Bienvenido, {{ Auth::user()->email }}</a>
</div>
</div>
</header>

        <div class="container-fluid">
<div class="row">
                @php $type = Auth::user()->type; @endphp

                @if ($type === 'Admin' || $type === 'Supervisor')
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
<div class="position-sticky pt-3">
<ul class="nav flex-column">
                                @if ($type === 'Admin')
<li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="/dashboard">Dashboard</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'profile' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'profile' ? 'active' : '' }}" href="/profile">Perfil</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'sub_user' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'sub_user' ? 'active' : '' }}" href="/sub_user">Sub Usuario</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'department' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'department' ? 'active' : '' }}" href="/department">Departamento</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'visitor' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'visitor' ? 'active' : '' }}" href="/visitor">Visitante</a>
</li>
                                @endif

                                @if ($type === 'Supervisor')
<li class="nav-item {{ Request::segment(1) == 'sub_user' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'sub_user' ? 'active' : '' }}" href="/sub_user">Sub Usuario</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'key_type' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'key_type' ? 'active' : '' }}" href="{{ route('key_type.index') }}">Tipos de Llaves</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'keylog' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'keylog' ? 'active' : '' }}" href="{{ route('keylog.index') }}">Registro de Llaves</a>
</li>
                                @endif

                                @if ($type === 'Admin')
<li class="nav-item {{ Request::segment(1) == 'key_type' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'key_type' ? 'active' : '' }}" href="{{ route('key_type.index') }}">Tipos de Llaves</a>
</li>
<li class="nav-item {{ Request::segment(1) == 'keylog' ? 'active-item' : '' }}">
<a class="nav-link {{ Request::segment(1) == 'keylog' ? 'active' : '' }}" href="{{ route('keylog.index') }}">Registro de Llaves</a>
</li>
                                @endif

                                <li class="nav-item">
<a class="nav-link" href="{{ route('logout') }}">Cerrar sesión</a>
</li>
</ul>
</div>
</nav>
                @endif

                <main class="{{ $type === 'Admin' || $type === 'Supervisor' ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4' : 'col-12 px-4' }}">
                    @yield('content')
</main>
</div>
</div>
    @endguest

    <script src="{{ asset('js/bootstrap.js') }}"></script>

    @php
        $userIsAdmin = Auth::check() && Auth::user()->type === 'Admin';
        $userIsSupervisor = Auth::check() && Auth::user()->type === 'Supervisor';
    @endphp

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (Auth::check() && !$userIsAdmin && !$userIsSupervisor)
                function checkAndShowPopup() {
                    const now = Date.now();
                    const lastRegister = localStorage.getItem('lastVisitorRegister');
                    const mustShowPopup = !lastRegister || (now - parseInt(lastRegister)) >= 40000;

                    if (mustShowPopup && !window.popupAlreadyShown) {
                        window.popupAlreadyShown = true;

                        Swal.fire({
                            html: `<div style="font-size: 20px; line-height: 1.5; text-align: center;"><strong>Bienvenido</strong><br>a<br><strong>TCC Tababela CargoCenter S.A.</strong></div>`,
                            icon: 'info',
                            confirmButtonText: 'Sí, registrar visitante',
                            cancelButtonText: 'Más tarde',
                            showCancelButton: true
                        }).then((result) => {
                            const timestamp = Date.now();
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Uso de Datos Personales',
                                    html: `<p style="font-size: 16px; text-align: justify;">Al continuar, aceptas que tus datos personales sean utilizados por <strong>TCC Tababela CargoCenter S.A.</strong> únicamente con fines de control y registro de visitas, de acuerdo con nuestra política de privacidad.</p>`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Acepto',
                                    cancelButtonText: 'Cancelar'
                                }).then((consent) => {
                                    if (consent.isConfirmed) {
                                        localStorage.setItem('lastVisitorRegister', timestamp);
                                        window.location.href = '{{ route('visitor.add') }}';
                                    } else {
                                        window.popupAlreadyShown = false;
                                    }
                                });
                            } else {
                                localStorage.setItem('lastVisitorRegister', timestamp);
                                window.popupAlreadyShown = false;
                            }
                        });
                    }
                }

                setInterval(checkAndShowPopup, 5000);
                checkAndShowPopup();
            @endif

            $.ajaxSetup({ timeout: 40000 });
        });
</script>

    @if (session('success'))
<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '{{ session('success') }}',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
</script>
    @endif

    <style>
        .active-item {
            background-color: #0d6efd !important;
        }
        .active-item .nav-link {
            color: #fff !important;
            font-weight: bold;
        }
        .active-item .nav-link:hover {
            color: #e2e6ea !important;
        }
</style>
</body>

</html>