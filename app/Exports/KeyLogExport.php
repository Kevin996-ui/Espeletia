<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class KeyLogExport implements FromCollection, WithHeadings, WithCustomCsvSettings
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
                $log->name_taken,
                $log->identity_card_taken,
                $log->key_code,
                $log->area,
                $log->key_taken_at,
                $log->key_returned_at ?? 'No registrada',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Cédula',
            'Llave',
            'Área',
            'Fecha Retiro',
            'Fecha Devolución',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
            'delimiter' => ',',
        ];
    }
}