<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class KeyLogExport implements FromCollection
{
    protected $keyLogs;

    public function __construct($keyLogs)
    {
        $this->keyLogs = $keyLogs;
    }

    public function collection()
    {
        return collect($this->keyLogs)->map(function ($log) {
            return [
                'Nombre' => $log->name_taken,
                'Cédula' => $log->identity_card_taken,
                'Llave' => $log->key_code,
                'Área' => $log->area,
                'Fecha Retiro' => $log->key_taken_at,
                'Fecha Devolución' => $log->key_returned_at ?? 'No registrada',
            ];
        });
    }
}