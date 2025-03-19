@extends('dashboard')

@section('content')

<h2 class="mt-3">Administración de Departmentos</h2>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    	<li class="breadcrumb-item active">Administración de Departmentos</li>
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
				<div class="col col-md-6">Administración de Departmentos</div>
				<div class="col col-md-6">
					<a href="/department/add" class="btn btn-success btn-sm float-end">Agregar</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="department_table">
					<thead>
						<tr>
							<th>Nombre del Departamento</th>
							<th>Persona de contacto</th>
							<th>Correo Electrónico</th> <!-- Nueva columna de correo -->
							<th>Creado</th>
							<th>Actualizado</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>

	var table = $('#department_table').DataTable({
		processing:true,
		serverSide:true,
		ajax:'{{ route("department.fetch_all") }}', // Aquí traemos los datos

		columns:[
			{
				data:'department_name',
				name:'department_name'
			},
			{
				data:'contact_person',
				name:'contact_person'
			},
			{
				data:'email', // Nueva columna para mostrar el correo electrónico
				name:'email'
			},
			{
				data:'created_at',
				name:'created_at'
			},
			{
				data:'updated_at',
				name:'updated_at'
			},
			{
				data:'action',
				name:'action',
				orderable:false
			}
		],

		"language": {
			"processing": "Procesando...",
			"lengthMenu": "Mostrar _MENU_ registros",
			"zeroRecords": "No se encontraron resultados",
			"emptyTable": "Ningún dato disponible en esta tabla",
			"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"infoFiltered": "(filtrado de un total de _MAX_ registros)",
			"search": "Buscar:",
			"infoThousands": ",",
			"loadingRecords": "Cargando...",
			"paginate": {
				"first": "Primero",
				"last": "Último",
				"next": "Siguiente",
				"previous": "Anterior"
			}
		}
	});

	$(document).on('click', '.delete', function(){

		var id = $(this).data('id');

		if(confirm("¿Estás segura de que quieres eliminarlo?"))
		{
			window.location.href = '/department/delete/'+id;
		}

	});

</script>

@endsection
