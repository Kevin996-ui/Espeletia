@extends('dashboard')

@section('content')
    <h2 class="mt-3">Listado de llaves</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Registro de Llaves</li>
        </ol>
    </nav>

    <div class="mt-4 mb-4">

        @if (session()->has('success'))
            <div class="alert alert-success">

                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6"><strong>Listado de llaves registradas</strong></div>
                    <div class="col-md-6">
                        <a href="{{ route('keylog.create') }}" class="btn btn-success btn-sm float-end">Registrar llave</a>

                        @if (auth()->check())
                            <a href="{{ route('keylog.report') }}"
                                class="btn btn-secondary btn-sm float-end me-2">Reporte</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar por cédula...">

                <div id="loadingSpinner" style="display: none; text-align:center; margin-bottom: 10px;">🔄 Buscando...</div>

                <div class="table-responsive" id="keylog-table-container">

                    @include('partials.keylog_table', ['keyLogs' => $keyLogs])
                </div>

                <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-4">
                    <div>

                        Mostrando {{ $keyLogs->firstItem() }} a {{ $keyLogs->lastItem() }} de {{ $keyLogs->total() }}
                        resultados
                    </div>
                    <div>

                        {{ $keyLogs->links('vendor.pagination.bootstrap-4') }}
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

        .table-header-colored th {

            background-color: #f2f2f2;

            color: #0b0d0e;

            font-weight: bold;

            text-align: center;

        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchInput');

            const tableContainer = document.getElementById('keylog-table-container');

            const paginationContainer = document.getElementById('paginationContainer');

            let timer;

            let originalTableHTML = tableContainer.innerHTML;

            searchInput.addEventListener('input', function() {

                clearTimeout(timer);

                const value = this.value.trim();

                timer = setTimeout(() => {

                    if (value.length >= 4) {

                        fetch(
                                `{{ route('keylog.ajax-search') }}?search=${encodeURIComponent(value)}`
                            )

                            .then(response => response.json())

                            .then(data => {

                                tableContainer.innerHTML = data.table_html;

                                paginationContainer.style.display = 'none';

                            });

                    } else if (value.length === 0) {

                        tableContainer.innerHTML = originalTableHTML;

                        paginationContainer.style.display = 'flex';

                    }

                }, 300);

            });

        });
    </script>
@endsection
