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
		        		@if($errors->has('name'))
		        		<span class="text-danger">{{ $errors->first('name') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<label><b>Correo electrónico</b></label>
		        		<input type="text" name="email" class="form-control" placeholder="Correo electrónico" value="{{ $data->email }}">
		        		@if($errors->has('email'))
		        			<span class="text-danger">{{ $errors->first('email') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<label><b>Contraseña</b></label>
		        		<input type="password" name="password" class="form-control" placeholder="Contraseña">
		        		@if($errors->has('password'))
		        			<span class="text-danger">{{ $errors->first('password') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<input type="hidden" name="hidden_id" value="{{ $data->id }}" />
		        		<input type="submit" class="btn btn-primary" value="Editar" />
		        	</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
