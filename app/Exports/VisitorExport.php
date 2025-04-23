<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class VisitorExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    protected $visitors;

    public function __construct($visitors)
    {
        $this->visitors = $visitors;
    }

    public function collection()
    {
        return collect($this->visitors)->map(function ($v) {
            return [
                $v->visitor_name,
                $v->visitor_company,
                $v->visitor_identity_card,
                $v->visitor_reason_to_meet,
                $v->department->department_name ?? 'N/A',
                $v->card->code ?? 'N/A',
                $v->visitor_card ?? 'N/A',
                $v->visitor_photo ?? 'N/A',
                $v->visitor_enter_time,
                $v->visitor_out_time ?? 'No registrado'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Empresa',
            'Cédula',
            'Motivo',
            'Departamento',
            'Tarjeta Visitante',
            'Tarjeta Proveedor',
            'Herramientas / Dispositivos',
            'Hora de Entrada',
            'Hora de Salida'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
            'delimiter' => ','
        ];
    }
}