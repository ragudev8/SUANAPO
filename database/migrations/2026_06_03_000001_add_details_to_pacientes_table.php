<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('estado_civil', 50)->nullable()->after('sexo');
            $table->string('ocupacion')->nullable()->after('grado_militar');
            $table->string('unidad_dependencia')->nullable()->after('ocupacion');
            $table->string('celular', 20)->nullable()->after('telefono');
            $table->string('correo')->nullable()->after('celular');
            $table->string('contacto_emergencia_nombre')->nullable()->after('direccion');
            $table->string('contacto_emergencia_telefono', 20)->nullable()->after('contacto_emergencia_nombre');
            $table->string('responsable_nombre')->nullable()->after('contacto_emergencia_telefono');
            $table->string('responsable_parentesco', 100)->nullable()->after('responsable_nombre');
            $table->text('observaciones')->nullable()->after('alergias');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'estado_civil',
                'ocupacion',
                'unidad_dependencia',
                'celular',
                'correo',
                'contacto_emergencia_nombre',
                'contacto_emergencia_telefono',
                'responsable_nombre',
                'responsable_parentesco',
                'observaciones',
            ]);
        });
    }
};
