<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE usuarios MODIFY rol ENUM('super_admin', 'admin', 'medico', 'enfermero', 'enfermero_media', 'licenciado_enfermeria', 'paciente', 'auditor') DEFAULT 'paciente'");
        DB::table('usuarios')->where('rol', 'enfermero')->update(['rol' => 'enfermero_media']);
    }

    public function down(): void
    {
        DB::table('usuarios')
            ->whereIn('rol', ['enfermero_media', 'licenciado_enfermeria'])
            ->update(['rol' => 'enfermero']);

        DB::statement("ALTER TABLE usuarios MODIFY rol ENUM('super_admin', 'admin', 'medico', 'enfermero', 'paciente', 'auditor') DEFAULT 'paciente'");
    }
};
