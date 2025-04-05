@extends('dashboard')

@section('content')
    <h2 class="mt-3">Registrar Nueva Llave</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('keylog.index') }}">Listado de Llaves</a></li>
            <li class="breadcrumb-item active">Registrar</li>
        </ol>
    </nav>

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header text-center">Formulario de Registro</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('keylog.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label><b>Cédula</b></label>
                            <input type="text" name="identity_card_taken" class="form-control" maxlength="10" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="form-group mb-3">
                            <label><b>Nombre</b></label>
                            <input type="text" name="name_taken" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label><b>Código de la Llave</b></label>
                            <select name="key_code" class="form-control" id="key_type_select" required>
                                <option value="">Seleccione el código de la Llave</option>
                                @foreach ($keyTypes as $type)
                                    <option value="{{ $type->name }}" data-area="{{ $type->area }}">{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label><b>Área</b></label>
                            <input type="text" name="area" class="form-control" id="area_input" required readonly>
                        </div>

                        <div class="form-group mb-3" style="display: none;">
                            <label><b>Fecha y Hora de Retiro</b></label>
                            <input type="datetime-local" name="key_taken_at" class="form-control" id="key_taken_at"
                                required>
                        </div>

                        <div class="form-group text-center mt-4">
                            <input type="submit" class="btn btn-primary" value="Guardar Registro">
                            <a href="{{ route('keylog.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const now = new Date();
            const formatted = now.toISOString().slice(0, 16);
            document.getElementById('key_taken_at').value = formatted;

            const selectKeyType = document.getElementById('key_type_select');
            const areaInput = document.getElementById('area_input');

            selectKeyType.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const area = selectedOption.getAttribute('data-area');
                areaInput.value = area ?? '';
            });
        };
    </script>
@endsection
