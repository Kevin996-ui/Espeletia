@extends('dashboard')

@section('content')
    <h2 class="mt-3">Editar Registro de Llave</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('keylog.index') }}">Listado de Llaves</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header text-center">Formulario de Edición</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('keylog.update', $keyLog->id) }}">

                        @csrf

                        @method('PUT')

                        <div class="form-group mb-3">
                            <label><b>Cédula</b></label>
                            <input type="text" name="identity_card_taken" class="form-control" maxlength="10" required
                                value="{{ $keyLog->identity_card_taken }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="form-group mb-3">
                            <label><b>Nombre</b></label>
                            <input type="text" name="name_taken" class="form-control" required
                                value="{{ $keyLog->name_taken }}">
                        </div>

                        <div id="key_fields">

                            @php

                                $keyCodes = explode(',', $keyLog->key_code);

                            @endphp

                            @foreach ($keyCodes as $index => $code)
                                @php $code = trim($code); @endphp
                                <div class="form-group mb-3 key-field" data-index="{{ $index }}">
                                    <label><b>Código de la Llave</b></label>
                                    <select name="key_code[]" class="form-control key-code-select" required>
                                        <option value="">Seleccione el código de la Llave</option>

                                        @foreach ($keyTypes as $type)
                                            <option value="{{ $type->name }}" data-area="{{ $type->area }}"
                                                {{ $code === $type->name ? 'selected' : '' }}>

                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="mt-2"><b>Área</b></label>
                                    <input type="text" name="area" class="form-control area-field"
                                        value="{{ $keyLog->area }}" readonly>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button type="button" id="add_key_field" class="btn btn-success btn-sm">+ Agregar otra
                                llave</button>
                            <button type="button" id="remove_last_key_field" class="btn btn-danger btn-sm"
                                style="display: {{ count($keyCodes) > 1 ? 'inline-block' : 'none' }}">-</button>
                        </div>

                        <div class="form-group text-center mt-4">
                            <input type="submit" class="btn btn-primary" value="Actualizar Registro">
                            <a href="{{ route('keylog.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const keyTypes = @json($keyTypes);

            const selectedKeys = new Set();

            function updateArea(select, areaField) {

                const selectedOption = select.options[select.selectedIndex];

                const area = selectedOption.getAttribute('data-area');

                areaField.value = area ?? '';

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

                        if (!selectedKeys.has(type.name) || type.name === currentValue) {

                            currentSelect.innerHTML +=
                                `<option value="${type.name}" data-area="${type.area}">${type.name}</option>`;

                        }

                    });

                    currentSelect.value = currentValue;

                });

            }

            document.querySelectorAll('.key-code-select').forEach(select => {

                select.addEventListener('change', function() {

                    const areaInput = this.closest('.key-field').querySelector('.area-field');

                    updateArea(this, areaInput);

                    updateOptions();

                });

            });

            updateOptions();

            const keyFieldsContainer = document.getElementById('key_fields');

            const addBtn = document.getElementById('add_key_field');

            const removeBtn = document.getElementById('remove_last_key_field');

            addBtn.addEventListener('click', () => {

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

                const select = div.querySelector('.key-code-select');

                const areaInput = div.querySelector('.area-field');

                select.addEventListener('change', function() {

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

        });
    </script>
@endsection
