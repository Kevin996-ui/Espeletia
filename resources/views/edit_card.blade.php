@extends('dashboard')

@section('content')
    <h2 class="mt-3">Editar Tarjeta</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/card">Administración de Tarjetas</a></li>
            <li class="breadcrumb-item active">Editar Tarjeta</li>
        </ol>
    </nav>

    <div class="mt-4">
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col col-md-6">Formulario para Editar Tarjeta</div>
                    <div class="col col-md-6">
                        <a href="/card" class="btn btn-secondary btn-sm float-end">Volver</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('card.update', $card->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="code" class="form-label">Código de la Tarjeta</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $card->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Actualizar Tarjeta</button>
                </form>
            </div>
        </div>
    </div>
@endsection
