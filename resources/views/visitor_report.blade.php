@extends('dashboard')

@section('content')

<h2 class="mt-3">Reporte de Visitantes</h2>

<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item active">Reporte de Visitantes</li>
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
<div class="col col-md-6">Reporte de Visitantes</div>
<div class="col col-md-6">
<a href="{{ route('visitor.index') }}" class="btn btn-secondary btn-sm float-end">Volver</a>
</div>
</div>
</div>
<div class="card-body">
<form method="GET" action="{{ route('visitor.report') }}">
<div class="row mb-3">
<div class="col">
<input type="text" name="search" class="form-control" placeholder="Buscar por Cédula" value="{{ request('search') }}">
</div>
<div class="col">
<input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
</div>
<div class="col">
<input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
</div>
<div class="col">
<select name="department_id" class="form-control">
<option value="">Seleccione un Departamento</option>

                            @foreach($departments as $department)
<option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>

                                    {{ $department->department_name }}
</option>

                            @endforeach
</select>
</div>
<div class="col">
<select name="no_exit" class="form-control">
<option value="">Registros de salida</option>
<option value="1" {{ request('no_exit') == '1' ? 'selected' : '' }}>No han registrado salida</option>
</select>
</div>
<div class="col">
<input type="text" name="visitor_card" class="form-control" placeholder="Buscar por Tarjeta de Visitante" value="{{ request('visitor_card') }}">
</div>
</div>
<div class="row mb-3">
<div class="col text-end">
<button type="submit" class="btn btn-primary">Filtrar</button>
<a href="{{ route('visitor.report') }}" class="btn btn-secondary ms-2">Limpiar Filtros</a>
</div>
</div>
</form>

            <div class="row mb-3">
<div class="col">
<a href="{{ route('visitor.report.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-success">Exportar CSV</a>
<a href="{{ route('visitor.report.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-danger">Exportar PDF</a>
</div>
</div>

            @if($visitors->isEmpty())
<div class="alert alert-warning">

                    No se encontraron visitantes que coincidan con los filtros aplicados.
</div>

            @else
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th>Foto</th>
<th>Nombre del Visitante</th>
<th>Empresa</th>
<th>Cédula de Identidad</th>
<th>Hora de Entrada</th>
<th>Motivo de Visita</th>
<th>Departamento</th>
<th>Tarjeta de Visitante</th>
<th>Hora de Salida</th>
</tr>
</thead>
<tbody>

                            @foreach($visitors as $visitor)
<tr>
<td>
<div style="text-align: center;">
<img src="{{ asset('storage/' . $visitor->visitor_photo) }}" alt="Foto de {{ $visitor->visitor_name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 1px solid #ccc;">
</div>
</td>
<td>{{ $visitor->visitor_name }}</td>
<td>{{ $visitor->visitor_company }}</td>
<td>{{ $visitor->visitor_identity_card }}</td>
<td>{{ $visitor->visitor_enter_time }}</td>
<td>{{ $visitor->visitor_reason_to_meet }}</td>
<td>{{ optional($visitor->department)->department_name ?? 'N/A' }}</td>
<td>{{ $visitor->visitor_card ?? 'N/A' }}</td>
<td>{{ $visitor->visitor_out_time ?? 'No registrado' }}</td>
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

