<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_sangre', function (Blueprint $table) {
            $table->foreignId('paciente_id')->nullable()->after('id')->constrained('pacientes')->nullOnDelete();
            $table->foreignId('donante_asignado_id')->nullable()->after('paciente_id')->constrained('pacientes')->nullOnDelete();
            $table->text('indicaciones')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_sangre', function (Blueprint $table) {
            $table->dropConstrainedForeignId('paciente_id');
            $table->dropConstrainedForeignId('donante_asignado_id');
            $table->dropColumn('indicaciones');
        });
    }
};
