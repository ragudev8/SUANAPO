<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->string('tipo_consulta', 20)->default('interna')->after('medico_id');
        });

        $internaId = DB::table('especialidades')->where('nombre', 'Consulta interna')->value('id');
        $externaId = DB::table('especialidades')->where('nombre', 'Consulta externa')->value('id');
        $generalId = DB::table('especialidades')->where('nombre', 'Medicina general')->value('id');

        if (! $generalId) {
            $generalId = DB::table('especialidades')->insertGetId([
                'nombre' => 'Medicina general',
                'descripcion' => 'Consulta medica general',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($externaId) {
            DB::table('citas')
                ->where('especialidad_id', $externaId)
                ->update(['tipo_consulta' => 'externa', 'especialidad_id' => $generalId]);
        }

        if ($internaId) {
            DB::table('citas')
                ->where('especialidad_id', $internaId)
                ->update(['tipo_consulta' => 'interna', 'especialidad_id' => $generalId]);
        }
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn('tipo_consulta');
        });
    }
};
