@extends('dashboard')

@section('content')
    <h2 class="mt-3">Listado de Visitas</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Listado de Visitas</li>
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
                <div class="row">
                    <div class="col col-md-6">Administración de Visitantes</div>
                    <div class="col col-md-6">
                        <a href="{{ route('visitor.add') }}" class="btn btn-success btn-sm float-end">Registrar Visita</a>

                        @if (auth()->check() && in_array(auth()->user()->type, ['Admin', 'User']))
                            <a href="{{ route('visitor.report') }}"
                                class="btn btn-secondary btn-sm float-end ms-2">Reporte</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('visitor.index') }}">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar por cédula y presione enter" value="{{ request('search') }}">
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered" id="visitor_table">
                        <thead class="thead-colored">
                            <tr>
                                <th>Nombre del Visitante</th>
                                <th>Empresa</th>
                                <th>Cédula de Identidad</th>
                                <th>Motivo de Visita</th>
                                <th>Departamento</th>
                                <th>Tarjeta de Visitante</th>
                                <th>Tarjeta de Proveedor</th>
                                <th>Herramientas / Dispositivos</th>
                                <th>Hora de Entrada</th>
                                <th>Hora de Salida</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($visitors as $visitor)
                                <tr>
                                    <td>{{ $visitor->visitor_name }}</td>
                                    <td>{{ $visitor->visitor_company }}</td>
                                    <td>{{ $visitor->visitor_identity_card }}</td>
                                    <td>{{ $visitor->visitor_reason_to_meet }}</td>
                                    <td>{{ $visitor->department ? $visitor->department->department_name : 'N/A' }}</td>
                                    <td>{{ $visitor->card ? $visitor->card->code : 'N/A' }}</td>
                                    <td>{{ $visitor->visitor_card ? $visitor->visitor_card : 'N/A' }}</td>
                                    <td>{{ $visitor->visitor_photo && $visitor->visitor_photo !== 'N/A' ? $visitor->visitor_photo : 'N/A' }}</td>
                                    <td>{{ $visitor->visitor_enter_time }}</td>
                                    <td>

                                        @if ($visitor->visitor_out_time)
                                            {{ $visitor->visitor_out_time }}
                                        @else
                                            <form action="{{ route('visitor.exit', $visitor->id) }}" method="POST"
                                                class="exit-form">

                                                @csrf
                                                <button type="submit" class="btn btn-soft-danger btn-sm"
                                                    @if ($visitor->visitor_out_time) disabled @endif>

                                                    Registrar Salida
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ $visitor->visitor_out_time ? 'javascript:void(0);' : route('visitor.edit', $visitor->id) }}"
                                                class="btn btn-warning btn-sm"
                                                @if ($visitor->visitor_out_time) disabled @endif>

                                                Editar
                                            </a>
                                        </div>
                                        <div style="margin-top: 5px;">
                                            <a href="{{ $visitor->visitor_out_time ? 'javascript:void(0);' : route('visitor.delete', $visitor->id) }}"
                                                class="btn btn-danger btn-sm"
                                                @if ($visitor->visitor_out_time) disabled @endif>

                                                Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>

                        Mostrando {{ $visitors->firstItem() }} a {{ $visitors->lastItem() }} de {{ $visitors->total() }}
                        resultados
                    </div>
                    <div>

                        {{ $visitors->links('vendor.pagination.bootstrap-4') }}
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

        .thead-colored th {

            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;

        }
    </style>
@endsection
