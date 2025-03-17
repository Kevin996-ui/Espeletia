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
                            <th>Hora de Salida</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                        <tr>
                            <td>
                                <!-- Mostrar la imagen en miniatura -->
                                <img src="{{ asset('storage/visitor_photos/' . $visitor->visitor_photo) }}" alt="Foto" width="50" height="50">
                            </td>
                            <td>{{ $visitor->visitor_name }}</td>
                            <td>{{ $visitor->visitor_company }}</td>
                            <td>{{ $visitor->visitor_identity_card }}</td>
                            <td>{{ $visitor->visitor_enter_time }}</td>
                            <td>{{ $visitor->visitor_reason_to_meet }}</td>

                            <!-- Columna Hora de Salida -->
                            <td>
                                @if($visitor->visitor_out_time)
                                    {{ $visitor->visitor_out_time }}
                                @else
                                    <form action="{{ route('visitor.exit', $visitor->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-soft-danger btn-sm" @if($visitor->visitor_out_time) disabled @endif>
                                            Registrar Salida
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <!-- Columna Acción con salto de línea entre botones -->
                            <td>
                                <div>
                                    <a href="{{ $visitor->visitor_out_time ? 'javascript:void(0);' : route('visitor.edit', $visitor->id) }}"
                                       class="btn btn-warning btn-sm" @if($visitor->visitor_out_time) disabled @endif>
                                       Editar
                                    </a>
                                </div>
                                <div style="margin-top: 5px;">
                                    <a href="{{ $visitor->visitor_out_time ? 'javascript:void(0);' : route('visitor.delete', $visitor->id) }}"
                                       class="btn btn-danger btn-sm" @if($visitor->visitor_out_time) disabled @endif>
                                       Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-soft-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .btn-soft-danger:hover {
        background-color: #f1b0b7;
        border-color: #f1b0b7;
        color: #721c24;
    }

    .btn[disabled] {
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-sm {
        margin-right: 10px;
    }
</style>

@endsection
