<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('numero_empleado', 50)->nullable()->after('dni');
            $table->string('cargo', 150)->nullable()->after('rol');
            $table->string('area_departamento', 150)->nullable()->after('cargo');
            $table->string('unidad_asignada', 150)->nullable()->after('area_departamento');
            $table->string('turno', 50)->nullable()->after('unidad_asignada');
            $table->date('fecha_ingreso')->nullable()->after('turno');
            $table->string('telefono_institucional', 30)->nullable()->after('colegiatura');
            $table->string('celular', 30)->nullable()->after('telefono_institucional');
            $table->text('observaciones_admin')->nullable()->after('firma_digital');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
                'numero_empleado',
                'cargo',
                'area_departamento',
                'unidad_asignada',
                'turno',
                'fecha_ingreso',
                'telefono_institucional',
                'celular',
                'observaciones_admin',
            ]);
        });
    }
};
