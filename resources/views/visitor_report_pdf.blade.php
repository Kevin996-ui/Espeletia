<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Visitantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>Reporte de Visitantes</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Cédula</th>
                <th>Hora de Entrada</th>
                <th>Motivo</th>
                <th>Departamento</th>
                <th>Hora de Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->visitor_name }}</td>
                    <td>{{ $visitor->visitor_company }}</td>
                    <td>{{ $visitor->visitor_identity_card }}</td>
                    <td>{{ $visitor->visitor_enter_time }}</td>
                    <td>{{ $visitor->visitor_reason_to_meet }}</td>
                    <td>{{ $visitor->department ? $visitor->department->department_name : 'N/A' }}</td>
                    <td>{{ $visitor->visitor_out_time ?? 'No registrado' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
