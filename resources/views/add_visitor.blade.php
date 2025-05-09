@extends(session('user_guest_type') === 'User' ? 'user_dashboard' : 'dashboard')

@section('content')
    <h2 class="mt-3">Agregar Nuevo Visitante</h2>
    @if (!(session('user_guest_type') === 'User'))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('visitor.index') }}">Administración de Visitantes</a></li>
                <li class="breadcrumb-item active">Agregar Visitante</li>
            </ol>
        </nav>
    @endif
    <div>
        <label for="cameraSelect"><strong>Seleccionar Cámara:</strong></label>
        <select id="cameraSelect"></select>
    </div>
    <div class="visitor-photo-container mb-4"> <video id="video" autoplay playsinline
            style="border:1px solid #ccc;"></video> <img id="preview" src=""
            style="display:none; border:1px solid #ccc;"> </div>
    <div class="alert alert-info text-center" role="alert" id="captureNotice">
        📸 Por favor, ubique su cédula frente a la cámara y presione "Capturar Cédula" para extraer los datos
        automáticamente.
    </div>
    <div class="text-center mb-5"> <button id="captureButton" class="btn btn-success btn-lg">Capturar Cédula</button>
    </div>
    <div id="visitorFormContainer" style="display:none; opacity:0; transform:translateY(-20px); transition:all 0.5s ease;">
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header text-center">Formulario de Registro</div>
                    <div class="card-body">
                        <form id="visitorForm" method="POST" action="{{ route('visitor.store') }}"
                            enctype="multipart/form-data"> @csrf <div class="form-group mb-3"> <label><b>Cédula de
                                        Identidad</b></label> <input type="text" name="visitor_identity_card"
                                    id="visitor_identity_card" class="form-control form-control-lg" required maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" /> </div>
                            <div class="form-group mb-3"> <label><b>Nombre del Visitante</b></label> <input type="text"
                                    name="visitor_name" id="visitor_name" class="form-control form-control-lg" required />
                            </div>
                            <div class="form-group mb-3"> <label><b>Empresa</b></label> <input type="text"
                                    name="visitor_company" class="form-control form-control-lg" required /> </div>
                            <div class="form-group mb-3"> <label><b>Seleccione su tarjeta de visitante</b></label>
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
                            <div class="form-group mb-3"> <label><b>Seleccionar Departamento</b></label> <select
                                    name="department_id" class="form-control form-control-lg" required>
                                    <option value="">Seleccionar Departamento</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('department_id'))
                                    <span class="text-danger">{{ $errors->first('department_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3"> <label><b>¿Es proveedor?</b></label> <input type="checkbox"
                                    id="isProvider" name="isProvider" class="form-check-input" value="1" /> </div>
                            <div id="visitorCardContainer" style="display:none;">
                                <p><b>Si es proveedor, por favor registrar su tarjeta de visita</b></p>
                                <div class="form-group mb-3"> <label for="visitor_card">Tarjeta de proveedor</label>
                                    <input type="text" name="visitor_card" class="form-control" id="visitor_card">
                                </div>
                            </div>
                            <div class="form-group mb-3"> <label><b>¿Lleva herramientas o dispositivos?</b></label>
                                <input type="checkbox" id="hasTools" name="hasTools" class="form-check-input"
                                    value="1" />
                            </div>
                            <div id="toolsDescriptionContainer" style="display:none;">
                                <div class="form-group mb-3"> <label for="visitor_photo"><b>Describa las herramientas o
                                            dispositivos</b></label> <input type="text" name="visitor_photo"
                                        id="visitor_photo" class="form-control" placeholder="Ej: Laptop, multímetro, etc.">
                                </div>
                            </div>
                            <div class="form-group mb-3" style="display:none;"> <label><b>Hora de Entrada</b></label>
                                <input type="datetime-local" name="visitor_enter_time" class="form-control form-control-lg"
                                    required />
                            </div>
                            <div class="form-group mb-3"> <label><b>Motivo de la Visita</b></label>
                                <textarea name="visitor_reason_to_meet" class="form-control form-control-lg" required></textarea>
                            </div>
                            <div class="form-group mb-3 text-center"> <input type="submit" class="btn btn-primary btn-lg"
                                    value="Guardar" /> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const video = document.getElementById('video');
        const preview = document.getElementById('preview');
        const captureButton = document.getElementById('captureButton');
        const visitorFormContainer = document.getElementById('visitorFormContainer');
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment'
            }
        }).then(stream => {
            video.srcObject = stream;
        }).catch(error => {
            console.error('Error al acceder a la cámara:', error);
        });
        captureButton.addEventListener('click', function(e) {
            e.preventDefault();
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            preview.src = canvas.toDataURL('image/jpeg');
            preview.style.display = 'block';
            video.style.display = 'none';
            fetch('{{ route('vision.analyze') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    photo: preview.src
                })
            }).then(response => response.json()).then(data => {
                document.getElementById('visitor_identity_card').value = data.document ?? '';
                document.getElementById('visitor_name').value = data.name ?? '';
                visitorFormContainer.style.display = 'block';
                setTimeout(() => {
                    visitorFormContainer.style.opacity = '1';
                    visitorFormContainer.style.transform = 'translateY(0)';
                }, 100);
                visitorFormContainer.scrollIntoView({
                    behavior: 'smooth'
                });
            }).catch(error => {
                console.error('Error OCR:', error);
            });
        });
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

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment'
                }
            }).then(stream => {
                const video = document.getElementById('video');
                video.srcObject = stream;
            }).catch(error => {
                console.error('Error al acceder a la cámara:', error);
                alert('No se pudo acceder a la cámara. Verifica los permisos.');
            });
        }
    </script>
    <style>
        .visitor-photo-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .visitor-photo-container video,
        .visitor-photo-container img {
            width: 400px;
            height: auto;
            object-fit: contain;
            border: 1px solid #ccc;
        }

        #captureButton {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
        }

        #captureButton:hover {
            background-color: #45a049;
        }
    </style>
@endsection
