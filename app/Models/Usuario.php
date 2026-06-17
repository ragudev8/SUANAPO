<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 'email', 'password_hash', 'dni', 'numero_empleado', 'rol',
        'cargo', 'area_departamento', 'unidad_asignada', 'turno', 'fecha_ingreso',
        'colegiatura', 'telefono_institucional', 'celular', 'especialidad_id',
        'firma_digital', 'observaciones_admin', 'activo',
    ];

    protected $hidden = ['password_hash', 'remember_token'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_ingreso' => 'date',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function getNameAttribute(): string
    {
        return $this->nombre;
    }

    public function getRolLabelAttribute(): string
    {
        return self::roleLabel($this->rol);
    }

    public static function roleLabel(?string $role): string
    {
        return match ($role) {
            'super_admin' => 'Administrador general',
            'admin' => 'Administrador',
            'medico' => 'Doctor',
            'enfermero' => 'Enfermero',
            'enfermero_media' => 'Enfermero educacion media',
            'licenciado_enfermeria' => 'Licenciado en enfermeria',
            'soporte_ti' => 'Soporte TI',
            'docente' => 'Docente',
            'administrativo_academia' => 'Administrativo academia',
            'paciente' => 'Paciente',
            'auditor' => 'Auditor',
            default => ucfirst(str_replace('_', ' ', (string) $role)),
        };
    }

    public function getRolIconAttribute(): string
    {
        return self::roleIcon($this->rol);
    }

    public static function roleIcon(?string $role): string
    {
        return match ($role) {
            'super_admin', 'admin' => 'bi-shield-check',
            'medico' => 'bi-clipboard2-pulse',
            'enfermero', 'enfermero_media', 'licenciado_enfermeria' => 'bi-heart-pulse',
            'soporte_ti' => 'bi-pc-display',
            'docente' => 'bi-mortarboard',
            'administrativo_academia' => 'bi-briefcase',
            'paciente' => 'bi-person',
            'auditor' => 'bi-clipboard-data',
            default => 'bi-person-badge',
        };
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function paciente(): HasOne
    {
        return $this->hasOne(Paciente::class, 'usuario_id');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'medico_id');
    }

    public function esAdmin(): bool
    {
        return in_array($this->rol, ['super_admin', 'admin'], true);
    }

    public function canModule(string $module, string $action = 'view'): bool
    {
        $permissions = config("anapo.permissions.{$this->rol}", []);

        if (in_array($action, $permissions['*'] ?? [], true)) {
            return true;
        }

        return in_array($action, $permissions[$module] ?? [], true);
    }

    public function canAnyModule(string $module, array $actions): bool
    {
        foreach ($actions as $action) {
            if ($this->canModule($module, $action)) {
                return true;
            }
        }

        return false;
    }
}
