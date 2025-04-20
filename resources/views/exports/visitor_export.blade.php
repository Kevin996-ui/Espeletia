<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Visitantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/logo-tam-2.png') }}" alt="Logo">
        <h3>Reporte de Visitantes</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Cédula</th>
                <th>Motivo</th>
                <th>Departamento</th>
                <th>Tarjeta Visitante</th>
                <th>Tarjeta Proveedor</th>
                <th>Herramientas / Dispositivos</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->visitor_name }}</td>
                    <td>{{ $visitor->visitor_company }}</td>
                    <td>{{ $visitor->visitor_identity_card }}</td>
                    <td>{{ $visitor->visitor_reason_to_meet }}</td>
                    <td>{{ $visitor->department->department_name ?? 'N/A' }}</td>
                    <td>{{ $visitor->card ? $visitor->card->code : 'N/A' }}</td>
                    <td>{{ $visitor->visitor_card ?? 'N/A' }}</td>
                    <td>{{ $visitor->visitor_photo ?? 'N/A' }}</td>
                    <td>{{ $visitor->visitor_enter_time }}</td>
                    <td>{{ $visitor->visitor_out_time ?? 'No registrado' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado por: {{ $generated_by }}<br>
        Exportado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>
