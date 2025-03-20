@extends('dashboard')

@section('content')
<h2 class="mt-3">{{ isset($visitor) ? 'Editar Visitante' : 'Agregar Nuevo Visitante' }}</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('visitor.index') }}">Administración de Visitantes</a></li>
        <li class="breadcrumb-item active">{{ isset($visitor) ? 'Editar Visitante' : 'Agregar Visitante' }}</li>
    </ol>
</nav>

<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header text-center">{{ isset($visitor) ? 'Editar Visitante' : 'Agregar Nuevo Visitante' }}</div>
            <div class="card-body">
                <form id="visitorForm" method="POST" action="{{ isset($visitor) ? route('visitor.update', $visitor->id) : route('visitor.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($visitor))
                        @method('PUT')
                    @endif

                    <div class="form-group mb-3">
                        <label><b>Nombre del Visitante</b></label>
                        <input type="text" name="visitor_name" class="form-control form-control-lg" value="{{ isset($visitor) ? $visitor->visitor_name : '' }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Empresa</b></label>
                        <input type="text" name="visitor_company" class="form-control form-control-lg" value="{{ isset($visitor) ? $visitor->visitor_company : '' }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Cédula de Identidad</b></label>
                        <input type="text" name="visitor_identity_card" class="form-control form-control-lg" value="{{ isset($visitor) ? $visitor->visitor_identity_card : '' }}" required maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                    </div>

                    <!-- Campo de selección del departamento -->
                    <div class="form-group mb-3">
                        <label><b>Departamento</b></label>
                        <select name="department_id" class="form-control form-control-lg" required>
                            <option value="">Seleccionar Departamento</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ isset($visitor) && $visitor->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="visitor_card">Visitor Card</label>
                        <input type="text" name="visitor_card" class="form-control" value="{{ old('visitor_card', $visitor->visitor_card ?? '') }}">
                    </div>

                    <div class="form-group mb-3" style="display:none;">
                        <label><b>Hora de Entrada</b></label>
                        <input type="datetime-local" name="visitor_enter_time" class="form-control form-control-lg" value="{{ isset($visitor) ? \Carbon\Carbon::parse($visitor->visitor_enter_time)->format('Y-m-d\TH:i') : '' }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Motivo de la Visita</b></label>
                        <textarea name="visitor_reason_to_meet" class="form-control form-control-lg" required>{{ isset($visitor) ? $visitor->visitor_reason_to_meet : '' }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Foto del Visitante</b></label>
                        <div class="visitor-photo-container">
                            <video id="video" autoplay></video>
                            <canvas id="canvas" style="display:none;"></canvas>
                            <img id="photo" src="{{ isset($visitor) ? asset('storage/' . $visitor->visitor_photo) : '' }}" alt="Tu foto aparecerá aquí" />
                        </div>
                        <div style="text-align: center; margin-top: 15px;">
                            <button type="button" id="captureButton" class="btn btn-lg">Capturar Foto</button>
                        </div>
                        <input type="hidden" name="visitor_photo" id="visitor_photo" />
                    </div>

                    <div class="form-group mb-3 text-center">
                        <input type="submit" class="btn btn-primary btn-lg" value="{{ isset($visitor) ? 'Actualizar Visitante' : 'Guardar' }}" />
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
    .visitor-photo-container video, .visitor-photo-container img {
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
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photo = document.getElementById('photo');
    const captureButton = document.getElementById('captureButton');
    const visitorPhoto = document.getElementById('visitor_photo');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error('Error al acceder a la cámara:', err);
        });

    captureButton.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/jpeg');
        photo.src = dataURL;
        photo.style.display = 'block';
        visitorPhoto.value = dataURL;
    });

    document.getElementById('visitorForm').addEventListener('submit', function(event) {
        if (!visitorPhoto.value) {
            alert('Por favor, capture la foto antes de enviar el formulario.');
            event.preventDefault();
        }
    });

    window.onload = function() {
        const enterTimeInput = document.querySelector('input[name="visitor_enter_time"]');
        if (!enterTimeInput.value) {
            const now = new Date();
            const formattedTime = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}T${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            enterTimeInput.value = formattedTime;
        }
    };
</script>
@endsection