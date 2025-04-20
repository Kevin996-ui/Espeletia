@extends('dashboard')

@section('content')
    <style>
        body {

            background-image: url('{{ asset('images/Infraestructura11.jpg') }}');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            height: 100vh;

        }

        .login-card {

            background-color: rgba(255, 255, 255, 0.92);

            border-radius: 10px;

            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);

        }

        .login-form {

            padding-top: 100px;

            padding-bottom: 50px;

        }
    </style>

    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">

                    @if (session()->has('error'))
                        <div class="alert alert-danger">

                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <div class="card login-card">
                        <h3 class="card-header text-center">Login TCC</h3>
                        <div class="card-body">
                            <form method="POST" action="{{ route('login.custom') }}">

                                @csrf

                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Correo Electrónico" required />

                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <input type="password" name="password" class="form-control" placeholder="Contraseña"
                                        required />

                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="d-grid mx-auto">
                                    <button type="submit" class="btn btn-dark btn-block">Iniciar sesión</button>
                                </div>
                            </form>

                            <br />
                            <div class="text-center">
                                <a href="{{ route('register.custom.view') }}">¿No tienes una cuenta? Regístrate</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
