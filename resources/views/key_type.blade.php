@extends('dashboard')

@section('content')
    <h2 class="mt-3">Administración de Tipos de Llaves</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Administración de Tipos de Llaves</li>
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
                    <div class="col col-md-6">Listado de Tipos de Llaves</div>
                    <div class="col col-md-6">
                        <a href="{{ route('key_type.add') }}" class="btn btn-success btn-sm float-end">Agregar</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="key_type_table">
                        <thead>
                            <tr>
                                <th>Nombre del Tipo de Llave</th>
                                <th>Área</th>
                                <th>Correo Electrónico</th>
                                <th>Creado</th>
                                <th>Actualizado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Formulario oculto para eliminación -->
                <form id="deleteForm" method="POST" style="display:none;">

                    @csrf

                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <style>
        table.dataTable thead th {

            background-color: #f2f2f2;

            font-weight: bold;

        }
    </style>

    <script>
        $(document).ready(function() {

            var table = $('#key_type_table').DataTable({

                processing: true,

                serverSide: true,

                ajax: '{{ route('key_type.fetch_all') }}',

                columns: [

                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'area',
                        name: 'area'
                    },

                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],

                language: {

                    processing: "Procesando...",

                    search: "Buscar:",

                    lengthMenu: "Mostrar _MENU_ registros",

                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",

                    infoEmpty: "Mostrando 0 a 0 de 0 registros",

                    infoFiltered: "(filtrado de _MAX_ registros totales)",

                    loadingRecords: "Cargando...",

                    zeroRecords: "No se encontraron resultados",

                    emptyTable: "No hay datos disponibles en la tabla",

                    paginate: {

                        first: "Primero",

                        previous: "Anterior",

                        next: "Siguiente",

                        last: "Último"

                    },

                    aria: {

                        sortAscending: ": activar para ordenar la columna de manera ascendente",

                        sortDescending: ": activar para ordenar la columna de manera descendente"

                    }

                }

            });

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará el tipo de llave permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#deleteForm');
                        form.attr('action', '/key_type/' + id);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
