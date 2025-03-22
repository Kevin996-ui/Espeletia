<!DOCTYPE html>
<html>
<head>
<title>Nuevo Visitante Registrado o Actualizado</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
<h2 style="color: #2c3e50;">📌 Nuevo Visitante Registrado o Actualizado</h2>

<p><strong>📛 Nombre del Visitante:</strong> {{ $visitor->visitor_name }}</p>
<p><strong>🏢 Empresa:</strong> {{ $visitor->visitor_company }}</p>
<p><strong>🪪 Cédula:</strong> {{ $visitor->visitor_identity_card }}</p>
<p><strong>🕐 Hora de Entrada:</strong> {{ \Carbon\Carbon::parse($visitor->visitor_enter_time)->format('d/m/Y H:i') }}</p>

<p><strong>🏬 Departamento:</strong> {{ $department->department_name }}</p>
<p><strong>👤 Persona(s) de Contacto:</strong> {{ $department->contact_person }}</p>
<p><strong>📧 Correo(s) del Departamento:</strong> {{ $department->email }}</p>

<p><strong>📄 Motivo de la Visita:</strong> {{ $visitor->visitor_reason_to_meet }}</p>

@if($visitor->visitor_card)
<p><strong>🧾 Visitante registrado como proveedor.</strong></p>
<p><strong>🔖 Tarjeta de visitante:</strong> {{ $visitor->visitor_card }}</p>
@else
<p><strong>🧾 No registrado como proveedor.</strong></p>
@endif

<p><strong>🖼️ Foto del Visitante:</strong></p>
<img src="{{ url('storage/' . $visitor->visitor_photo) }}" alt="Foto del Visitante" width="300" style="border: 1px solid #ccc; padding: 5px;">

<hr style="margin-top: 30px;">
<p style="font-size: 12px; color: #888;">Este correo fue enviado automáticamente por el sistema de registro de visitantes.</p>
</body>
</html>