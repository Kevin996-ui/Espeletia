@extends('dashboard')

@section('content')
    <h2 class="mt-3">Listado de llaves</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Registro de Llaves</li>
        </ol>
    </nav>

    <div class="mt-4 mb-4">

        @if (session()->has('success'))
            <div class="alert alert-success">

                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6"><strong>Listado de llaves registradas</strong></div>
                    <div class="col-md-6">
                        <a href="{{ route('keylog.create') }}" class="btn btn-success btn-sm float-end">Registrar llave</a>

                        @if (auth()->check() && auth()->user()->type === 'Admin')
                            <a href="{{ route('keylog.report') }}"
                                class="btn btn-secondary btn-sm float-end me-2">Reporte</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="keylog_table">
                        <thead class="table-header-colored">
                            <tr>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Código de Llave</th>
                                <th>Área</th>
                                <th>Fecha y Hora de Retiro</th>
                                <th>Fecha y Hora de Entrega</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($keyLogs as $keyLog)
                                <tr>
                                    <td>{{ $keyLog->name_taken }}</td>
                                    <td>{{ $keyLog->identity_card_taken }}</td>
                                    <td>{{ $keyLog->key_code }}</td>
                                    <td>{{ $keyLog->area }}</td>
                                    <td>{{ \Carbon\Carbon::parse($keyLog->key_taken_at)->format('d/m/Y H:i') }}</td>
                                    <td>

                                        @if ($keyLog->key_returned_at)
                                            {{ \Carbon\Carbon::parse($keyLog->key_returned_at)->format('d/m/Y H:i') }}
                                        @else
                                            <form action="{{ route('keylog.return', $keyLog->id) }}" method="POST">

                                                @csrf
                                                <button type="submit" class="btn btn-soft-danger btn-sm">

                                                    Registrar Devolución
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $keyLog->key_returned_at ? 'javascript:void(0);' : route('keylog.edit', $keyLog->id) }}"
                                            class="btn btn-warning btn-sm"
                                            @if ($keyLog->key_returned_at) disabled @endif>

                                            Editar
                                        </a>

                                        <form action="{{ route('keylog.destroy', $keyLog->id) }}" method="POST"
                                            style="display:inline;">

                                            @csrf

                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm mt-1"
                                                @if ($keyLog->key_returned_at) disabled @endif>

                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>

                        Mostrando {{ $keyLogs->firstItem() }} a {{ $keyLogs->lastItem() }} de {{ $keyLogs->total() }}
                        resultados
                    </div>
                    <div>

                        {{ $keyLogs->links('vendor.pagination.bootstrap-4') }}
                    </div>
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


        .table-header-colored th {

            background-color: #d6dadb;
            color: #0b0d0e;
            font-weight: bold;
            text-align: center;

        }
    </style>
@endsection
