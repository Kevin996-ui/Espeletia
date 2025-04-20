<!DOCTYPE html>
<html lang="es">

<head>
    <title>TCC Tababela CargoCenter S.A.</title>
    <link rel="icon" href="{{ asset('images/logo-tam-3.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-tam-3.png') }}" alt="Logo TCC" style="height: 32px; margin-right: 8px;">

                TCC Tababela CargoCenter S.A.
            </a>
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="#">Bienvenido, {{ Auth::user()->email }}</a>
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">

                @php $type = Auth::user()->type; @endphp

                @if (in_array($type, ['Admin', 'Supervisor', 'User']))
                    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                        <div class="position-sticky pt-3">
                            <ul class="nav flex-column">

                                @if ($type === 'Admin')
                                    <li class="nav-item {{ Request::is('dashboard') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}"
                                            href="/dashboard">Dashboard</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('profile') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('profile') ? 'active' : '' }}"
                                            href="/profile">Perfil</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('sub_user') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('sub_user') ? 'active' : '' }}"
                                            href="/sub_user">Sub Usuario</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('department') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('department') ? 'active' : '' }}"
                                            href="/department">Departamento</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('card') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('card') ? 'active' : '' }}"
                                            href="/card">Registrar Tarjetas</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('visitor') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('visitor') ? 'active' : '' }}"
                                            href="/visitor">Visitante</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('key_type') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('key_type') ? 'active' : '' }}"
                                            href="{{ route('key_type.index') }}">Tipos de Llaves</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('keylog') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('keylog') ? 'active' : '' }}"
                                            href="{{ route('keylog.index') }}">Registro de Llaves</a>
                                    </li>
                                @elseif ($type === 'User')
                                    <li class="nav-item {{ Request::is('department') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('department') ? 'active' : '' }}"
                                            href="/department">Departamento</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('card') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('card') ? 'active' : '' }}"
                                            href="/card">Registrar Tarjetas</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('visitor') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('visitor') ? 'active' : '' }}"
                                            href="/visitor">Visitante</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('key_type') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('key_type') ? 'active' : '' }}"
                                            href="{{ route('key_type.index') }}">Tipos de Llaves</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('keylog') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('keylog') ? 'active' : '' }}"
                                            href="{{ route('keylog.index') }}">Registro de Llaves</a>
                                    </li>
                                @elseif ($type === 'Supervisor')
                                    <li class="nav-item {{ Request::is('key_type') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('key_type') ? 'active' : '' }}"
                                            href="{{ route('key_type.index') }}">Tipos de Llaves</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('keylog') ? 'active-item' : '' }}">
                                        <a class="nav-link {{ Request::is('keylog') ? 'active' : '' }}"
                                            href="{{ route('keylog.index') }}">Registro de Llaves</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="nav-link" href="#" id="logout-link">Cerrar sesión</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                @endif

                <main
                    class="{{ in_array($type, ['Admin', 'Supervisor', 'User']) ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4' : 'col-12 px-4' }}">

                    @if (isset($chart_labels) && isset($chart_data))
                        <div class="mt-4 mb-4">
                            <h4>📈 Visitas por Día</h4>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <canvas id="visitorLineChart" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const ctx = document.getElementById('visitorLineChart').getContext('2d');
                                new Chart(ctx, {

                                    type: 'line',
                                    data: {
                                        labels: {!! json_encode($chart_labels) !!},
                                        datasets: [{
                                            label: 'Cantidad de visitas',
                                            data: {!! json_encode($chart_data) !!},
                                            borderColor: 'blue',
                                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4
                                        }]
                                    },

                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: true
                                            },
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false
                                            }
                                        },

                                        scales: {
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Fecha'
                                                }
                                            },

                                            y: {

                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Visitas'
                                                },

                                                ticks: {
                                                    precision: 0
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    @endif

                    @if (isset($key_chart_labels) && isset($key_chart_data))
                        <div class="mt-4 mb-4">
                            <h4>🔐 Llaves Retiradas por Día</h4>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <canvas id="keyLineChart" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {

                                const keyCtx = document.getElementById('keyLineChart').getContext('2d');
                                new Chart(keyCtx, {

                                    type: 'line',
                                    data: {

                                        labels: {!! json_encode($key_chart_labels) !!},
                                        datasets: [{

                                            label: 'Cantidad de llaves retiradas',
                                            data: {!! json_encode($key_chart_data) !!},
                                            borderColor: 'green',
                                            backgroundColor: 'rgba(0, 218, 97, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: true
                                            },
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false
                                            }
                                        },
                                        scales: {
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Fecha'
                                                }
                                            },
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Llaves retiradas'
                                                },
                                                ticks: {
                                                    precision: 0
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

    @endguest

    <script src="{{ asset('js/bootstrap.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const logoutLink = document.getElementById("logout-link");

            if (logoutLink) {

                logoutLink.addEventListener("click", function(e) {
                    e.preventDefault();

                    Swal.fire({

                        title: '¿Estás seguro?',
                        text: "Estás a punto de cerrar sesión.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, cerrar sesión',
                        cancelButtonText: 'Cancelar'

                    }).then((result) => {

                        if (result.isConfirmed) {
                            window.location.href = "{{ route('logout') }}";
                        }
                    });
                });
            }

            @if (Auth::check() && Auth::user()->type === 'User')

                function checkAndShowPopup() {
                    const lastShown = localStorage.getItem('lastVisitorRegister');
                    const now = Date.now();
                    const diff = lastShown ? now - parseInt(lastShown) : null;
                    const shouldShow = !lastShown || diff >= 40000;

                    if (shouldShow && !window.popupAlreadyShown) {
                        window.popupAlreadyShown = true;

                        Swal.fire({
                            html: '<div style="font-size: 20px; line-height: 1.5; text-align: center;"><strong>Bienvenido</strong><br>a<br><strong>TCC Tababela CargoCenter S.A.</strong></div>',
                            icon: 'info',
                            confirmButtonText: 'Sí, registrar visitante',
                            cancelButtonText: 'Más tarde',
                            showCancelButton: true
                        }).then((result) => {
                            const timestamp = Date.now();
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Uso de Datos Personales',
                                    html: '<p style="font-size: 16px; text-align: justify;">Al continuar, aceptas que tus datos personales sean utilizados por <strong>TCC Tababela CargoCenter S.A.</strong> únicamente con fines de control y registro de visitas, de acuerdo con nuestra política de privacidad.</p>',
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
        });
    </script>

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
