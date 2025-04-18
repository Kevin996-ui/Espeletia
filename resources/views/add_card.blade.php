@extends('dashboard')

@section('content')
    <h2 class="mt-3">Agregar Nueva Tarjeta</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/card">Administración de Tarjetas</a></li>
            <li class="breadcrumb-item active">Agregar Tarjeta</li>
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
                    <div class="col col-md-6">Formulario para Agregar Tarjeta</div>
                    <div class="col col-md-6">
                        <a href="/card" class="btn btn-secondary btn-sm float-end">Volver</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('card.store') }}" method="POST" id="cardForm">
                    @csrf
                    <div class="form-group">
                        <label for="code" class="font-weight-bold">Código de la tarjeta</label>
                        <input type="text" name="code" id="code" class="form-control" required>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Agregar tarjeta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
