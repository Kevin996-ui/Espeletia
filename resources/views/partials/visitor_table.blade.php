@foreach ($visitors as $visitor)
    <tr>
        <td>{{ $visitor->visitor_identity_card }}</td>
        <td>{{ $visitor->visitor_name }}</td>
        <td>{{ $visitor->visitor_company }}</td>
        <td>{{ $visitor->visitor_reason_to_meet }}</td>
        <td>{{ $visitor->department ? $visitor->department->department_name : 'N/A' }}</td>
        <td>{{ $visitor->card ? $visitor->card->code : 'N/A' }}</td>
        <td>{{ $visitor->visitor_card ?? 'N/A' }}</td>
        <td>{{ $visitor->visitor_photo && $visitor->visitor_photo !== 'N/A' ? $visitor->visitor_photo : 'N/A' }}</td>
        <td>{{ \Carbon\Carbon::parse($visitor->visitor_enter_time)->format('d/m/Y H:i') }}</td>

        <td>

            @if ($visitor->visitor_out_time)
                {{ \Carbon\Carbon::parse($visitor->visitor_out_time)->format('d/m/Y H:i') }}
            @else
                <form action="{{ route('visitor.exit', $visitor->id) }}" method="POST" class="exit-form">

                    @csrf
                    <button type="submit" class="btn btn-soft-danger btn-sm">Registrar Salida</button>
                </form>
            @endif
        </td>

        <td>
            <div>
                <a href="{{ $visitor->visitor_out_time ? 'javascript:void(0);' : route('visitor.edit', $visitor->id) }}"
                    class="btn btn-warning btn-sm" @if ($visitor->visitor_out_time) disabled @endif>

                    Editar
                </a>
            </div>

            <div style="margin-top: 5px;">

                @if (!$visitor->visitor_out_time)
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm"
                        onclick="confirmDelete({{ $visitor->id }})">

                        Eliminar
                    </a>
                    <form id="delete-form-{{ $visitor->id }}" action="{{ route('visitor.delete', $visitor->id) }}"
                        method="POST" style="display: none;">

                        @csrf
                        @method('DELETE')
                    </form>
                @else
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" disabled>Eliminar</a>
                @endif
            </div>
        </td>
    </tr>
@endforeach
