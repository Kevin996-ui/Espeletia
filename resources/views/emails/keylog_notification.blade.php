<!DOCTYPE html>
<html>

<head>
    <title>🔑 Nuevo Registro de Llave</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">

    <h2 style="color: #2c3e50;">🔐 Nuevo Registro de Llave</h2>

    <p><strong>📛 Nombre de quien retira:</strong> {{ $keyLog->name_taken }}</p>
    <p><strong>🪪 Cédula:</strong> {{ $keyLog->identity_card_taken }}</p>
    <p><strong>🏬 Área:</strong> {{ $keyLog->area }}</p>
    <p><strong>🔑 Código de Llave:</strong> {{ $keyLog->key_code }}</p>
    <p><strong>🧰 Herramientas / Dispositivos:</strong>

        {{ $keyLog->taken_photo && $keyLog->taken_photo !== '-' ? $keyLog->taken_photo : 'N/A' }}
    </p>
    <p><strong>🕐 Fecha y Hora de Retiro:</strong>

        {{ \Carbon\Carbon::parse($keyLog->key_taken_at)->format('d/m/Y H:i') }}
    </p>

    <p><strong>📧 Correo del Responsable:</strong> {{ $keyType->email }}</p>

    <hr style="margin-top: 30px;">
    <p style="font-size: 12px; color: #888;">

        Este correo fue enviado automáticamente por el sistema de registro de llaves.
    </p>
</body>

</html>
