<table class="table table-bordered" id="keylog_table">
    <thead class="table-header-colored">
        <tr>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Código de Llave</th>
            <th>Fecha y Hora de Retiro</th>
            <th>Fecha y Hora de Entrega</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="keylogTableBody">

        @foreach ($keyLogs as $keyLog)
            <tr>
                <td>{{ $keyLog->identity_card_taken }}</td>
                <td>{{ $keyLog->name_taken }}</td>
                <td>{{ $keyLog->key_code }}</td>
                <td>{{ \Carbon\Carbon::parse($keyLog->key_taken_at)->format('d/m/Y H:i') }}</td>
                <td>

                    @if ($keyLog->key_returned_at)
                        {{ \Carbon\Carbon::parse($keyLog->key_returned_at)->format('d/m/Y H:i') }}
                    @else
                        <form action="{{ route('keylog.return', $keyLog->id) }}" method="POST">

                            @csrf
                            <button type="submit" class="btn btn-soft-danger btn-sm">Registrar Devolución</button>
                        </form>
                    @endif
                </td>
                <td>
                    <a href="{{ $keyLog->key_returned_at ? 'javascript:void(0);' : route('keylog.edit', $keyLog->id) }}"
                        class="btn btn-warning btn-sm" @if ($keyLog->key_returned_at) disabled @endif>

                        Editar
                    </a>
                    <form action="{{ route('keylog.destroy', $keyLog->id) }}" method="POST" style="display:inline;">

                        @csrf

                        @method('DELETE')
                        <button class="btn btn-danger btn-sm mt-1" @if ($keyLog->key_returned_at) disabled @endif>

                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
