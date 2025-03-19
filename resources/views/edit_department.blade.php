@extends('dashboard')

@section('content')

<h2 class="mt-3">Administración de Departmentos</h2>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    	<li class="breadcrumb-item"><a href="/department">Administración de Departmentos</a></li>
    	<li class="breadcrumb-item active">Editar Departamento</li>
  	</ol>
</nav>
<div class="row mt-4">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">Editar Departamento</div>
			<div class="card-body">
				<form method="POST" action="{{ route('department.edit_validation') }}">
					@csrf

					<div class="form-group mb-3">
		        		<label><b>Nombre del Departamento</b></label>
		        		<input type="text" name="department_name" class="form-control" value="{{ $data->department_name }}" />
		        		@if($errors->has('department_name'))
		        			<span class="text-danger">{{ $errors->first('department_name') }}</span>
		        		@endif
		        	</div>

		        	<div class="form-group mb-3">
		        		<label><b>Persona de contacto</b></label>
		        		@php
		        		$contact_person = explode(", ", $data->contact_person);
		        		@endphp

		        		@for($i = 0; $i < count($contact_person); $i++)

		        		<div class="row mt-2" id="person_{{ $i }}">
		        			<div class="col col-md-10">
		        				<input type="text" name="contact_person[]" class="form-control" value="{{ $contact_person[$i] }}" />
		        			</div>
		        			<div class="col col-md-2">
		        				@if($i == 0)
		        				<button type="button" name="add_person" id="add_person" class="btn btn-success btn-sm">+</button>
		        				@else
		        				<button type="button" class="btn btn-danger btn-sm remove_person" data-id="{{ $i }}">-</button>
		        				@endif
		        			</div>
		        		</div>

		        		@endfor
		        		<div id="append_person"></div>
		        	</div>

		        	<!-- Agregando el campo de correo electrónico -->
		        	<div class="form-group mb-3">
		        		<label><b>Correo Electrónico</b></label>
		        		<input type="email" name="email" class="form-control" value="{{ $data->email }}" />
		        		@if($errors->has('email'))
		        			<span class="text-danger">{{ $errors->first('email') }}</span>
		        		@endif
		        	</div>

		        	<div class="form-group mb-3">
		        		<input type="hidden" name="hidden_id" value="{{ $data->id }}" />
		        		<input type="hidden" id="total_contact_person" value="{{ $i }}" />
		        		<input type="submit" class="btn btn-primary" value="Actualizar" />
		        	</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	var count_person = $('#total_contact_person').val();

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
