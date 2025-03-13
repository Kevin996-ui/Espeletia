@extends('dashboard')

@section('content')

<h2 class="mt-3">Administración de Visitantes</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Administración de Visitantes</li>
    </ol>
</nav>

<div class="mt-4 mb-4">
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col col-md-6">Administración de Visitantes</div>
                <div class="col col-md-6">
                    <a href="{{ route('visitor.add') }}" class="btn btn-success btn-sm float-end">Agregar Visitante</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Formulario de búsqueda -->
            <form method="GET" action="{{ route('visitor.index') }}">
                <div class="mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por Cédula" value="{{ request('search') }}">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="visitor_table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre del Visitante</th>
                            <th>Empresa</th>
                            <th>Cédula de Identidad</th>
                            <th>Hora de Entrada</th>
                            <th>Motivo de Visita</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                        <tr>
                            <td><img src="{{ asset('storage/' . $visitor->visitor_photo) }}" alt="Foto" width="50" height="50"></td>
                            <td>{{ $visitor->visitor_name }}</td>
                            <td>{{ $visitor->visitor_company }}</td>
                            <td>{{ $visitor->visitor_identity_card }}</td>
                            <td>{{ $visitor->visitor_enter_time }}</td>
                            <td>{{ $visitor->visitor_reason_to_meet }}</td>
                            <td>
                                <a href="{{ route('visitor.edit', $visitor->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <a href="{{ route('visitor.delete', $visitor->id) }}" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
