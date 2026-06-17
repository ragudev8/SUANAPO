<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE usuarios MODIFY rol ENUM('super_admin', 'admin', 'medico', 'enfermero', 'enfermero_media', 'licenciado_enfermeria', 'soporte_ti', 'docente', 'administrativo_academia', 'paciente', 'auditor') DEFAULT 'paciente'");
    }

    public function down(): void
    {
        DB::table('usuarios')
            ->whereIn('rol', ['soporte_ti', 'docente', 'administrativo_academia'])
            ->update(['rol' => 'paciente']);

        DB::statement("ALTER TABLE usuarios MODIFY rol ENUM('super_admin', 'admin', 'medico', 'enfermero', 'enfermero_media', 'licenciado_enfermeria', 'paciente', 'auditor') DEFAULT 'paciente'");
    }
};
