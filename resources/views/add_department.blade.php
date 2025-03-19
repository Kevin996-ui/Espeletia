@extends('dashboard')

@section('content')

<h2 class="mt-3">Administración de Departmentos</h2>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    	<li class="breadcrumb-item"><a href="/department">Administración de Departmentos</a></li>
    	<li class="breadcrumb-item active">Agregar Nuevo Departamento</li>
  	</ol>
</nav>
<div class="row mt-4">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">Agregar Nuevo Departamento</div>
			<div class="card-body">
				<form method="POST" action="{{ route('department.add_validation') }}">
					@csrf
					<div class="form-group mb-3">
		        		<label><b>Nombre del Departamento</b></label>
		        		<input type="text" name="department_name" class="form-control" />
		        		@if($errors->has('department_name'))
		        			<span class="text-danger">{{ $errors->first('department_name') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<label><b>Persona de contacto</b></label>
		        		<div class="row">
		        			<div class="col col-md-10">
		        				<input type="text" name="contact_person[]" class="form-control" />
		        			</div>
		        			<div class="col col-md-2">
		        				<button type="button" name="add_person" id="add_person" class="btn btn-success btn-sm">+</button>
		        			</div>
		        		</div>
		        		<div id="append_person"></div>
		        	</div>
		        	<div class="form-group mb-3">
		        		<label><b>Correo Electrónico</b></label>
		        		<input type="email" name="email" class="form-control" />
		        		@if($errors->has('email'))
		        			<span class="text-danger">{{ $errors->first('email') }}</span>
		        		@endif
		        	</div>
		        	<div class="form-group mb-3">
		        		<input type="submit" class="btn btn-primary" value="Agregar" />
		        	</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	var count_person = 0;

	$(document).on('click', '#add_person', function(){
		count_person++;

		var html = `
		<div class="row mt-2" id="person_`+count_person+`">
			<div class="col-md-10">
				<input type="text" name="contact_person[]" class="form-control department_contact_person" />
			</div>
			<div class="col-md-2">
				<button type="button" name="remove_person" class="btn btn-danger btn-sm remove_person" data-id="`+count_person+`">-</button>
			</div>
		</div>
		`;

		$('#append_person').append(html);
	});

	$(document).on('click', '.remove_person', function(){
		var button_id = $(this).data('id');
		$('#person_'+button_id).remove();
	});
});
</script>
@endsection
