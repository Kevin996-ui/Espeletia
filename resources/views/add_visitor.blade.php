@extends('dashboard')

@section('content')
<h2 class="mt-3">Agregar Nuevo Visitante</h2>
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('visitor.index') }}">Administración de Visitantes</a></li>
<li class="breadcrumb-item active">Agregar Visitante</li>
</ol>
</nav>

    <div class="row mt-4">
<div class="col-md-8 offset-md-2">
<div class="card">
<div class="card-header text-center">Agregar Nuevo Visitante</div>
<div class="card-body">
<form id="visitorForm" method="POST" action="{{ route('visitor.store') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="form-group mb-3">
<label><b>Nombre del Visitante</b></label>
<input type="text" name="visitor_name" class="form-control form-control-lg" required />
</div>

                        <div class="form-group mb-3">
<label><b>Empresa</b></label>
<input type="text" name="visitor_company" class="form-control form-control-lg" required />
</div>

                        <div class="form-group mb-3">
<label><b>Cédula de Identidad</b></label>
<input type="text" name="visitor_identity_card" class="form-control form-control-lg"

                                required maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
</div>

                        <div class="form-group mb-3">
<label><b>Seleccione su tarjeta de visitante</b></label>
<select name="card_id" class="form-control form-control-lg">
<option value="">Seleccionar Tarjeta</option>

                                @foreach ($cards as $card)
<option value="{{ $card->id }}">{{ $card->code }}</option>

                                @endforeach
</select>

                            @if ($errors->has('card_id'))
<span class="text-danger">{{ $errors->first('card_id') }}</span>

                            @endif
</div>

                        <div class="form-group mb-3">
<label><b>Seleccionar Departamento</b></label>
<select name="department_id" class="form-control form-control-lg" required>
<option value="">Seleccionar Departamento</option>

                                @foreach ($departments as $department)
<option value="{{ $department->id }}">{{ $department->department_name }}</option>

                                @endforeach
</select>

                            @if ($errors->has('department_id'))
<span class="text-danger">{{ $errors->first('department_id') }}</span>

                            @endif
</div>

                        <div class="form-group mb-3">
<label><b>¿Es proveedor?</b></label>
<input type="checkbox" id="isProvider" name="isProvider" class="form-check-input" value="1" />
</div>

                        <div id="visitorCardContainer" style="display: none;">
<p><b>Si es proveedor, por favor registrar su tarjeta de visita</b></p>
<div class="form-group mb-3">
<label for="visitor_card">Tarjeta de proveedor</label>
<input type="text" name="visitor_card" class="form-control" id="visitor_card">
</div>
</div>

                        <div class="form-group mb-3">
<label><b>¿Lleva herramientas o dispositivos?</b></label>
<input type="checkbox" id="hasTools" name="hasTools" class="form-check-input" value="1" />
</div>

                        <div id="toolsDescriptionContainer" style="display: none;">
<div class="form-group mb-3">
<label for="visitor_photo"><b>Describa las herramientas o dispositivos</b></label>
<input type="text" name="visitor_photo" id="visitor_photo" class="form-control"

                                    placeholder="Ej: Laptop, multímetro, etc.">
</div>
</div>

                        <div class="form-group mb-3" style="display:none;">
<label><b>Hora de Entrada</b></label>
<input type="datetime-local" name="visitor_enter_time" class="form-control form-control-lg"

                                required />
</div>

                        <div class="form-group mb-3">
<label><b>Motivo de la Visita</b></label>
<textarea name="visitor_reason_to_meet" class="form-control form-control-lg" required></textarea>
</div>

                        <div class="form-group mb-3 text-center">
<input type="submit" class="btn btn-primary btn-lg" value="Guardar" />
</div>
</form>
</div>
</div>
</div>
</div>

    <style>

        .visitor-photo-container {

            display: flex;

            justify-content: space-between;

            align-items: center;

            height: 350px;

        }

        .visitor-photo-container video,

        .visitor-photo-container img {

            width: 48%;

            height: 100%;

            object-fit: cover;

            border: 1px solid #ccc;

        }

        #captureButton {

            background-color: #4CAF50;

            color: white;

            border: none;

        }

        #captureButton:hover {

            background-color: #45a049;

        }
</style>

    <script>

        const isProviderCheckbox = document.getElementById('isProvider');

        const visitorCardContainer = document.getElementById('visitorCardContainer');

        const visitorCardInput = document.getElementById('visitor_card');

        const hasToolsCheckbox = document.getElementById('hasTools');

        const toolsDescriptionContainer = document.getElementById('toolsDescriptionContainer');

        const visitorPhotoInput = document.getElementById('visitor_photo');

        function toggleVisitorCard() {

            if (isProviderCheckbox.checked) {

                visitorCardContainer.style.display = 'block';

                visitorCardInput.setAttribute('required', 'required');

            } else {

                visitorCardContainer.style.display = 'none';

                visitorCardInput.removeAttribute('required');

            }

        }

        function toggleToolsDescription() {

            if (hasToolsCheckbox.checked) {

                toolsDescriptionContainer.style.display = 'block';

                visitorPhotoInput.setAttribute('required', 'required');

            } else {

                toolsDescriptionContainer.style.display = 'none';

                visitorPhotoInput.removeAttribute('required');

                visitorPhotoInput.value = '';

            }

        }

        isProviderCheckbox.addEventListener('change', toggleVisitorCard);

        hasToolsCheckbox.addEventListener('change', toggleToolsDescription);

        window.onload = function() {

            toggleVisitorCard();

            toggleToolsDescription();

            const enterTimeInput = document.querySelector('input[name="visitor_enter_time"]');

            if (!enterTimeInput.value) {

                const now = new Date();

                const formattedTime =

                    `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}T${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

                enterTimeInput.value = formattedTime;

            }

        };
</script>

@endsection

