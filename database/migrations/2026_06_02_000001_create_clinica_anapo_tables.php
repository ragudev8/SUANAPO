<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('dni', 20)->unique()->nullable();
            $table->enum('rol', ['super_admin', 'admin', 'medico', 'enfermero', 'enfermero_media', 'licenciado_enfermeria', 'soporte_ti', 'docente', 'administrativo_academia', 'paciente', 'auditor'])->default('paciente');
            $table->string('colegiatura', 100)->nullable();
            $table->foreignId('especialidad_id')->nullable()->constrained('especialidades')->nullOnDelete();
            $table->longText('firma_digital')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['rol', 'activo']);
        });

        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->unique()->constrained('usuarios')->nullOnDelete();
            $table->string('nombre');
            $table->string('dni', 20)->unique();
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['M', 'F', 'Otro']);
            $table->enum('grado_militar', ['Cadete', 'Oficial', 'Escala_Basica', 'Personal_Administrativo', 'Civil', 'Beneficiario', 'Instructor', 'Aspirante', 'Personal']);
            $table->string('numero_placa', 50)->nullable();
            $table->enum('tipo_sangre', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->nullable();
            $table->text('alergias')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
            $table->index(['nombre', 'dni']);
            $table->index(['grado_militar', 'tipo_sangre']);
        });

        Schema::create('expedientes_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->unique()->constrained('pacientes')->cascadeOnDelete();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('antecedentes_personales')->nullable();
            $table->text('antecedentes_quirurgicos')->nullable();
            $table->timestamps();
        });

        Schema::create('libro_visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->date('fecha_visita');
            $table->unsignedInteger('numero_orden');
            $table->time('hora_llegada');
            $table->enum('estado', ['registrado', 'preclinica', 'esperando_medico', 'en_consulta', 'en_farmacia', 'en_procedimiento', 'finalizado'])->default('registrado');
            $table->foreignId('registrado_por_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamps();
            $table->unique(['fecha_visita', 'numero_orden']);
            $table->index(['estado', 'fecha_visita']);
        });

        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libro_visitas_id')->nullable()->constrained('libro_visitas')->nullOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->foreignId('especialidad_id')->nullable()->constrained('especialidades')->nullOnDelete();
            $table->dateTime('fecha_hora')->nullable();
            $table->unsignedInteger('duracion_estimada')->default(15);
            $table->enum('estado', ['registrada', 'en_preclinica', 'en_consulta', 'completada', 'cancelada'])->default('registrada');
            $table->boolean('completada')->default(false);
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
            $table->index(['estado', 'fecha_hora']);
        });

        Schema::create('preclinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->unsignedInteger('presion_sistolica')->nullable();
            $table->unsignedInteger('presion_diastolica')->nullable();
            $table->unsignedInteger('pulso')->nullable();
            $table->decimal('temperatura', 4, 2)->nullable();
            $table->decimal('peso', 6, 2)->nullable();
            $table->decimal('talla', 5, 2)->nullable();
            $table->text('notas_iniciales')->nullable();
            $table->foreignId('registrado_por_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('preclinica_id')->nullable()->constrained('preclinicas')->nullOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_id')->constrained('usuarios')->restrictOnDelete();
            $table->text('sintomas');
            $table->string('duracion_sintomas', 100)->nullable();
            $table->unsignedInteger('presion_sistolica')->nullable();
            $table->unsignedInteger('presion_diastolica')->nullable();
            $table->unsignedInteger('pulso')->nullable();
            $table->decimal('temperatura', 4, 2)->nullable();
            $table->decimal('peso', 6, 2)->nullable();
            $table->decimal('talla', 5, 2)->nullable();
            $table->text('notas_medicas');
            $table->text('tratamiento_prescrito')->nullable();
            $table->longText('firma_digital')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained('consultas')->cascadeOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->text('descripcion');
            $table->text('evolucion')->nullable();
            $table->boolean('resuelto')->default(false);
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();
        });

        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('presentacion', 100)->nullable();
            $table->string('dosis', 50)->nullable();
            $table->integer('cantidad_stock')->default(0);
            $table->integer('cantidad_minima')->default(10);
            $table->date('fecha_vencimiento')->nullable();
            $table->string('lote', 50)->nullable();
            $table->decimal('precio_costo', 8, 2)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->index(['fecha_vencimiento', 'cantidad_stock']);
        });

        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained('consultas')->restrictOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_id')->constrained('usuarios')->restrictOnDelete();
            $table->string('folio_unico', 50)->unique();
            $table->longText('codigo_qr');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('estado', ['activa', 'surtida', 'vencida', 'cancelada'])->default('activa');
            $table->longText('firma_digital');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->index(['fecha_emision', 'estado']);
        });

        Schema::create('detalles_receta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->restrictOnDelete();
            $table->string('dosis', 100)->nullable();
            $table->string('frecuencia', 100)->nullable();
            $table->unsignedInteger('cantidad_dias')->nullable();
            $table->unsignedInteger('cantidad_medicamento')->nullable();
            $table->boolean('dispensado')->default(false);
            $table->timestamp('fecha_dispensado')->nullable();
            $table->foreignId('dispensado_por_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('incapacidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_id')->constrained('usuarios')->restrictOnDelete();
            $table->foreignId('diagnostico_id')->nullable()->constrained('diagnosticos')->nullOnDelete();
            $table->date('fecha_inicio');
            $table->unsignedTinyInteger('dias_reposo');
            $table->date('fecha_fin');
            $table->text('motivo');
            $table->longText('firma_jefe_medico_digital');
            $table->longText('sello_clinica')->nullable();
            $table->string('pdf_ruta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('constancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_id')->constrained('usuarios')->restrictOnDelete();
            $table->enum('tipo', ['medica', 'dictamen']);
            $table->string('asunto')->nullable();
            $table->text('contenido');
            $table->longText('firma_medico_digital');
            $table->longText('sello_clinica')->nullable();
            $table->string('pdf_ruta')->nullable();
            $table->timestamps();
        });

        Schema::create('examenes_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->enum('tipo', ['ingreso', 'permanencia']);
            $table->date('fecha_examen');
            $table->text('resultados_sangre')->nullable();
            $table->boolean('cardiograma')->default(false);
            $table->boolean('ultrasonido_abdominal')->default(false);
            $table->boolean('rayos_x_torax')->default(false);
            $table->boolean('rayos_x_lumbar')->default(false);
            $table->boolean('aprobado')->default(false);
            $table->text('notas_medicas')->nullable();
            $table->foreignId('medico_aprobador_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('pdf_ruta')->nullable();
            $table->timestamps();
        });

        Schema::create('donaciones_sangre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_donante_id')->constrained('pacientes')->cascadeOnDelete();
            $table->enum('tipo_sangre', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']);
            $table->unsignedInteger('cantidad_unidades')->default(1);
            $table->date('fecha_donacion');
            $table->enum('estado_salud', ['apto', 'no_apto'])->default('apto');
            $table->text('notas_salud')->nullable();
            $table->foreignId('registrado_por_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamps();
            $table->index(['tipo_sangre', 'fecha_donacion']);
        });

        Schema::create('solicitudes_sangre', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_sangre', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']);
            $table->unsignedInteger('cantidad_unidades');
            $table->string('solicitante_nombre');
            $table->string('institucion')->nullable();
            $table->foreignId('director_id')->constrained('usuarios')->restrictOnDelete();
            $table->date('fecha_solicitud');
            $table->date('fecha_entrega')->nullable();
            $table->string('boleta_pdf_ruta')->nullable();
            $table->enum('estado', ['pendiente', 'entregada', 'rechazada'])->default('pendiente');
            $table->timestamps();
            $table->index(['estado', 'tipo_sangre']);
        });

        Schema::create('inventario_sangre', function (Blueprint $table) {
            $table->enum('tipo_sangre', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->primary();
            $table->integer('cantidad_disponible')->default(0);
            $table->timestamp('ultima_actualizacion')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('logs_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->enum('accion', ['view', 'create', 'update', 'delete', 'login', 'logout', 'download', 'print', 'sign', 'modify']);
            $table->string('tabla_accedida', 100)->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->json('cambios_json')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['usuario_id', 'created_at']);
            $table->index(['tabla_accedida', 'registro_id']);
        });

        Schema::create('cambios_pendientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('tabla');
            $table->string('operacion', 20);
            $table->json('payload');
            $table->enum('estado', ['pendiente', 'sincronizado', 'error'])->default('pendiente');
            $table->text('mensaje_error')->nullable();
            $table->timestamps();
            $table->index(['estado', 'created_at']);
        });
    }

    public function down(): void
    {
        foreach ([
            'cambios_pendientes', 'logs_auditoria', 'inventario_sangre', 'solicitudes_sangre',
            'donaciones_sangre', 'examenes_medicos', 'constancias', 'incapacidades',
            'detalles_receta', 'recetas', 'medicamentos', 'diagnosticos', 'consultas',
            'preclinicas', 'citas', 'libro_visitas', 'expedientes_medicos', 'pacientes',
            'usuarios', 'failed_jobs', 'jobs', 'cache_locks', 'cache', 'sessions', 'password_reset_tokens', 'especialidades',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
