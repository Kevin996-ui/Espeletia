@extends('dashboard')

@section('content')
    <h2 class="mt-3">Editar Perfil</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Perfil</li>
        </ol>
    </nav>

    <div class="mt-4 mb-4">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <strong>Editar Perfil</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin_profile.edit_validation') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id }}">

                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Nombre de Usuario</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="{{ $data->name }}" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-form-label">Correo Electrónico</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" value="{{ $data->email }}" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">Nueva Contraseña</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control"
                                placeholder="Contraseña (opcional)" />
                        </div>
                    </div>

                    <div class="text-end">
                        <input type="submit" class="btn btn-primary" value="Guardar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
