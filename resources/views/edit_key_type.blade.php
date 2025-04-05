@extends('dashboard')

@section('content')

    <h2 class="mt-3">Editar Tipo de Llave</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('key_type.index') }}">Tipos de Llaves</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <div class="mt-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('key_type.update', $keyType->id) }}">

                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Tipo de Llave</label>
                        <input type="text" name="name" class="form-control" value="{{ $keyType->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="area" class="form-label">Área</label>
                        <input type="text" name="area" class="form-control" value="{{ $keyType->area }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ $keyType->email }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('key_type.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

@endsection
