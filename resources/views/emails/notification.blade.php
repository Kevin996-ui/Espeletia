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
    <p><strong>🕐 Hora de Entrada:</strong>

        {{ \Carbon\Carbon::parse($visitor->visitor_enter_time)->format('d/m/Y H:i') }}</p>

    <p><strong>🏬 Departamento:</strong> {{ $department->department_name }}</p>
    <p><strong>👤 Persona(s) de Contacto:</strong> {{ $department->contact_person }}</p>
    <p><strong>📧 Correo(s) del Departamento:</strong> {{ $department->email }}</p>

    <p><strong>📄 Motivo de la Visita:</strong> {{ $visitor->visitor_reason_to_meet }}</p>

    <p><strong>💳 Tarjeta de Visitante:</strong> {{ $visitor->card ? $visitor->card->code : 'N/A' }}</p>

    @if ($visitor->visitor_card)
        <p><strong>🧾 Visitante registrado como proveedor.</strong></p>
        <p><strong>🔖 Tarjeta de proveedor:</strong> {{ $visitor->visitor_card }}</p>
    @else
        <p><strong>🧾 No registrado como proveedor:</strong> No registrado</p>
    @endif

    @if ($visitor->visitor_photo)
        <p><strong>🔧 Herramientas / Dispositivos:</strong> {{ $visitor->visitor_photo }}</p>
    @else
        <p><strong>🔧 Herramientas / Dispositivos:</strong> No registrado</p>
    @endif

    <hr style="margin-top: 30px;">
    <p style="font-size: 12px; color: #888;">Este correo fue enviado automáticamente por el sistema de registro de
        visitantes.</p>
</body>

</html>
