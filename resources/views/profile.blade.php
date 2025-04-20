@extends('dashboard')
@section('content')
    <h2 class="mt-3">Perfil Administrador</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Perfil Administrador</li>
        </ol>
    </nav>
    <div class="row mt-4">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Editar Usuario</div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.edit_validation') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label><b>Nombre de Usuario</b></label>
                            <input type="text" name="name" class="form-control" placeholder="Nombre"
                                value="{{ $data->name }}" />
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label><b>Correo Electrónico</b></label>
                            <input type="text" name="email" class="form-control" placeholder="Correo Electrónico"
                                value="{{ $data->email }}" />
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->has('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label><b>Contraseña</b></label>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña" />
                        </div>
                        <div class="form-group mb-3">
                            <input type="submit" class="btn btn-primary" value="Editar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
