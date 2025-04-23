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

        .signup-card {
            background-color: rgba(255, 255, 255, 0.92);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .signup-form {
            padding-top: 100px;
            padding-bottom: 50px;
        }
    </style>

    <main class="signup-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="card signup-card">
                        <h3 class="card-header text-center">Registrar Usuario</h3>
                        <div class="card-body">
                            <form action="{{ route('register.custom') }}" method="POST">
                                @csrf

                                <div class="form-group mb-3">
                                    <input type="text" name="name" class="form-control" placeholder="Nombre"
                                        required />
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <input type="text" name="email" class="form-control"
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
                                    <button type="submit" class="btn btn-dark btn-block">Registrarte</button>
                                </div>
                            </form>

                            <br />
                            <div class="text-center">
                                <a href="{{ route('login') }}">Iniciar sesión</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
