<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        $this->rebuildPacientesTable(
            "'Cadete', 'Oficial', 'Escala_Basica', 'Personal_Administrativo', 'Civil', 'Beneficiario', 'Instructor', 'Aspirante', 'Personal'",
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::table('pacientes')
            ->whereNotIn('grado_militar', ['Oficial', 'Escala_Basica', 'Cadete', 'Personal', 'Beneficiario'])
            ->update(['grado_militar' => 'Beneficiario']);

        $this->rebuildPacientesTable("'Oficial', 'Escala_Basica', 'Cadete', 'Personal', 'Beneficiario'");
    }

    private function rebuildPacientesTable(string $allowedTypes): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement("
            CREATE TABLE pacientes_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                usuario_id INTEGER NULL,
                nombre VARCHAR NOT NULL,
                dni VARCHAR(20) NOT NULL,
                fecha_nacimiento DATE NOT NULL,
                sexo VARCHAR CHECK (sexo IN ('M', 'F', 'Otro')) NOT NULL,
                grado_militar VARCHAR CHECK (grado_militar IN ({$allowedTypes})) NOT NULL,
                estado_civil VARCHAR(50) NULL,
                ocupacion VARCHAR NULL,
                unidad_dependencia VARCHAR NULL,
                numero_placa VARCHAR(50) NULL,
                tipo_sangre VARCHAR CHECK (tipo_sangre IN ('O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-')) NULL,
                alergias TEXT NULL,
                observaciones TEXT NULL,
                telefono VARCHAR(20) NULL,
                celular VARCHAR(20) NULL,
                correo VARCHAR NULL,
                direccion VARCHAR NULL,
                contacto_emergencia_nombre VARCHAR NULL,
                contacto_emergencia_telefono VARCHAR(20) NULL,
                responsable_nombre VARCHAR NULL,
                responsable_parentesco VARCHAR(100) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
            )
        ");

        DB::statement("
            INSERT INTO pacientes_new (
                id, usuario_id, nombre, dni, fecha_nacimiento, sexo, grado_militar,
                estado_civil, ocupacion, unidad_dependencia, numero_placa, tipo_sangre,
                alergias, observaciones, telefono, celular, correo, direccion,
                contacto_emergencia_nombre, contacto_emergencia_telefono,
                responsable_nombre, responsable_parentesco, created_at, updated_at
            )
            SELECT
                id, usuario_id, nombre, dni, fecha_nacimiento, sexo, grado_militar,
                estado_civil, ocupacion, unidad_dependencia, numero_placa, tipo_sangre,
                alergias, observaciones, telefono, celular, correo, direccion,
                contacto_emergencia_nombre, contacto_emergencia_telefono,
                responsable_nombre, responsable_parentesco, created_at, updated_at
            FROM pacientes
        ");

        DB::statement('DROP TABLE pacientes');
        DB::statement('ALTER TABLE pacientes_new RENAME TO pacientes');
        DB::statement('CREATE UNIQUE INDEX pacientes_usuario_id_unique ON pacientes (usuario_id)');
        DB::statement('CREATE UNIQUE INDEX pacientes_dni_unique ON pacientes (dni)');
        DB::statement('CREATE INDEX pacientes_nombre_dni_index ON pacientes (nombre, dni)');
        DB::statement('CREATE INDEX pacientes_grado_militar_tipo_sangre_index ON pacientes (grado_militar, tipo_sangre)');

        DB::statement('PRAGMA foreign_keys=ON');
    }
};
