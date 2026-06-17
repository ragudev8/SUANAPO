<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosDemoSeeder extends Seeder
{
    public function run(): void
    {
        $especialidad = Especialidad::updateOrCreate(
            ['nombre' => 'Consulta interna'],
            ['descripcion' => 'Atencion para personal, cadetes y miembros internos de ANAPO', 'activa' => true],
        );

        Usuario::updateOrCreate(
            ['email' => 'medico@anapo.local'],
            [
                'nombre' => 'Medico General de Prueba',
                'password_hash' => Hash::make('TemporalPassword123!'),
                'dni' => '1111111111',
                'numero_empleado' => 'ANAPO-MED-001',
                'rol' => 'medico',
                'cargo' => 'Medico general',
                'area_departamento' => 'Consulta medica',
                'unidad_asignada' => 'Clinica ANAPO',
                'turno' => 'Matutino',
                'fecha_ingreso' => '2026-02-01',
                'telefono_institucional' => '2222-0101',
                'celular' => '9999-0101',
                'especialidad_id' => $especialidad->id,
                'colegiatura' => 'MED-001',
                'activo' => true,
            ],
        );
    }
}
