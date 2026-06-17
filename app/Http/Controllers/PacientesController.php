<?php

namespace App\Http\Controllers;

use App\Models\ExpedienteMedico;
use App\Models\Paciente;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class PacientesController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $pacientes = Paciente::query()
            ->when($q, fn ($query) => $query
                ->where('nombre', 'like', "%{$q}%")
                ->orWhere('dni', 'like', "%{$q}%")
                ->orWhere('celular', 'like', "%{$q}%")
                ->orWhere('telefono', 'like', "%{$q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'q'));
    }

    public function create(): View
    {
        return view('pacientes.create', [
            'paciente' => new Paciente(),
            'usuarios' => $this->usuariosDisponibles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePaciente($request);
        $expediente = $this->validateExpediente($request);

        $paciente = Paciente::create($data);
        ExpedienteMedico::create(['paciente_id' => $paciente->id] + $expediente);

        return redirect()->route('pacientes.show', $paciente)->with('status', 'Paciente registrado.');
    }

    public function show(Paciente $paciente): View
    {
        $paciente->load([
            'usuario',
            'expediente',
            'libroVisitas.cita.especialidad',
            'citas.preclinica',
            'consultas.medico',
            'recetas.detalles.medicamento',
            'incapacidades.medico',
            'constancias.medico',
            'examenesMedicos.medicoAprobador',
            'donacionesSangre',
            'solicitudesSangre.donanteAsignado',
            'solicitudesComoDonante.paciente',
        ]);

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente): View
    {
        $paciente->load('expediente');

        return view('pacientes.create', [
            'paciente' => $paciente,
            'usuarios' => $this->usuariosDisponibles($paciente),
        ]);
    }

    public function update(Request $request, Paciente $paciente): RedirectResponse
    {
        $paciente->update($this->validatePaciente($request, $paciente->id));
        $paciente->expediente()->updateOrCreate(
            ['paciente_id' => $paciente->id],
            $this->validateExpediente($request),
        );

        return redirect()->route('pacientes.show', $paciente)->with('status', 'Paciente actualizado.');
    }

    public function destroy(Paciente $paciente): RedirectResponse
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('status', 'Paciente eliminado.');
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'nombre',
                'dni',
                'fecha_nacimiento',
                'edad',
                'sexo',
                'estado_civil',
                'vinculo_institucional',
                'grado_militar',
                'ocupacion',
                'unidad_dependencia',
                'numero_placa',
                'tipo_sangre',
                'alergias',
                'observaciones',
                'telefono',
                'celular',
                'correo',
                'direccion',
                'contacto_emergencia_nombre',
                'contacto_emergencia_telefono',
                'responsable_nombre',
                'responsable_parentesco',
                'antecedentes_familiares',
                'antecedentes_personales',
                'antecedentes_quirurgicos',
            ]);

            Paciente::with('expediente')->orderBy('nombre')->chunk(200, function ($pacientes) use ($out) {
                foreach ($pacientes as $paciente) {
                    fputcsv($out, [
                        $paciente->nombre,
                        $paciente->dni,
                        optional($paciente->fecha_nacimiento)->format('Y-m-d'),
                        $paciente->edad,
                        $paciente->sexo,
                        $paciente->estado_civil,
                        $paciente->vinculo_institucional_label,
                        $paciente->grado_militar,
                        $paciente->ocupacion,
                        $paciente->unidad_dependencia,
                        $paciente->numero_placa,
                        $paciente->tipo_sangre,
                        $paciente->alergias,
                        $paciente->observaciones,
                        $paciente->telefono,
                        $paciente->celular,
                        $paciente->correo,
                        $paciente->direccion,
                        $paciente->contacto_emergencia_nombre,
                        $paciente->contacto_emergencia_telefono,
                        $paciente->responsable_nombre,
                        $paciente->responsable_parentesco,
                        $paciente->expediente?->antecedentes_familiares,
                        $paciente->expediente?->antecedentes_personales,
                        $paciente->expediente?->antecedentes_quirurgicos,
                    ]);
                }
            });

            fclose($out);
        }, 'pacientes-anapo.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validatePaciente(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'usuario_id' => ['nullable', 'exists:usuarios,id', Rule::unique('pacientes', 'usuario_id')->ignore($id)],
            'dni' => ['required', 'string', 'max:20', 'unique:pacientes,dni,'.($id ?? 'NULL').',id'],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:M,F,Otro'],
            'estado_civil' => ['nullable', 'string', 'max:50'],
            'grado_militar' => ['required', Rule::in(array_keys(config('anapo.patient_types')))],
            'ocupacion' => ['nullable', 'string', 'max:255'],
            'unidad_dependencia' => ['nullable', 'string', 'max:255'],
            'numero_placa' => ['nullable', 'string', 'max:50'],
            'tipo_sangre' => ['nullable', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'alergias' => ['nullable', 'string'],
            'observaciones' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'celular' => ['nullable', 'string', 'max:20'],
            'correo' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'contacto_emergencia_nombre' => ['nullable', 'string', 'max:255'],
            'contacto_emergencia_telefono' => ['nullable', 'string', 'max:20'],
            'responsable_nombre' => ['nullable', 'string', 'max:255'],
            'responsable_parentesco' => ['nullable', 'string', 'max:100'],
        ]);
    }

    private function usuariosDisponibles(?Paciente $paciente = null)
    {
        return Usuario::query()
            ->where(function ($query) use ($paciente) {
                $query->whereDoesntHave('paciente');

                if ($paciente?->usuario_id) {
                    $query->orWhere('id', $paciente->usuario_id);
                }
            })
            ->orderBy('nombre')
            ->limit(300)
            ->get();
    }

    private function validateExpediente(Request $request): array
    {
        return $request->validate([
            'antecedentes_familiares' => ['nullable', 'string'],
            'antecedentes_personales' => ['nullable', 'string'],
            'antecedentes_quirurgicos' => ['nullable', 'string'],
        ]);
    }
}
