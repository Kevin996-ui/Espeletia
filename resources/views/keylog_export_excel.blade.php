<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cédula</th>
            <th>Código de Llave</th>
            <th>Área</th>
            <th>Fecha y Hora de Retiro</th>
            <th>Fecha y Hora de Devolución</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($keyLogs as $log)
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
