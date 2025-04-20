@extends('dashboard')

@section('content')
    <h2 class="mt-3">Reporte de Llaves</h2>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Reporte de Llaves</li>
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
                    <div class="col col-md-6">Reporte de Llaves</div>
                    <div class="col col-md-6">
                        <a href="{{ route('keylog.index') }}" class="btn btn-secondary btn-sm float-end">Volver</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('keylog.report') }}">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por Cédula"
                                value="{{ request('search') }}">
                        </div>
                        <div class="col">
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col">
                            <select name="key_code" class="form-control">
                                <option value="">Seleccione una Llave</option>

                                @foreach ($keyTypes as $type)
                                    <option value="{{ $type->name }}"
                                        {{ request('key_code') == $type->name ? 'selected' : '' }}>

                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select name="no_return" class="form-control">
                                <option value="">Estado de devolución</option>
                                <option value="1" {{ request('no_return') == '1' ? 'selected' : '' }}>No devuelta
                                </option>
                            </select>
                        </div>
                        <div class="col text-end">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="{{ route('keylog.report') }}" class="btn btn-secondary ms-2">Limpiar</a>
                        </div>
                    </div>
                </form>

                <div class="row mb-3">
                    <div class="col">
                        <a href="{{ route('keylog.report.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                            class="btn btn-success">Exportar CSV</a>
                        <a href="{{ route('keylog.report.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                            class="btn btn-danger">Exportar PDF</a>
                    </div>
                </div>

                @if ($keyLogs->isEmpty())
                    <div class="alert alert-warning">

                        No se encontraron llaves que coincidan con los filtros.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Código de Llave</th>
                                    <th>Área</th>
                                    <th>Fecha y Hora de Retiro</th>
                                    <th>Fecha y Hora de Entrega</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($keyLogs as $log)
                                    <tr>
                                        <td>{{ $log->name_taken }}</td>
                                        <td>{{ $log->identity_card_taken }}</td>
                                        <td>{{ $log->key_code }}</td>
                                        <td>{{ $log->area }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->key_taken_at)->format('d/m/Y H:i') }}</td>
                                        <td>

                                            @if ($log->key_returned_at)
                                                {{ \Carbon\Carbon::parse($log->key_returned_at)->format('d/m/Y H:i') }}
                                            @else
                                                No registrada
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
