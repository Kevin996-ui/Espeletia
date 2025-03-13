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
                <form method="POST" action="{{ route('visitor.update', $visitor->id) }}" enctype="multipart/form-data">
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
                        <input type="text" name="visitor_identity_card" class="form-control" value="{{ old('visitor_identity_card', $visitor->visitor_identity_card) }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Hora de Entrada</b></label>
                        <input type="datetime-local" name="visitor_enter_time" class="form-control" value="{{ old('visitor_enter_time', $visitor->visitor_enter_time) }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Motivo de la Visita</b></label>
                        <input type="text" name="visitor_reason_to_meet" class="form-control" value="{{ old('visitor_reason_to_meet', $visitor->visitor_reason_to_meet) }}" required />
                    </div>

                    <div class="form-group mb-3">
                        <label><b>Foto del Visitante</b></label>
                        <video id="video" width="100%" height="auto" autoplay></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <br>
                        <button type="button" id="captureButton" class="btn btn-primary">Capturar Foto</button>
                        <input type="hidden" name="visitor_photo" id="visitor_photo" />
                        <br>
                        <img id="photo" src="{{ asset('storage/' . $visitor->visitor_photo) }}" alt="Tu foto aparecerá aquí" style="width:100%; max-width: 300px; display:{{ $visitor->visitor_photo ? 'block' : 'none' }}" />
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Visitante</button>
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
</script>

@endsection
