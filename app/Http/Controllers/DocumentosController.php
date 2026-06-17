<?php

namespace App\Http\Controllers;

use App\Models\Constancia;
use App\Models\Diagnostico;
use App\Models\ExamenMedico;
use App\Models\Incapacidad;
use App\Models\Paciente;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentosController extends Controller
{
    public function index(): View
    {
        return view('documentos.index', $this->data());
    }

    public function storeIncapacidad(Request $request): RedirectResponse
    {
        Incapacidad::create($this->validateIncapacidad($request));

        return back()->with('status', 'Incapacidad registrada.');
    }

    public function showIncapacidad(Incapacidad $incapacidad): View
    {
        $incapacidad->load(['paciente', 'medico']);

        return view('documentos.incapacidad-show', compact('incapacidad'));
    }

    public function updateIncapacidad(Request $request, Incapacidad $incapacidad): RedirectResponse
    {
        $incapacidad->update($this->validateIncapacidad($request));

        return back()->with('status', 'Incapacidad actualizada.');
    }

    public function destroyIncapacidad(Incapacidad $incapacidad): RedirectResponse
    {
        $incapacidad->delete();

        return back()->with('status', 'Incapacidad eliminada.');
    }

    public function storeConstancia(Request $request): RedirectResponse
    {
        Constancia::create($this->validateConstancia($request));

        return back()->with('status', 'Constancia registrada.');
    }

    public function showConstancia(Constancia $constancia): View
    {
        $constancia->load(['paciente', 'medico']);

        return view('documentos.constancia-show', compact('constancia'));
    }

    public function updateConstancia(Request $request, Constancia $constancia): RedirectResponse
    {
        $constancia->update($this->validateConstancia($request));

        return back()->with('status', 'Constancia actualizada.');
    }

    public function destroyConstancia(Constancia $constancia): RedirectResponse
    {
        $constancia->delete();

        return back()->with('status', 'Constancia eliminada.');
    }

    public function storeExamen(Request $request): RedirectResponse
    {
        ExamenMedico::create($this->validateExamen($request));

        return back()->with('status', 'Examen registrado.');
    }

    public function showExamen(ExamenMedico $examen): View
    {
        $examen->load(['paciente', 'medicoAprobador']);

        return view('documentos.examen-show', compact('examen'));
    }

    public function updateExamen(Request $request, ExamenMedico $examen): RedirectResponse
    {
        $examen->update($this->validateExamen($request));

        return back()->with('status', 'Examen actualizado.');
    }

    public function destroyExamen(ExamenMedico $examen): RedirectResponse
    {
        $examen->delete();

        return back()->with('status', 'Examen eliminado.');
    }

    private function data(): array
    {
        return [
            'pacientes' => Paciente::orderBy('nombre')->get(),
            'medicos' => Usuario::whereIn('rol', ['medico', 'admin', 'super_admin'])->orderBy('nombre')->get(),
            'diagnosticos' => Diagnostico::with('consulta.paciente')->latest()->limit(100)->get(),
            'incapacidades' => Incapacidad::with(['paciente', 'medico'])->latest()->paginate(8, ['*'], 'incapacidades'),
            'constancias' => Constancia::with(['paciente', 'medico'])->latest()->paginate(8, ['*'], 'constancias'),
            'examenes' => ExamenMedico::with(['paciente', 'medicoAprobador'])->latest()->paginate(8, ['*'], 'examenes'),
        ];
    }

    private function validateIncapacidad(Request $request): array
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'medico_id' => ['required', 'exists:usuarios,id'],
            'diagnostico_id' => ['nullable', 'exists:diagnosticos,id'],
            'fecha_inicio' => ['required', 'date'],
            'dias_reposo' => ['required', 'integer', 'min:1', 'max:90'],
            'lugar_reposo' => ['required', 'in:casa,cuadra,clinica'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'motivo' => ['required', 'string'],
            'firma_jefe_medico_digital' => ['nullable', 'string'],
            'sello_clinica' => ['nullable', 'string'],
            'pdf_ruta' => ['nullable', 'string', 'max:255'],
        ]);
        $data['firma_jefe_medico_digital'] = $data['firma_jefe_medico_digital'] ?: 'firma-pendiente';

        return $data;
    }

    private function validateConstancia(Request $request): array
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'medico_id' => ['required', 'exists:usuarios,id'],
            'tipo' => ['required', 'in:medica,dictamen'],
            'asunto' => ['nullable', 'string', 'max:255'],
            'contenido' => ['required', 'string'],
            'firma_medico_digital' => ['nullable', 'string'],
            'sello_clinica' => ['nullable', 'string'],
            'pdf_ruta' => ['nullable', 'string', 'max:255'],
        ]);
        $data['firma_medico_digital'] = $data['firma_medico_digital'] ?: 'firma-pendiente';

        return $data;
    }

    private function validateExamen(Request $request): array
    {
        return $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'tipo' => ['required', 'in:ingreso,permanencia'],
            'fecha_examen' => ['required', 'date'],
            'resultados_sangre' => ['nullable', 'string'],
            'cardiograma' => ['nullable', 'boolean'],
            'ultrasonido_abdominal' => ['nullable', 'boolean'],
            'rayos_x_torax' => ['nullable', 'boolean'],
            'rayos_x_lumbar' => ['nullable', 'boolean'],
            'aprobado' => ['nullable', 'boolean'],
            'notas_medicas' => ['nullable', 'string'],
            'medico_aprobador_id' => ['nullable', 'exists:usuarios,id'],
            'pdf_ruta' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
