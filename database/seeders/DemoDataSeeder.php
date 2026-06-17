<?php

namespace Database\Seeders;

use App\Models\CambioPendiente;
use App\Models\Cita;
use App\Models\Constancia;
use App\Models\Consulta;
use App\Models\DetalleReceta;
use App\Models\Diagnostico;
use App\Models\DonacionSangre;
use App\Models\Especialidad;
use App\Models\ExamenMedico;
use App\Models\ExpedienteMedico;
use App\Models\Incapacidad;
use App\Models\InventarioSangre;
use App\Models\LibroVisita;
use App\Models\LogAuditoria;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Preclinica;
use App\Models\Receta;
use App\Models\Retroalimentacion;
use App\Models\SolicitudSangre;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        Especialidad::whereIn('nombre', ['Consulta interna', 'Consulta externa'])->update(['activa' => false]);

        $servicios = collect([
            ['Medicina general', 'Consulta medica general'],
            ['Odontologia', 'Atencion dental y salud bucal'],
            ['Ginecologia', 'Atencion ginecologica'],
            ['Psicologia', 'Atencion psicologica'],
            ['Nutricion', 'Atencion nutricional'],
            ['Fisioterapia', 'Rehabilitacion y terapia fisica'],
            ['Emergencia', 'Atencion inmediata o urgente'],
        ])->mapWithKeys(fn ($item) => [
            $item[0] => Especialidad::updateOrCreate(
                ['nombre' => $item[0]],
                ['descripcion' => $item[1], 'activa' => true],
            ),
        ]);

        $admin = $this->usuario('admin.demo@anapo.local', 'Admin Demo', '080119900001', 'admin', null);
        $medico = $this->usuario('medico.demo@anapo.local', 'Dra. Karla Mejia', '080119850002', 'medico', $servicios['Medicina general']->id, 'MED-2458');
        $enfermero = $this->usuario('enfermero.demo@anapo.local', 'Enfermero Luis Cruz', '080119920003', 'enfermero_media', $servicios['Medicina general']->id);
        $auditor = $this->usuario('auditor.demo@anapo.local', 'Auditor Clinico Demo', '080119880004', 'auditor', null);
        $director = $this->usuario('director.demo@anapo.local', 'Director Medico Demo', '080119800005', 'admin', null);
        $this->usuario('ti.demo@anapo.local', 'Tecnico TI Demo', '080119930006', 'soporte_ti', null);
        $this->usuario('docente.demo@anapo.local', 'Docente Demo', '080119870007', 'docente', null);
        $this->usuario('academia.demo@anapo.local', 'Administrativo Academia Demo', '080119910008', 'administrativo_academia', null);

        Incapacidad::where('motivo', 'Reposo medico por diagnostico demo.')->forceDelete();
        DonacionSangre::where('notas_salud', 'like', '%demo%')->delete();
        SolicitudSangre::whereIn('solicitante_nombre', ['Hospital Escuela Demo', 'Clinica Regional Demo'])->delete();
        LogAuditoria::where('user_agent', 'DemoDataSeeder')->delete();

        $pacientes = [
            $this->paciente([
                'nombre' => 'Jose Armando Lopez',
                'dni' => '0801200101010',
                'fecha_nacimiento' => '2001-04-12',
                'sexo' => 'M',
                'grado_militar' => 'Cadete',
                'estado_civil' => 'Soltero',
                'numero_placa' => 'CAD-102',
                'tipo_sangre' => 'O+',
                'ocupacion' => 'Cadete de segundo ano',
                'unidad_dependencia' => 'Compania Alfa',
                'telefono' => '2222-1010',
                'celular' => '9999-1010',
                'correo' => 'jose.lopez.demo@anapo.local',
                'direccion' => 'Residencia de cadetes, ANAPO',
                'alergias' => 'Penicilina',
                'observaciones' => 'Paciente demo para flujo de consulta interna.',
                'contacto_emergencia_nombre' => 'Maria Lopez',
                'contacto_emergencia_telefono' => '9888-1010',
                'responsable_nombre' => 'Carlos Lopez',
                'responsable_parentesco' => 'Padre',
            ], 'Asma en madre.', 'Rinitis alergica.', 'Apendicectomia en 2018.'),
            $this->paciente([
                'nombre' => 'Ana Gabriela Rivera',
                'dni' => '0801199502020',
                'fecha_nacimiento' => '1995-09-30',
                'sexo' => 'F',
                'grado_militar' => 'Oficial',
                'estado_civil' => 'Casado',
                'numero_placa' => 'OF-778',
                'tipo_sangre' => 'A+',
                'ocupacion' => 'Oficial instructora',
                'unidad_dependencia' => 'Direccion Academica',
                'telefono' => '2222-2020',
                'celular' => '9999-2020',
                'correo' => 'ana.rivera.demo@anapo.local',
                'direccion' => 'Tegucigalpa, Francisco Morazan',
                'alergias' => 'Sin alergias conocidas',
                'observaciones' => 'Control por cefalea recurrente.',
                'contacto_emergencia_nombre' => 'Mario Rivera',
                'contacto_emergencia_telefono' => '9888-2020',
            ], 'Hipertension en padre.', 'Migrana ocasional.', 'Sin cirugias.'),
            $this->paciente([
                'nombre' => 'Roberto Daniel Perez',
                'dni' => '0801198803030',
                'fecha_nacimiento' => '1988-02-14',
                'sexo' => 'M',
                'grado_militar' => 'Escala_Basica',
                'estado_civil' => 'Union libre',
                'numero_placa' => 'EB-305',
                'tipo_sangre' => 'B+',
                'ocupacion' => 'Agente asignado',
                'unidad_dependencia' => 'Seguridad interna',
                'telefono' => '2222-3030',
                'celular' => '9999-3030',
                'correo' => 'roberto.perez.demo@anapo.local',
                'direccion' => 'Comayaguela',
                'alergias' => 'Ibuprofeno',
                'observaciones' => 'Seguimiento por dolor lumbar.',
                'contacto_emergencia_nombre' => 'Sonia Perez',
                'contacto_emergencia_telefono' => '9888-3030',
            ], 'Diabetes familiar.', 'Lumbalgia mecanica.', 'Sin cirugias.'),
            $this->paciente([
                'nombre' => 'Lucia Fernanda Molina',
                'dni' => '0801200404040',
                'fecha_nacimiento' => '2004-11-05',
                'sexo' => 'F',
                'grado_militar' => 'Civil',
                'estado_civil' => 'Soltero',
                'tipo_sangre' => 'O-',
                'ocupacion' => 'Estudiante',
                'unidad_dependencia' => 'Consulta externa',
                'telefono' => '2222-4040',
                'celular' => '9999-4040',
                'correo' => 'lucia.molina.demo@anapo.local',
                'direccion' => 'Valle de Angeles',
                'alergias' => 'Sin registro',
                'observaciones' => 'Paciente civil demo para consulta externa.',
                'contacto_emergencia_nombre' => 'Teresa Molina',
                'contacto_emergencia_telefono' => '9888-4040',
            ], 'Sin datos relevantes.', 'Sin antecedentes relevantes.', 'Sin cirugias.'),
            $this->paciente([
                'nombre' => 'Manuel Antonio Castro',
                'dni' => '0801197005050',
                'fecha_nacimiento' => '1970-06-22',
                'sexo' => 'M',
                'grado_militar' => 'Beneficiario',
                'estado_civil' => 'Casado',
                'tipo_sangre' => 'AB+',
                'ocupacion' => 'Beneficiario',
                'unidad_dependencia' => 'Familia policial',
                'telefono' => '2222-5050',
                'celular' => '9999-5050',
                'correo' => 'manuel.castro.demo@anapo.local',
                'direccion' => 'Santa Lucia',
                'alergias' => 'Sulfas',
                'observaciones' => 'Control de presion arterial.',
                'contacto_emergencia_nombre' => 'Rosa Castro',
                'contacto_emergencia_telefono' => '9888-5050',
            ], 'Hipertension familiar.', 'Hipertension arterial.', 'Colecistectomia.'),
        ];

        $medicamentos = [
            $this->medicamento('Acetaminofen', 'Tableta', '500 mg', 220, 40, '2027-12-31', 'LOT-AC-001', 0.35),
            $this->medicamento('Amoxicilina', 'Capsula', '500 mg', 85, 30, '2027-05-15', 'LOT-AM-002', 1.10),
            $this->medicamento('Loratadina', 'Tableta', '10 mg', 130, 25, '2028-01-20', 'LOT-LR-003', 0.60),
            $this->medicamento('Ibuprofeno', 'Tableta', '400 mg', 18, 25, '2026-11-30', 'LOT-IB-004', 0.55),
            $this->medicamento('Suero oral', 'Sobre', '27.9 g', 60, 20, '2027-08-01', 'LOT-SO-005', 0.75),
        ];

        $today = Carbon::today();
        $estados = ['registrado', 'preclinica', 'esperando_medico', 'en_consulta', 'en_farmacia', 'finalizado'];
        $serviciosDemo = ['Medicina general', 'Odontologia', 'Ginecologia', 'Psicologia', 'Nutricion'];
        $consultas = [];

        foreach ($pacientes as $index => $paciente) {
            $fecha = $today->copy()->subDays($index % 3);
            $fechaKey = $fecha->copy()->startOfDay()->toDateTimeString();
            $visita = LibroVisita::updateOrCreate(
                ['fecha_visita' => $fechaKey, 'numero_orden' => $index + 1],
                [
                    'paciente_id' => $paciente->id,
                    'hora_llegada' => $fecha->copy()->setTime(7 + $index, 20)->format('H:i:s'),
                    'estado' => $estados[$index] ?? 'finalizado',
                    'registrado_por_id' => $enfermero->id,
                ],
            );

            $cita = Cita::updateOrCreate(
                ['libro_visitas_id' => $visita->id],
                [
                    'paciente_id' => $paciente->id,
                    'medico_id' => $medico->id,
                    'tipo_consulta' => in_array($paciente->grado_militar, ['Civil', 'Beneficiario'], true) ? 'externa' : 'interna',
                    'especialidad_id' => $servicios[$serviciosDemo[$index % count($serviciosDemo)]]->id,
                    'fecha_hora' => $fecha->copy()->setTime(8 + $index, 0),
                    'duracion_estimada' => 20,
                    'estado' => $index >= 3 ? 'completada' : 'en_consulta',
                    'completada' => $index >= 3,
                    'fecha_completado' => $index >= 3 ? $fecha->copy()->setTime(9 + $index, 0) : null,
                ],
            );

            $preclinica = Preclinica::updateOrCreate(
                ['cita_id' => $cita->id],
                [
                    'paciente_id' => $paciente->id,
                    'presion_sistolica' => 118 + ($index * 3),
                    'presion_diastolica' => 76 + ($index * 2),
                    'pulso' => 72 + $index,
                    'temperatura' => 36.5 + ($index / 10),
                    'peso' => 62 + ($index * 4),
                    'talla' => 1.62 + ($index / 100),
                    'notas_iniciales' => 'Signos vitales registrados para datos demo.',
                    'registrado_por_id' => $enfermero->id,
                ],
            );

            $consulta = Consulta::updateOrCreate(
                ['cita_id' => $cita->id],
                [
                    'preclinica_id' => $preclinica->id,
                    'paciente_id' => $paciente->id,
                    'medico_id' => $medico->id,
                    'sintomas' => ['Cefalea y malestar general', 'Dolor lumbar', 'Congestion nasal', 'Dolor abdominal leve', 'Control de presion'][$index],
                    'duracion_sintomas' => ($index + 1).' dias',
                    'presion_sistolica' => 118 + ($index * 3),
                    'presion_diastolica' => 76 + ($index * 2),
                    'pulso' => 72 + $index,
                    'temperatura' => 36.5 + ($index / 10),
                    'peso' => 62 + ($index * 4),
                    'talla' => 1.62 + ($index / 100),
                    'notas_medicas' => 'Evaluacion medica demo con paciente estable.',
                    'tratamiento_prescrito' => 'Hidratacion, reposo relativo y medicamento segun receta.',
                    'firma_digital' => 'firma-demo-medico',
                ],
            );
            $consultas[] = $consulta;

            $diagnostico = Diagnostico::updateOrCreate(
                ['consulta_id' => $consulta->id, 'descripcion' => 'Diagnostico demo '.($index + 1)],
                [
                    'paciente_id' => $paciente->id,
                    'evolucion' => $index >= 2 ? 'Evolucion favorable.' : 'Pendiente de seguimiento.',
                    'resuelto' => $index >= 2,
                    'fecha_resolucion' => $index >= 2 ? $fecha->copy()->addDays(3) : null,
                ],
            );

            if ($index < 3) {
                Incapacidad::updateOrCreate(
                    ['paciente_id' => $paciente->id, 'medico_id' => $medico->id, 'fecha_inicio' => $fecha->toDateString()],
                    [
                        'diagnostico_id' => $diagnostico->id,
                        'dias_reposo' => 2 + $index,
                        'fecha_fin' => $fecha->copy()->addDays(2 + $index)->toDateString(),
                        'motivo' => 'Reposo medico por diagnostico demo.',
                        'firma_jefe_medico_digital' => 'firma-demo-jefe',
                        'sello_clinica' => 'sello-demo-anapo',
                        'pdf_ruta' => 'demo/incapacidad-'.$paciente->id.'.pdf',
                    ],
                );
            }
        }

        foreach ($consultas as $index => $consulta) {
            $receta = Receta::updateOrCreate(
                ['folio_unico' => 'RX-DEMO-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'consulta_id' => $consulta->id,
                    'paciente_id' => $consulta->paciente_id,
                    'medico_id' => $medico->id,
                    'codigo_qr' => 'QR-DEMO-RX-'.($index + 1),
                    'fecha_emision' => $today->copy()->subDays($index)->toDateString(),
                    'fecha_vencimiento' => $today->copy()->addDays(15)->toDateString(),
                    'estado' => $index % 2 === 0 ? 'activa' : 'surtida',
                    'firma_digital' => 'firma-demo-receta',
                    'notas' => 'Receta demo generada para pruebas.',
                ],
            );

            DetalleReceta::updateOrCreate(
                ['receta_id' => $receta->id, 'medicamento_id' => $medicamentos[$index % count($medicamentos)]->id],
                [
                    'dosis' => $medicamentos[$index % count($medicamentos)]->dosis,
                    'frecuencia' => 'Cada 8 horas',
                    'cantidad_dias' => 3 + $index,
                    'cantidad_medicamento' => 9 + ($index * 3),
                    'dispensado' => $index % 2 !== 0,
                    'fecha_dispensado' => $index % 2 !== 0 ? $today->copy()->subDays($index)->setTime(10, 30) : null,
                    'dispensado_por_id' => $index % 2 !== 0 ? $enfermero->id : null,
                ],
            );
        }

        Constancia::updateOrCreate(
            ['paciente_id' => $pacientes[0]->id, 'medico_id' => $medico->id, 'tipo' => 'medica'],
            [
                'asunto' => 'Constancia medica demo',
                'contenido' => 'Se hace constar que el paciente fue evaluado en Clinica ANAPO.',
                'firma_medico_digital' => 'firma-demo-medico',
                'sello_clinica' => 'sello-demo-anapo',
                'pdf_ruta' => 'demo/constancia-medica.pdf',
            ],
        );
        Constancia::updateOrCreate(
            ['paciente_id' => $pacientes[1]->id, 'medico_id' => $medico->id, 'tipo' => 'dictamen'],
            [
                'asunto' => 'Dictamen medico demo',
                'contenido' => 'Paciente apto para actividades regulares con seguimiento.',
                'firma_medico_digital' => 'firma-demo-medico',
                'sello_clinica' => 'sello-demo-anapo',
                'pdf_ruta' => 'demo/dictamen-medico.pdf',
            ],
        );

        foreach ($pacientes as $index => $paciente) {
            ExamenMedico::updateOrCreate(
                ['paciente_id' => $paciente->id, 'tipo' => $index % 2 === 0 ? 'ingreso' : 'permanencia'],
                [
                    'fecha_examen' => $today->copy()->subDays(20 + $index)->toDateString(),
                    'resultados_sangre' => 'Hemograma dentro de parametros demo.',
                    'cardiograma' => $index % 2 === 0,
                    'ultrasonido_abdominal' => $index % 3 === 0,
                    'rayos_x_torax' => true,
                    'rayos_x_lumbar' => $index === 2,
                    'aprobado' => $index !== 3,
                    'notas_medicas' => 'Examen medico demo registrado.',
                    'medico_aprobador_id' => $medico->id,
                    'pdf_ruta' => 'demo/examen-'.$paciente->id.'.pdf',
                ],
            );
        }

        foreach (['O+' => 8, 'O-' => 2, 'A+' => 6, 'A-' => 1, 'B+' => 4, 'B-' => 1, 'AB+' => 3, 'AB-' => 1] as $tipo => $cantidad) {
            InventarioSangre::updateOrCreate(
                ['tipo_sangre' => $tipo],
                ['cantidad_disponible' => $cantidad, 'ultima_actualizacion' => now()],
            );
        }

        foreach ($pacientes as $index => $paciente) {
            DonacionSangre::updateOrCreate(
                ['paciente_donante_id' => $paciente->id, 'fecha_donacion' => $today->copy()->subDays(30 + $index)->toDateString()],
                [
                    'tipo_sangre' => $paciente->tipo_sangre ?? 'O+',
                    'cantidad_unidades' => 1,
                    'estado_salud' => $index === 3 ? 'no_apto' : 'apto',
                    'notas_salud' => $index === 3 ? 'No apto por hemoglobina baja demo.' : 'Donante apto demo.',
                    'registrado_por_id' => $enfermero->id,
                ],
            );
        }

        SolicitudSangre::updateOrCreate(
            ['solicitante_nombre' => 'Hospital Escuela Demo', 'fecha_solicitud' => $today->copy()->subDays(4)->toDateString()],
            [
                'paciente_id' => $pacientes[2]->id,
                'donante_asignado_id' => $pacientes[0]->id,
                'tipo_sangre' => 'O+',
                'cantidad_unidades' => 2,
                'institucion' => 'Hospital Escuela',
                'director_id' => $director->id,
                'fecha_entrega' => $today->copy()->subDays(2)->toDateString(),
                'boleta_pdf_ruta' => 'demo/solicitud-sangre-1.pdf',
                'estado' => 'entregada',
                'indicaciones' => 'Donante asignado debe presentarse al banco de sangre del Hospital Escuela.',
            ],
        );
        SolicitudSangre::updateOrCreate(
            ['solicitante_nombre' => 'Clinica Regional Demo', 'fecha_solicitud' => $today->toDateString()],
            [
                'paciente_id' => $pacientes[4]->id,
                'donante_asignado_id' => $pacientes[1]->id,
                'tipo_sangre' => 'A+',
                'cantidad_unidades' => 1,
                'institucion' => 'Clinica Regional',
                'director_id' => $director->id,
                'fecha_entrega' => null,
                'boleta_pdf_ruta' => null,
                'estado' => 'pendiente',
                'indicaciones' => 'Coordinar traslado del donante con la unidad correspondiente.',
            ],
        );

        foreach ([
            ['usuario_id' => $admin->id, 'accion' => 'login', 'tabla_accedida' => 'usuarios', 'registro_id' => $admin->id],
            ['usuario_id' => $medico->id, 'accion' => 'view', 'tabla_accedida' => 'pacientes', 'registro_id' => $pacientes[0]->id],
            ['usuario_id' => $enfermero->id, 'accion' => 'create', 'tabla_accedida' => 'preclinicas', 'registro_id' => $consultas[0]->preclinica_id],
            ['usuario_id' => $auditor->id, 'accion' => 'download', 'tabla_accedida' => 'reportes', 'registro_id' => null],
        ] as $index => $log) {
            LogAuditoria::updateOrCreate(
                ['usuario_id' => $log['usuario_id'], 'accion' => $log['accion'], 'tabla_accedida' => $log['tabla_accedida']],
                [
                    'registro_id' => $log['registro_id'],
                    'cambios_json' => ['demo' => true, 'orden' => $index + 1],
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'DemoDataSeeder',
                    'created_at' => now()->subMinutes($index * 7),
                ],
            );
        }

        CambioPendiente::updateOrCreate(
            ['tabla' => 'pacientes', 'operacion' => 'update'],
            [
                'usuario_id' => $admin->id,
                'payload' => ['dni' => $pacientes[0]->dni, 'observacion' => 'Cambio demo pendiente de sincronizacion'],
                'estado' => 'pendiente',
                'mensaje_error' => null,
            ],
        );
        CambioPendiente::updateOrCreate(
            ['tabla' => 'medicamentos', 'operacion' => 'create'],
            [
                'usuario_id' => $admin->id,
                'payload' => ['nombre' => 'Medicamento offline demo'],
                'estado' => 'sincronizado',
                'mensaje_error' => null,
            ],
        );

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'usuario.demo@anapo.local'],
            ['token' => 'token-demo-no-valido', 'created_at' => now()],
        );
        DB::table('cache')->updateOrInsert(
            ['key' => 'demo_kpi_pacientes'],
            ['value' => '5', 'expiration' => now()->addDay()->timestamp],
        );
        DB::table('cache_locks')->updateOrInsert(
            ['key' => 'demo_lock_reportes'],
            ['owner' => 'DemoDataSeeder', 'expiration' => now()->addMinutes(10)->timestamp],
        );
        DB::table('jobs')->updateOrInsert(
            ['queue' => 'demo', 'payload' => '{"job":"DemoJob"}'],
            ['attempts' => 0, 'reserved_at' => null, 'available_at' => now()->timestamp, 'created_at' => now()->timestamp],
        );
        DB::table('failed_jobs')->updateOrInsert(
            ['uuid' => 'demo-failed-job-uuid'],
            ['connection' => 'sync', 'queue' => 'demo', 'payload' => '{"job":"DemoFailedJob"}', 'exception' => 'Excepcion demo', 'failed_at' => now()],
        );
        DB::table('sessions')->updateOrInsert(
            ['id' => 'demo-session-id'],
            [
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'DemoDataSeeder',
                'payload' => 'payload-demo',
                'last_activity' => now()->timestamp,
            ],
        );

        Retroalimentacion::updateOrCreate(
            ['usuario_id' => $medico->id, 'asunto' => 'Agregar filtro por tipo de paciente'],
            [
                'modulo' => 'pacientes',
                'tipo' => 'mejora',
                'prioridad' => 'media',
                'mensaje' => 'Seria util filtrar pacientes por cadete, oficial, civil o beneficiario desde la lista principal.',
                'estado' => 'pendiente',
            ],
        );
        Retroalimentacion::updateOrCreate(
            ['usuario_id' => $enfermero->id, 'asunto' => 'Mejorar vista de dispensacion en celular'],
            [
                'modulo' => 'atenciones',
                'tipo' => 'diseno',
                'prioridad' => 'alta',
                'mensaje' => 'En farmacia se usa mucho desde pantalla pequena. Conviene que la validacion de receta se vea mas compacta.',
                'estado' => 'revisando',
                'respuesta_admin' => 'Se revisara junto con los ajustes responsive de farmacia.',
                'revisado_por_id' => $admin->id,
                'revisado_en' => now(),
            ],
        );
    }

    private function usuario(string $email, string $nombre, string $dni, string $rol, ?int $especialidadId, ?string $colegiatura = null): Usuario
    {
        $laboral = match ($rol) {
            'medico' => [
                'cargo' => 'Medico general',
                'area_departamento' => 'Consulta medica',
                'turno' => 'Matutino',
                'numero_empleado' => 'ANAPO-MED-'.substr($dni, -4),
                'telefono_institucional' => '2222-1100',
                'celular' => '9999-1100',
            ],
            'enfermero', 'enfermero_media' => [
                'cargo' => 'Enfermero de preclinica',
                'area_departamento' => 'Preclinica y farmacia',
                'turno' => 'Rotativo',
                'numero_empleado' => 'ANAPO-ENF-'.substr($dni, -4),
                'telefono_institucional' => '2222-1200',
                'celular' => '9999-1200',
            ],
            'licenciado_enfermeria' => [
                'cargo' => 'Licenciado en enfermeria',
                'area_departamento' => 'Preclinica, farmacia y procedimientos',
                'turno' => 'Rotativo',
                'numero_empleado' => 'ANAPO-LENF-'.substr($dni, -4),
                'telefono_institucional' => '2222-1250',
                'celular' => '9999-1250',
            ],
            'soporte_ti' => [
                'cargo' => 'Tecnico de soporte TI',
                'area_departamento' => 'Tecnologia',
                'turno' => 'Administrativo',
                'numero_empleado' => 'ANAPO-TI-'.substr($dni, -4),
                'telefono_institucional' => '2222-1400',
                'celular' => '9999-1400',
            ],
            'docente' => [
                'cargo' => 'Docente',
                'area_departamento' => 'Formacion academica',
                'turno' => 'Administrativo',
                'numero_empleado' => 'ANAPO-DOC-'.substr($dni, -4),
                'telefono_institucional' => '2222-1500',
                'celular' => '9999-1500',
            ],
            'administrativo_academia' => [
                'cargo' => 'Administrativo academia',
                'area_departamento' => 'Administracion academica',
                'turno' => 'Administrativo',
                'numero_empleado' => 'ANAPO-ACAD-'.substr($dni, -4),
                'telefono_institucional' => '2222-1600',
                'celular' => '9999-1600',
            ],
            'auditor' => [
                'cargo' => 'Auditor clinico',
                'area_departamento' => 'Auditoria',
                'turno' => 'Administrativo',
                'numero_empleado' => 'ANAPO-AUD-'.substr($dni, -4),
                'telefono_institucional' => '2222-1300',
                'celular' => '9999-1300',
            ],
            default => [
                'cargo' => 'Administrador del sistema',
                'area_departamento' => 'Administracion clinica',
                'turno' => 'Administrativo',
                'numero_empleado' => 'ANAPO-ADM-'.substr($dni, -4),
                'telefono_institucional' => '2222-1000',
                'celular' => '9999-1000',
            ],
        };

        $usuario = Usuario::where('email', $email)->orWhere('dni', $dni)->first() ?? new Usuario();

        $usuario->fill([
            'nombre' => $nombre,
            'email' => $email,
            'password_hash' => Hash::make('TemporalPassword123!'),
            'dni' => $dni,
            'numero_empleado' => $laboral['numero_empleado'],
            'rol' => $rol,
            'cargo' => $laboral['cargo'],
            'area_departamento' => $laboral['area_departamento'],
            'unidad_asignada' => 'Clinica ANAPO',
            'turno' => $laboral['turno'],
            'fecha_ingreso' => '2026-01-15',
            'colegiatura' => $colegiatura,
            'telefono_institucional' => $laboral['telefono_institucional'],
            'celular' => $laboral['celular'],
            'especialidad_id' => $especialidadId,
            'firma_digital' => $rol === 'medico' ? 'firma-digital-demo' : null,
            'observaciones_admin' => 'Usuario demo con perfil laboral para pruebas del sistema.',
            'activo' => true,
        ])->save();

        return $usuario;
    }

    private function paciente(array $data, string $familiares, string $personales, string $quirurgicos): Paciente
    {
        $paciente = Paciente::updateOrCreate(['dni' => $data['dni']], $data);
        ExpedienteMedico::updateOrCreate(
            ['paciente_id' => $paciente->id],
            [
                'antecedentes_familiares' => $familiares,
                'antecedentes_personales' => $personales,
                'antecedentes_quirurgicos' => $quirurgicos,
            ],
        );

        return $paciente;
    }

    private function medicamento(string $nombre, string $presentacion, string $dosis, int $stock, int $minimo, string $vence, string $lote, float $costo): Medicamento
    {
        return Medicamento::updateOrCreate(
            ['nombre' => $nombre],
            [
                'presentacion' => $presentacion,
                'dosis' => $dosis,
                'cantidad_stock' => $stock,
                'cantidad_minima' => $minimo,
                'fecha_vencimiento' => $vence,
                'lote' => $lote,
                'precio_costo' => $costo,
                'activo' => true,
            ],
        );
    }
}
