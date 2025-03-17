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
                        <input type="text" name="visitor_identity_card" class="form-control form-control-lg" value="{{ isset($visitor) ? $visitor->visitor_identity_card : '' }}" required
                               maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                    </div>

                    <!-- Campo oculto de Hora de Entrada -->
                    <div class="form-group mb-3" style="display:none;">
                        <label><b>Hora de Entrada</b></label>
                        <input type="datetime-local" name="visitor_enter_time" class="form-control form-control-lg"
                               value="{{ isset($visitor) ? \Carbon\Carbon::parse($visitor->visitor_enter_time)->format('Y-m-d\TH:i') : '' }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Motivo de la Visita</b></label>
                        <textarea name="visitor_reason_to_meet" class="form-control form-control-lg" required>{{ isset($visitor) ? $visitor->visitor_reason_to_meet : '' }}</textarea>
                    </div>

                    <!-- Sección de captura de foto -->
                    <div class="form-group mb-3">
                        <label><b>Foto del Visitante</b></label>
                        <div style="display: flex; justify-content: space-between; align-items: center; height: 350px;">
                            <!-- Video de la cámara con tamaño fijo -->
                            <video id="video" width="48%" height="100%" autoplay></video>
                            <!-- Contenedor de la foto con tamaño fijo -->
                            <div style="width: 48%; text-align: center;">
                                <canvas id="canvas" style="display:none;"></canvas>
                                <br>
                                <!-- Imagen capturada con tamaño fijo -->
                                <img id="photo" src="{{ isset($visitor) ? asset('storage/' . $visitor->visitor_photo) : '' }}" alt="Tu foto aparecerá aquí" style="width:100%; height: 100%; object-fit: cover;" />
                            </div>
                        </div>

                        <!-- Botón para capturar la foto centrado -->
                        <div style="text-align: center; margin-top: 15px;">
                            <button type="button" id="captureButton" class="btn btn-lg">Capturar Foto</button>
                        </div>

                        <input type="hidden" name="visitor_photo" id="visitor_photo" />
                    </div>

                    <div class="form-group mb-3 text-center">
                        <input type="submit" class="btn btn-primary btn-lg" value="{{ isset($visitor) ? 'Actualizar Visitante' : 'Agregar Visitante' }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #captureButton {
        background-color: #4CAF50; /* Color verde */
        color: white; /* Texto blanco */
        border: none;
    }

    #captureButton:hover {
        background-color: #45a049; /* Verde más oscuro al pasar el ratón */
    }
</style>

<script>
    // Obtener acceso a la cámara
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photo = document.getElementById('photo');
    const captureButton = document.getElementById('captureButton');
    const visitorPhoto = document.getElementById('visitor_photo');

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
        // Dibujar la imagen del video en el canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convertir la imagen del canvas a un data URL
        const dataURL = canvas.toDataURL('image/jpeg');

        // Mostrar la foto en la vista previa
        photo.src = dataURL;
        photo.style.display = 'block';

        // Asignar la foto capturada al campo hidden (para enviarlo en el formulario)
        visitorPhoto.value = dataURL;
    });

    // Prevenir el envío del formulario si no se ha capturado la foto
    document.getElementById('visitorForm').addEventListener('submit', function(event) {
        if (!visitorPhoto.value) {
            alert('Por favor, capture la foto antes de enviar el formulario.');
            event.preventDefault(); // Evita el envío del formulario
        }
    });

    // Actualizar la hora de entrada con la hora del sistema cuando el formulario se carga
    window.onload = function() {
        const enterTimeInput = document.querySelector('input[name="visitor_enter_time"]');
        if (!enterTimeInput.value) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const formattedTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            enterTimeInput.value = formattedTime;
        }
    };
</script>

@endsection
