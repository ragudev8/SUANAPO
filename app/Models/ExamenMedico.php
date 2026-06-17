<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenMedico extends Model
{
    protected $table = 'examenes_medicos';

    protected $fillable = [
        'paciente_id', 'tipo', 'fecha_examen', 'resultados_sangre', 'cardiograma',
        'ultrasonido_abdominal', 'rayos_x_torax', 'rayos_x_lumbar', 'aprobado',
        'notas_medicas', 'medico_aprobador_id', 'pdf_ruta',
    ];

    protected function casts(): array
    {
        return [
            'fecha_examen' => 'date',
            'cardiograma' => 'boolean',
            'ultrasonido_abdominal' => 'boolean',
            'rayos_x_torax' => 'boolean',
            'rayos_x_lumbar' => 'boolean',
            'aprobado' => 'boolean',
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medicoAprobador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'medico_aprobador_id');
    }
}
