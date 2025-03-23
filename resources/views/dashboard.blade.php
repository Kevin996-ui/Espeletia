<!DOCTYPE html>
<html lang="es">
<head>
<title>TCC Tababela CargoCenter S.A.</title>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    @guest

    <h1 class="mt-4 mb-5 text-center">TCC Tababela CargoCenter S.A.</h1>

    @yield('content')

    @else

    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
<link rel="stylesheet" href="{{asset('css/dataTables.bootstrap5.min.css')}}">

    <script src="{{asset('js/jquery.js')}}"></script>
<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/dataTables.bootstrap5.min.js')}}"></script>

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

            @if(Auth::user()->type == 'Admin')
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
<div class="position-sticky pt-3">
<ul class="nav flex-column">
<li class="nav-item">
<a class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}" href="/dashboard">Dashboard</a>
</li>
<li class="nav-item">
<a class="nav-link {{ Request::segment(1) == 'profile' ? 'active' : '' }}" href="/profile">Perfil</a>
</li>
<li class="nav-item">
<a class="nav-link {{ Request::segment(1) == 'sub_user' ? 'active' : '' }}" href="/sub_user">Sub Usuario</a>
</li>
<li class="nav-item">
<a class="nav-link {{ Request::segment(1) == 'department' ? 'active' : '' }}" href="/department">Departamento</a>
</li>
<li class="nav-item">
<a class="nav-link {{ Request::segment(1) == 'visitor' ? 'active' : '' }}" href="/visitor">Visitante</a>
</li>
<li class="nav-item">
<a class="nav-link" href="{{ route('logout') }}">Cerrar sesión</a>
</li>
</ul>
</div>
</nav>

            @endif

            <main class="{{ Auth::user()->type == 'Admin' ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4' : 'col-12 px-4' }}">

                @yield('content')
</main>
</div>
</div>

    @endguest

    <script src="{{ asset('js/bootstrap.js') }}"></script>

    @php

    $userIsAdmin = Auth::check() && Auth::user()->type === 'Admin';

@endphp

<script>

document.addEventListener("DOMContentLoaded", function () {

    @if(Auth::check() && !$userIsAdmin)

    const now = Date.now();

    const lastRegister = localStorage.getItem('lastVisitorRegister');

    const mustShowPopup = !lastRegister || (now - parseInt(lastRegister)) >= 120000;

    if (mustShowPopup) {

        Swal.fire({

            title: '¡Bienvenido!',

            html: `
<div style="font-size: 20px; line-height: 1.5; text-align: center;">
<strong>Bienvenido</strong><br>

                    a<br>
<strong>Tababela CargoCenter S.A.</strong>
</div>

            `,

            icon: 'info',

            confirmButtonText: 'Sí, registrar visitante',

            cancelButtonText: 'Más tarde',

            showCancelButton: true

        }).then((result) => {

            if (result.isConfirmed) {

                localStorage.setItem('lastVisitorRegister', Date.now());

                window.location.href = '{{ route("visitor.add") }}';

            }

        });

    }

    @endif

    $.ajaxSetup({

        timeout: 40000

    });

});
</script>

@if(session('success'))
<script>

document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({

        icon: 'success',

        title: 'Éxito',

        text: '{{ session('success') }}',

        timer: 2500,

        timerProgressBar: true,

        showConfirmButton: false

    }).then(() => {

        @if(Auth::check() && !$userIsAdmin)

        Swal.fire({

            html: `
<div style="font-size: 20px; line-height: 1.5; text-align: center;">
<strong>Bienvenido</strong><br>

                    a<br>
<strong>TCC Tababela CargoCenter S.A.</strong>
</div>

            `,

            icon: 'info',

            confirmButtonText: 'Sí, registrar visitante',

            cancelButtonText: 'Más tarde',

            showCancelButton: true

        }).then((result) => {

            if (result.isConfirmed) {

                localStorage.setItem('lastVisitorRegister', Date.now());

                window.location.href = '{{ route("visitor.add") }}';

            }

        });

        @endif

    });

});
</script>

@endif
</body>
</html>

