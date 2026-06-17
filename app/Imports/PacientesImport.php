<?php

namespace App\Imports;

use App\Models\ExpedienteMedico;
use App\Models\Paciente;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PacientesImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $updated = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $dni = trim((string) ($row['dni'] ?? ''));
            $nombre = trim((string) ($row['nombre'] ?? ''));

            if ($dni === '' || $nombre === '') {
                continue;
            }

            $paciente = Paciente::updateOrCreate(
                ['dni' => $dni],
                [
                    'nombre' => $nombre,
                    'fecha_nacimiento' => $row['fecha_nacimiento'] ?? now()->subYears(18)->toDateString(),
                    'sexo' => in_array($row['sexo'] ?? '', ['M', 'F', 'Otro'], true) ? $row['sexo'] : 'Otro',
                    'estado_civil' => $row['estado_civil'] ?? null,
                    'grado_militar' => $this->grado((string) ($row['grado_militar'] ?? 'Personal')),
                    'ocupacion' => $row['ocupacion'] ?? null,
                    'unidad_dependencia' => $row['unidad_dependencia'] ?? null,
                    'numero_placa' => $row['numero_placa'] ?? null,
                    'tipo_sangre' => $this->tipoSangre($row['tipo_sangre'] ?? null),
                    'alergias' => $row['alergias'] ?? null,
                    'observaciones' => $row['observaciones'] ?? null,
                    'telefono' => $row['telefono'] ?? null,
                    'celular' => $row['celular'] ?? null,
                    'correo' => $row['correo'] ?? null,
                    'direccion' => $row['direccion'] ?? null,
                    'contacto_emergencia_nombre' => $row['contacto_emergencia_nombre'] ?? null,
                    'contacto_emergencia_telefono' => $row['contacto_emergencia_telefono'] ?? null,
                    'responsable_nombre' => $row['responsable_nombre'] ?? null,
                    'responsable_parentesco' => $row['responsable_parentesco'] ?? null,
                ],
            );

            $paciente->wasRecentlyCreated ? $this->created++ : $this->updated++;
            ExpedienteMedico::updateOrCreate(
                ['paciente_id' => $paciente->id],
                [
                    'antecedentes_familiares' => $row['antecedentes_familiares'] ?? null,
                    'antecedentes_personales' => $row['antecedentes_personales'] ?? null,
                    'antecedentes_quirurgicos' => $row['antecedentes_quirurgicos'] ?? null,
                ],
            );
        }
    }

    private function grado(string $value): string
    {
        $normalized = str_replace([' ', '-'], '_', trim($value));
        $aliases = [
            'Escala_Basica' => 'Escala_Basica',
            'Escala_Basica_Policial' => 'Escala_Basica',
            'Basica' => 'Escala_Basica',
            'Personal_Admin' => 'Personal_Administrativo',
            'Administrativo' => 'Personal_Administrativo',
            'Civiles' => 'Civil',
            'Oficiales' => 'Oficial',
            'Cadetes' => 'Cadete',
        ];
        $normalized = $aliases[$normalized] ?? $normalized;

        return array_key_exists($normalized, config('anapo.patient_types'))
            ? $normalized
            : 'Civil';
    }

    private function tipoSangre(mixed $value): ?string
    {
        $value = strtoupper(trim((string) $value));

        return in_array($value, ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'], true) ? $value : null;
    }
}
