<!DOCTYPE html>
<html lang="es">

<head>
    <title>TCC Tababela CargoCenter S.A.</title>
    <link rel="icon" href="{{ asset('images/logo-tam-3.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
</head>

<body>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 d-flex align-items-center" href="#">
            <img src="{{ asset('images/logo-tam-3.png') }}" alt="Logo TCC"
                style="height: 36px; margin-right: 8px; background-color: rgb(255, 255, 255); border-radius: 4px; padding: 2px;">
        </a>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="#">Bienvenido, a TCC Tababela CargoCenter S.A.</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <main class="col-12 px-4">



                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

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

        });
    </script>
</body>

</html>
