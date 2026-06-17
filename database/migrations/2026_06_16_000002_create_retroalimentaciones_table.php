<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retroalimentaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('modulo', 80)->nullable();
            $table->enum('tipo', ['mejora', 'error', 'nuevo_modulo', 'diseno', 'otro'])->default('mejora');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->string('asunto', 180);
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'revisando', 'aceptada', 'cerrada'])->default('pendiente');
            $table->text('respuesta_admin')->nullable();
            $table->foreignId('revisado_por_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamp('revisado_en')->nullable();
            $table->timestamps();

            $table->index(['estado', 'prioridad']);
            $table->index(['usuario_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retroalimentaciones');
    }
};
