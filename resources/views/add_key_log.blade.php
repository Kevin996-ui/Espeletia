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

                        <div id="key_fields">
                            <div class="form-group mb-3 key-field" data-index="0">
                                <label><b>Código de la Llave</b></label>
                                <select name="key_code[]" class="form-control key-code-select" required>
                                    <option value="">Seleccione el código de la Llave</option>

                                    @foreach ($keyTypes as $type)
                                        <option value="{{ $type->name }}" data-area="{{ $type->area }}">

                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <label class="mt-2"><b>Área</b></label>
                                <input type="text" name="area" class="form-control area-field" readonly>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button type="button" id="add_key_field" class="btn btn-success btn-sm">+ Agregar otra

                                llave</button>
                            <button type="button" id="remove_last_key_field" class="btn btn-danger btn-sm"
                                style="display: none;">-</button>
                        </div>

                        <div class="form-group mb-3" style="display: none;">
                            <label><b>Fecha y Hora de Retiro</b></label>
                            <input type="datetime-local" name="key_taken_at" class="form-control" id="key_taken_at"
                                required>
                        </div>

                        <div class="form-group text-center mt-4">
                            <input type="submit" class="btn btn-primary" value="Guardar">
                            <a href="{{ route('keylog.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const now = new Date().toISOString().slice(0, 16);

            document.getElementById('key_taken_at').value = now;

            const keyTypes = @json($keyTypes);

            const selectedKeys = new Set();

            function updateArea(select, areaInput) {

                const selected = select.options[select.selectedIndex];

                areaInput.value = selected.getAttribute('data-area') || '';

            }

            function updateOptions() {

                const allSelects = document.querySelectorAll('.key-code-select');

                selectedKeys.clear();

                allSelects.forEach(select => {

                    if (select.value) selectedKeys.add(select.value);

                });

                allSelects.forEach(currentSelect => {

                    const currentValue = currentSelect.value;

                    currentSelect.innerHTML = '<option value="">Seleccione el código de la Llave</option>';

                    keyTypes.forEach(type => {

                        if (!selectedKeys.has(type.name) || currentValue === type.name) {

                            currentSelect.innerHTML +=

                                `<option value="${type.name}" data-area="${type.area}">${type.name}</option>`;

                        }

                    });

                    currentSelect.value = currentValue;

                });

            }

            const keyFieldsContainer = document.getElementById('key_fields');

            const removeBtn = document.getElementById('remove_last_key_field');

            document.getElementById('add_key_field').addEventListener('click', () => {

                const index = document.querySelectorAll('.key-field').length;

                const div = document.createElement('div');

                div.className = 'form-group mb-3 key-field';

                div.setAttribute('data-index', index);

                div.innerHTML = `
<label><b>Código de la Llave</b></label>
<select name="key_code[]" class="form-control key-code-select" required>
<option value="">Seleccione el código de la Llave</option>

                        ${keyTypes.map(type => `<option value="${type.name}" data-area="${type.area}">${type.name}</option>`).join('')}
</select>
<label class="mt-2"><b>Área</b></label>
<input type="text" name="area" class="form-control area-field" readonly>

                `;

                keyFieldsContainer.appendChild(div);

                const newSelect = div.querySelector('.key-code-select');

                const areaInput = div.querySelector('.area-field');

                newSelect.addEventListener('change', function() {

                    updateArea(this, areaInput);

                    updateOptions();

                });

                updateOptions();

                if (document.querySelectorAll('.key-field').length > 1) {

                    removeBtn.style.display = 'inline-block';

                }

            });

            removeBtn.addEventListener('click', () => {

                const allFields = document.querySelectorAll('.key-field');

                if (allFields.length > 1) {

                    allFields[allFields.length - 1].remove();

                    updateOptions();

                    if (allFields.length - 1 === 1) {

                        removeBtn.style.display = 'none';

                    }

                }

            });

            document.querySelectorAll('.key-code-select').forEach(select => {

                select.addEventListener('change', function() {

                    const areaInput = this.closest('.key-field').querySelector('.area-field');

                    updateArea(this, areaInput);

                    updateOptions();

                });

            });

        });
    </script>
@endsection
