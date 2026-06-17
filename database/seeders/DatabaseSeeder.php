<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use App\Models\InventarioSangre;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Especialidad::whereIn('nombre', ['Consulta interna', 'Consulta externa'])->update(['activa' => false]);

        foreach ([
            ['Medicina general', 'Consulta medica general'],
            ['Odontologia', 'Atencion dental y salud bucal'],
            ['Ginecologia', 'Atencion ginecologica'],
            ['Psicologia', 'Atencion psicologica'],
            ['Nutricion', 'Atencion nutricional'],
            ['Fisioterapia', 'Rehabilitacion y terapia fisica'],
            ['Emergencia', 'Atencion inmediata o urgente'],
        ] as [$nombre, $descripcion]) {
            Especialidad::updateOrCreate(['nombre' => $nombre], ['descripcion' => $descripcion, 'activa' => true]);
        }

        Usuario::updateOrCreate(
            ['email' => env('ANAPO_ADMIN_EMAIL', 'jefe@anapo.local')],
            [
                'nombre' => 'Jefe de Clinica',
                'password_hash' => Hash::make(env('ANAPO_ADMIN_PASSWORD', 'TemporalPassword123!')),
                'dni' => '0000000000',
                'numero_empleado' => 'ANAPO-ADM-001',
                'rol' => 'super_admin',
                'cargo' => 'Jefe de Clinica',
                'area_departamento' => 'Direccion Clinica',
                'unidad_asignada' => 'Clinica ANAPO',
                'turno' => 'Administrativo',
                'fecha_ingreso' => '2026-01-01',
                'telefono_institucional' => '2222-0000',
                'celular' => '9999-0000',
                'observaciones_admin' => 'Cuenta principal para administracion general del sistema.',
                'activo' => true,
            ],
        );

        foreach (['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $tipo) {
            InventarioSangre::updateOrCreate(['tipo_sangre' => $tipo], ['cantidad_disponible' => 0]);
        }
    }
}
