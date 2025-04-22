@extends('dashboard')

@section('content')
    <h2 class="mt-3">Administración de Tarjetas</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Administración de Tarjetas</li>
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
                    <div class="col col-md-6">Administración de Tarjetas</div>
                    <div class="col col-md-6">
                        <a href="{{ route('card.create') }}" class="btn btn-success btn-sm float-end">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="card_table">
                        <thead>
                            <tr>
                                <th>Código de la Tarjeta</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Actualización</th>
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
        var table = $('#card_table').DataTable({

            processing: true,

            serverSide: true,

            ajax: '{{ route('card.fetch_all') }}',

            columns: [

                {
                    data: 'code',
                    name: 'code'
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

            ],

            language: {

                processing: "Procesando...",

                lengthMenu: "Mostrar _MENU_ registros",

                zeroRecords: "No se encontraron resultados",

                emptyTable: "Ningún dato disponible en esta tabla",

                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",

                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",

                infoFiltered: "(filtrado de un total de _MAX_ registros)",

                search: "Buscar:",

                paginate: {

                    first: "Primero",

                    last: "Último",

                    next: "Siguiente",

                    previous: "Anterior"

                }

            }

        });

        $(document).on('click', '.delete', function(e) {

            e.preventDefault();

            var id = $(this).data('id');

            Swal.fire({

                title: '¿Estás seguro?',

                text: "Esta acción eliminará la tarjeta permanentemente.",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Sí, eliminar',

                cancelButtonText: 'Cancelar'

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = '/card/delete/' + id;

                }

            });

        });
    </script>
@endsection
