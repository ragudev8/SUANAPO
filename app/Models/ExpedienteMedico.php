<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpedienteMedico extends Model
{
    protected $table = 'expedientes_medicos';

    protected $fillable = [
        'paciente_id', 'antecedentes_familiares', 'antecedentes_personales', 'antecedentes_quirurgicos',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
