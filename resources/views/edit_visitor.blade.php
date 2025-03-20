@extends('dashboard')

@section('content')

<h2 class="mt-3">Editar Visitante</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('visitor.index') }}">Administración de Visitantes</a></li>
        <li class="breadcrumb-item active">Editar Visitante</li>
    </ol>
</nav>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Editar Visitante</div>
            <div class="card-body">
                <form method="POST" action="{{ route('visitor.update', $visitor->id) }}" enctype="multipart/form-data" id="visitorForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label><b>Nombre del Visitante</b></label>
                        <input type="text" name="visitor_name" class="form-control" value="{{ old('visitor_name', $visitor->visitor_name) }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Empresa</b></label>
                        <input type="text" name="visitor_company" class="form-control" value="{{ old('visitor_company', $visitor->visitor_company) }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Cédula de Identidad</b></label>
                        <input type="text" name="visitor_identity_card" class="form-control"
                               value="{{ old('visitor_identity_card', $visitor->visitor_identity_card) }}"
                               maxlength="13"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');" required />
                    </div>

                    <!-- Campo Visitor Card -->
                    <div class="form-group mb-3">
                        <label for="visitor_card"><b>Tarjeta de visitante</b></label>
                        <input type="text" name="visitor_card" class="form-control" value="{{ old('visitor_card', $visitor->visitor_card) }}">
                    </div>

                    <!-- El campo de Hora de Entrada ya no se muestra -->
                    <input type="hidden" name="visitor_enter_time" id="visitor_enter_time" />

                    <div class="form-group mb-3">
                        <label><b>Motivo de la Visita</b></label>
                        <input type="text" name="visitor_reason_to_meet" class="form-control" value="{{ old('visitor_reason_to_meet', $visitor->visitor_reason_to_meet) }}" required />
                    </div>

                    <!-- Campo para seleccionar el Departamento -->
                    <div class="form-group mb-3">
                        <label><b>Departamento</b></label>
                        <select name="department_id" class="form-control" required>
                            <option value="">Seleccione un Departamento</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                        {{ old('department_id', $visitor->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Foto del Visitante</b></label>
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                            <!-- Imagen capturada -->
                            <div style="width: 50%; text-align: center;">
                                <canvas id="canvas" style="display:none;"></canvas>
                                <img id="photo" src="{{ isset($visitor->visitor_photo) ? asset('storage/' . $visitor->visitor_photo) : '' }}"
                                     alt="Si deseas cambiar tu foto, puedes capturar una nueva."
                                     style="width: 100%; height: 250px; object-fit: cover; display:{{ $visitor->visitor_photo ? 'block' : 'none' }};" />
                            </div>

                            <!-- Video en vivo -->
                            <div style="width: 50%; text-align: center;">
                                <video id="video" style="width: 100%; height: 250px; object-fit: cover;" autoplay></video>
                            </div>
                        </div>

                        <!-- Botón para capturar la foto debajo de ambos recuadros -->
                        <div style="text-align: center; margin-top: 15px;">
                            <button type="button" id="captureButton" class="btn btn-success">Capturar Foto</button>
                        </div>

                        <!-- Campo oculto para la imagen capturada -->
                        <input type="hidden" name="visitor_photo" id="visitor_photo" value="{{ old('visitor_photo', isset($visitor->visitor_photo) ? asset('storage/' . $visitor->visitor_photo) : '') }}" />
                    </div>

                    <!-- Botón de actualizar visitante centrado -->
                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Actualizar Visitante</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Obtener acceso a la cámara
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photo = document.getElementById('photo');
    const captureButton = document.getElementById('captureButton');
    const visitorPhoto = document.getElementById('visitor_photo');
    const visitorEnterTime = document.getElementById('visitor_enter_time');

    // Obtener el flujo de video de la cámara
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error('Error al acceder a la cámara:', err);
        });

    // Capturar la foto cuando el usuario hace clic en el botón
    captureButton.addEventListener('click', function() {
        // Establecer dimensiones del canvas igual al video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Dibujar la imagen del video en el canvas
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convertir la imagen a formato base64
        const dataURL = canvas.toDataURL('image/jpeg');

        // Mostrar la foto capturada con las mismas dimensiones del video
        photo.src = dataURL;
        photo.style.display = 'block';
        photo.style.width = '100%';
        photo.style.height = '250px';
        photo.style.objectFit = 'cover';

        // Asignar el valor al campo oculto para enviarlo al backend
        visitorPhoto.value = dataURL;
    });

    // Cuando el formulario se va a enviar, actualizar la hora de entrada con la hora del sistema
    document.getElementById('visitorForm').addEventListener('submit', function(event) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const formattedTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        visitorEnterTime.value = formattedTime; // Asignamos la hora actual al campo oculto
    });
</script>

@endsection
