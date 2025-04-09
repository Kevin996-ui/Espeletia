@extends('dashboard')

@section('content')
<h2 class="mt-3">Administración de Sub-Usuario</h2>
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="/sub_user">Sub-Usuario</a></li>
<li class="breadcrumb-item active">Editar Sub-Usuario</li>
</ol>
</nav>

    <div class="row mt-4">
<div class="col-md-4">
<div class="card">
<div class="card-header">Editar Sub-Usuario</div>
<div class="card-body">
<form method="POST" action="{{ route('sub_user.edit_validation') }}">

                        @csrf

                        <div class="form-group mb-3">
<label><b>Nombre de usuario</b></label>
<input type="text" name="name" class="form-control" placeholder="Nombre" value="{{ $data->name }}" />

                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                        <div class="form-group mb-3">
<label><b>Correo electrónico</b></label>
<input type="text" name="email" class="form-control" placeholder="Correo electrónico" value="{{ $data->email }}">

                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                        <div class="form-group mb-3">
<label><b>Contraseña</b></label>
<input type="password" name="password" class="form-control" placeholder="Contraseña">

                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                        <div class="form-group mb-3">
<label><b>Seleccion el Tipo de Usuario</b></label>
<select name="type" class="form-control" required>
<option value="User" {{ $data->type == 'User' ? 'selected' : '' }}>Sub Usuario</option>
<option value="Supervisor" {{ $data->type == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
</select>

                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                        <input type="hidden" name="hidden_id" value="{{ $data->id }}" />

                        <div class="form-group mb-3">
<input type="submit" class="btn btn-primary" value="Editar" />
</div>
</form>
</div>
</div>
</div>
</div>

@endsection

