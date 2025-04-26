@extends(session('user_guest_type') === 'User' ? 'user_dashboard' : 'dashboard')

@section('content')
    <h2 class="mt-3">Listado de Visitas</h2>
    @if (!(session('user_guest_type') === 'User'))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Listado de Visitas</li>
            </ol>
        </nav>
    @endif
    <div class="mt-4 mb-4">

        @if (session()->has('success'))
            <div class="alert alert-success">

                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col col-md-6">Administración de Visitantes</div>
                    <div class="col col-md-6">
                        <a href="{{ route('visitor.add') }}" class="btn btn-success btn-sm float-end">Registrar Visita</a>

                        @if ((auth()->check() && in_array(auth()->user()->type, ['Admin', 'User'])) || session('user_guest_type') === 'User')
                            <a href="{{ route('visitor.report') }}"
                                class="btn btn-secondary btn-sm float-end ms-2">Reporte</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar por cédula...">

                <div class="table-responsive">
                    <table class="table table-bordered" id="visitor_table">
                        <thead class="thead-colored">
                            <tr>
                                <th>Cédula de Identidad</th>
                                <th>Visitante</th>
                                <th>Empresa</th>
                                <th>Motivo</th>
                                <th>Depto</th>
                                <th>Tarjeta de Visitante</th>
                                <th>Tarjeta de Proveedor</th>
                                <th>Herramientas / Dispositivos</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="visitorTableBody">

                            @include('partials.visitor_table', ['visitors' => $visitors])
                        </tbody>
                    </table>
                </div>

                <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-4">
                    <div>

                        Mostrando {{ $visitors->firstItem() }} a {{ $visitors->lastItem() }} de {{ $visitors->total() }}
                        resultados
                    </div>
                    <div>

                        {{ $visitors->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-soft-danger {

            background-color: #f8d7da;

            border-color: #f5c6cb;

            color: #721c24;

        }

        .btn-soft-danger:hover {

            background-color: #f1b0b7;

            border-color: #f1b0b7;

            color: #721c24;

        }

        .btn[disabled] {

            cursor: not-allowed;

            opacity: 0.6;

        }

        .btn-sm {

            margin-right: 10px;

        }

        .thead-colored th {

            background-color: #f2f2f2;

            font-weight: bold;

            text-align: center;

        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchInput');

            const tableBody = document.getElementById('visitorTableBody');

            const pagination = document.querySelector('.pagination');

            const info = document.querySelector('.d-flex.justify-content-between.align-items-center.mt-4 > div');

            let timer;

            const originalTableHTML = tableBody.innerHTML;

            const originalPaginationHTML = document.getElementById('paginationContainer').innerHTML;

            searchInput.addEventListener('input', function() {

                clearTimeout(timer);

                const value = this.value.trim();

                timer = setTimeout(() => {

                    if (value.length >= 4) {

                        fetch(
                                `{{ route('visitor.ajax-search') }}?search=${encodeURIComponent(value)}`
                            )

                            .then(response => response.json())

                            .then(data => {

                                tableBody.innerHTML = data.table_html;

                                pagination.style.display = 'none';

                                info.style.display = 'none';

                            });

                    } else if (value.length === 0) {

                        tableBody.innerHTML = originalTableHTML;

                        document.getElementById('paginationContainer').innerHTML =
                            originalPaginationHTML;

                    }

                }, 300);

            });

        });

        function confirmDelete(visitorId) {

            Swal.fire({

                title: '¿Estás seguro?',

                text: "Esta acción eliminará el registro del visitante.",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#3085d6',

                confirmButtonText: 'Sí, eliminar',

                cancelButtonText: 'Cancelar'

            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('delete-form-' + visitorId).submit();

                }

            });

        }
    </script>
@endsection
