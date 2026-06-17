<?php

namespace App\Exports;

use App\Models\LogAuditoria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditoriaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return LogAuditoria::with('usuario')->latest('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Usuario',
            'Email',
            'Accion',
            'Tabla o ruta',
            'Registro ID',
            'IP',
            'Navegador / agente',
            'Cambios',
        ];
    }

    public function map($log): array
    {
        return [
            optional($log->created_at)->format('d/m/Y H:i:s'),
            $log->usuario?->nombre ?? 'Sistema',
            $log->usuario?->email ?? '',
            $log->accion,
            $log->tabla_accedida,
            $log->registro_id,
            $log->ip_address,
            $log->user_agent,
            $log->cambios_json ? json_encode($log->cambios_json, JSON_UNESCAPED_UNICODE) : '',
        ];
    }
}
