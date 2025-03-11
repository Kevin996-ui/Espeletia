@extends('dashboard')

@section('content')

<h2 class="mt-3">Agregar Visitante</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('visitor.index') }}">Administración de Visitantes</a></li>
        <li class="breadcrumb-item active">Agregar Visitante</li>
    </ol>
</nav>

<div class="mt-4 mb-4">
    <div class="card">
        <div class="card-header">
            <h4>Formulario de Agregar Visitante</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('visitor.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="visitor_name" class="form-label">Nombre del Visitante</label>
                    <input type="text" class="form-control" id="visitor_name" name="visitor_name" required>
                </div>
                <div class="mb-3">
                    <label for="visitor_meet_person_name" class="form-label">Persona a Visitar</label>
                    <input type="text" class="form-control" id="visitor_meet_person_name" name="visitor_meet_person_name" required>
                </div>
                <div class="mb-3">
                    <label for="visitor_department" class="form-label">Departamento</label>
                    <input type="text" class="form-control" id="visitor_department" name="visitor_department" required>
                </div>
                <div class="mb-3">
                    <label for="visitor_status" class="form-label">Estado</label>
                    <select class="form-control" id="visitor_status" name="visitor_status" required>
                        <option value="In">En el lugar</option>
                        <option value="Out">Fuera del lugar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="visitor_enter_time" class="form-label">Hora de Entrada</label>
                    <input type="datetime-local" class="form-control" id="visitor_enter_time" name="visitor_enter_time" required>
                </div>
                <div class="mb-3">
                    <label for="visitor_out_time" class="form-label">Hora de Salida (opcional)</label>
                    <input type="datetime-local" class="form-control" id="visitor_out_time" name="visitor_out_time">
                </div>
                <button type="submit" class="btn btn-primary">Guardar Visitante</button>
            </form>
        </div>
    </div>
</div>

@endsection
