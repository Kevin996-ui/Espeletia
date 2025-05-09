@extends('dashboard')

@section('content')
    <h2 class="mt-3">Administración de Perfil</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Perfil</li>
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
                    <div class="col col-md-6">Administración de Perfil de Administrador</div>
                    <div class="col col-md-6">
                        <a href="{{ route('admin_profile.create') }}" class="btn btn-success btn-sm float-end">
                            <i class="fa fa-plus"></i> Agregar Nuevo Admin
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="admin_table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo Electrónico</th>
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

    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(function() {
            var table = $('#admin_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin_profile.fetchall') }}",
                language: {
                    processing: "Procesando...",
                    lengthMenu: "Mostrar _MENU_ registros",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "Ningún dato disponible",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "Mostrando registros del 0 al 0",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
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
                        orderable: false
                    }
                ]
            });

            // Manejar click en el botón de eliminar
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                // Confirmación con SweetAlert2
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realizar la petición AJAX para eliminar el perfil
                        $.ajax({
                            url: '/admin/profile/' + id + '/delete',
                            type: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Eliminado!',
                                        response.message,
                                        'success'
                                    );

                                    // Recargar la tabla
                                    $('#admin_table').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Hubo un problema al eliminar el perfil.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
