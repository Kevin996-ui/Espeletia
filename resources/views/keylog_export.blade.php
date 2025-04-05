<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Llaves</title>
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

        table, th, td {

            border: 1px solid #444;

        }

        th, td {

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
<h3>Reporte de Llaves</h3>
</div>

    <table>
<thead>
<tr>
<th>Nombre</th>
<th>Cédula</th>
<th>Código de Llave</th>
<th>Área</th>
<th>Hora de Retiro</th>
<th>Hora de Devolución</th>
</tr>
</thead>
<tbody>

            @foreach($keyLogs as $log)
<tr>
<td>{{ $log->name_taken }}</td>
<td>{{ $log->identity_card_taken }}</td>
<td>{{ $log->key_code }}</td>
<td>{{ $log->area }}</td>
<td>{{ \Carbon\Carbon::parse($log->key_taken_at)->format('d/m/Y H:i') }}</td>
<td>

                        @if ($log->key_returned_at)

                            {{ \Carbon\Carbon::parse($log->key_returned_at)->format('d/m/Y H:i') }}

                        @else

                            No registrada

                        @endif
</td>
</tr>

            @endforeach
</tbody>
</table>

    <div class="footer">

        Exportado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
</div>
</body>
</html>

