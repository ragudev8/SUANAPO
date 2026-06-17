<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incapacidad extends Model
{
    use SoftDeletes;

    protected $table = 'incapacidades';

    protected $fillable = [
        'paciente_id', 'medico_id', 'diagnostico_id', 'fecha_inicio', 'dias_reposo',
        'lugar_reposo', 'fecha_fin', 'motivo', 'firma_jefe_medico_digital', 'sello_clinica', 'pdf_ruta',
    ];

    protected function casts(): array
    {
        return ['fecha_inicio' => 'date', 'fecha_fin' => 'date'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'medico_id');
    }
}
