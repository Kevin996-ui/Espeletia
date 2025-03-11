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
                    <a href="{{ route('visitor.add') }}" class="btn btn-success btn-sm float-end">Agregar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="visitor_table">
                    <thead>
                        <tr>
                            <th>Nombre del Visitante</th>
                            <th>Persona a Visitar</th>
                            <th>Departamento</th>
                            <th>Hora de Entrada</th>
                            <th>Hora de Salida</th>
                            <th>Estado</th>
                            <th>Motivo</th>
                            <th>Foto</th>
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

    var table = $('#visitor_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("visitor.fetch_all") }}',

        "language": {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },

        columns: [
            { data: 'visitor_name', name: 'visitor_name' },
            { data: 'visitor_meet_person_name', name: 'visitor_meet_person_name' },
            { data: 'visitor_department', name: 'visitor_department' },
            { data: 'visitor_enter_time', name: 'visitor_enter_time' },
            { data: 'visitor_out_time', name: 'visitor_out_time' },
            { data: 'visitor_status', name: 'visitor_status' },
            { data: 'name', name: 'name' },
            { data: 'photo', name: 'photo' },
            { data: 'action', name: 'action', orderable: false }
        ]
    });

    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');
        if (confirm("¿Estás seguro de que deseas eliminarlo?")) {
            window.location.href = '/visitor/delete/' + id;
        }
    });

</script>

@endsection
