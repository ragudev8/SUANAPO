<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\DetalleReceta;
use App\Models\Especialidad;
use App\Models\LibroVisita;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Preclinica;
use App\Models\Receta;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AtencionesController extends Controller
{
    public function board(): View
    {
        $visitas = LibroVisita::with(['paciente', 'cita.especialidad', 'cita.preclinica', 'cita.consulta'])
            ->whereDate('fecha_visita', now()->toDateString())
            ->orderBy('numero_orden')
            ->get();

        return view('atenciones.board', compact('visitas'));
    }

    public function createLlegada(): View
    {
        return view('atenciones.llegada', [
            'pacientes' => Paciente::orderBy('nombre')->limit(200)->get(),
            'especialidades' => Especialidad::where('activa', true)->orderBy('nombre')->get(),
        ]);
    }

    public function storeLlegada(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'tipo_consulta' => ['required', 'in:sin_asignar,interna,externa'],
            'especialidad_id' => ['nullable', 'exists:especialidades,id'],
        ]);

        $fecha = now()->toDateString();
        $orden = (int) LibroVisita::whereDate('fecha_visita', $fecha)->max('numero_orden') + 1;

        $visita = LibroVisita::create([
            'paciente_id' => $data['paciente_id'],
            'fecha_visita' => $fecha,
            'numero_orden' => $orden,
            'hora_llegada' => now()->format('H:i:s'),
            'estado' => 'registrado',
            'registrado_por_id' => $request->user()?->id,
        ]);

        Cita::create([
            'libro_visitas_id' => $visita->id,
            'paciente_id' => $data['paciente_id'],
            'tipo_consulta' => $data['tipo_consulta'],
            'especialidad_id' => $data['especialidad_id'] ?? null,
            'fecha_hora' => now(),
            'estado' => 'registrada',
        ]);

        return redirect()->route('atenciones.board')->with('status', 'Llegada registrada.');
    }

    public function showVisita(LibroVisita $visita): View
    {
        $visita->load(['paciente', 'cita.especialidad', 'cita.preclinica.registradoPor', 'cita.consulta.medico', 'cita.consulta.diagnosticos']);

        return view('atenciones.visita', compact('visita'));
    }

    public function editVisita(LibroVisita $visita): View
    {
        $visita->load('cita');

        return view('atenciones.visita-form', [
            'visita' => $visita,
            'pacientes' => Paciente::orderBy('nombre')->limit(200)->get(),
            'especialidades' => Especialidad::where('activa', true)->orderBy('nombre')->get(),
        ]);
    }

    public function updateVisita(Request $request, LibroVisita $visita): RedirectResponse
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'tipo_consulta' => ['required', 'in:sin_asignar,interna,externa'],
            'especialidad_id' => ['nullable', 'exists:especialidades,id'],
            'estado' => ['required', 'in:registrado,preclinica,esperando_medico,en_consulta,en_farmacia,en_procedimiento,finalizado'],
        ]);

        $visita->update([
            'paciente_id' => $data['paciente_id'],
            'estado' => $data['estado'],
        ]);

        $visita->cita?->update([
            'paciente_id' => $data['paciente_id'],
            'tipo_consulta' => $data['tipo_consulta'],
            'especialidad_id' => $data['especialidad_id'] ?? null,
        ]);

        return redirect()->route('atenciones.visitas.show', $visita)->with('status', 'Visita actualizada.');
    }

    public function destroyVisita(LibroVisita $visita): RedirectResponse
    {
        $visita->cita?->delete();
        $visita->delete();

        return redirect()->route('atenciones.board')->with('status', 'Visita eliminada.');
    }

    public function preclinicaForm(LibroVisita $visita): View
    {
        $visita->load(['paciente', 'cita.preclinica']);
        $citaId = $visita->cita?->id;
        $preclinicaDelDia = Preclinica::with(['cita.especialidad', 'registradoPor'])
            ->where('paciente_id', $visita->paciente_id)
            ->when($citaId, fn ($query) => $query->where('cita_id', '!=', $citaId))
            ->whereHas('cita', fn ($query) => $query->whereDate('fecha_hora', now()->toDateString()))
            ->latest()
            ->first();

        return view('atenciones.preclinica', [
            'visita' => $visita,
            'preclinica' => $visita->cita?->preclinica ?? new Preclinica(),
            'preclinicaDelDia' => $preclinicaDelDia,
        ]);
    }

    public function preclinicaStore(Request $request, LibroVisita $visita): RedirectResponse
    {
        $cita = $visita->cita()->firstOrFail();
        $data = $request->validate([
            'usar_preclinica_id' => ['nullable', 'exists:preclinicas,id'],
            'presion_sistolica' => ['nullable', 'integer', 'min:50', 'max:250'],
            'presion_diastolica' => ['nullable', 'integer', 'min:30', 'max:180'],
            'pulso' => ['nullable', 'integer', 'min:30', 'max:220'],
            'temperatura' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'peso' => ['nullable', 'numeric', 'min:1', 'max:400'],
            'talla' => ['nullable', 'numeric', 'min:0.4', 'max:2.5'],
            'notas_iniciales' => ['nullable', 'string'],
        ]);

        if (! empty($data['usar_preclinica_id'])) {
            $preclinicaBase = Preclinica::where('id', $data['usar_preclinica_id'])
                ->where('paciente_id', $visita->paciente_id)
                ->whereHas('cita', fn ($query) => $query->whereDate('fecha_hora', now()->toDateString()))
                ->firstOrFail();

            $data = [
                'presion_sistolica' => $preclinicaBase->presion_sistolica,
                'presion_diastolica' => $preclinicaBase->presion_diastolica,
                'pulso' => $preclinicaBase->pulso,
                'temperatura' => $preclinicaBase->temperatura,
                'peso' => $preclinicaBase->peso,
                'talla' => $preclinicaBase->talla,
                'notas_iniciales' => trim(($preclinicaBase->notas_iniciales ?: '')."\nReutilizada desde preclinica #{$preclinicaBase->id} del mismo dia."),
            ];
        } else {
            unset($data['usar_preclinica_id']);
        }

        Preclinica::updateOrCreate(
            ['cita_id' => $cita->id],
            $data + [
                'paciente_id' => $visita->paciente_id,
                'registrado_por_id' => $request->user()?->id,
            ],
        );

        $visita->update(['estado' => 'esperando_medico']);
        $cita->update(['estado' => 'en_preclinica']);

        return redirect()->route('atenciones.board')->with('status', 'Preclinica registrada.');
    }

    public function consultaForm(LibroVisita $visita): View
    {
        $visita->load(['paciente', 'cita.preclinica', 'cita.consulta']);

        return view('atenciones.consulta', [
            'visita' => $visita,
            'consulta' => $visita->cita?->consulta ?? new Consulta(),
            'medicos' => Usuario::whereIn('rol', ['medico', 'admin', 'super_admin'])->orderBy('nombre')->get(),
        ]);
    }

    public function consultaStore(Request $request, LibroVisita $visita): RedirectResponse
    {
        $cita = $visita->cita()->with('preclinica')->firstOrFail();
        $data = $request->validate([
            'medico_id' => ['required', 'exists:usuarios,id'],
            'sintomas' => ['required', 'string'],
            'duracion_sintomas' => ['nullable', 'string', 'max:100'],
            'presion_sistolica' => ['nullable', 'integer', 'min:50', 'max:250'],
            'presion_diastolica' => ['nullable', 'integer', 'min:30', 'max:180'],
            'pulso' => ['nullable', 'integer', 'min:30', 'max:220'],
            'temperatura' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'peso' => ['nullable', 'numeric', 'min:1', 'max:400'],
            'talla' => ['nullable', 'numeric', 'min:0.4', 'max:2.5'],
            'notas_medicas' => ['required', 'string'],
            'tratamiento_prescrito' => ['nullable', 'string'],
            'firma_digital' => ['nullable', 'string'],
            'diagnostico' => ['nullable', 'string'],
        ]);

        $consulta = Consulta::updateOrCreate(
            ['cita_id' => $cita->id],
            collect($data)->except('diagnostico')->all() + [
                'preclinica_id' => $cita->preclinica?->id,
                'paciente_id' => $visita->paciente_id,
                'firma_digital' => $data['firma_digital'] ?: 'firma-pendiente',
            ],
        );

        if (! empty($data['diagnostico'])) {
            $consulta->diagnosticos()->updateOrCreate(
                ['descripcion' => $data['diagnostico']],
                ['paciente_id' => $visita->paciente_id, 'evolucion' => 'En seguimiento', 'resuelto' => false],
            );
        }

        $visita->update(['estado' => 'en_farmacia']);
        $cita->update(['medico_id' => $data['medico_id'], 'estado' => 'en_consulta']);

        return redirect()->route('atenciones.dispensacion', $visita)->with('status', 'Consulta registrada.');
    }

    public function dispensacionForm(LibroVisita $visita): View
    {
        $visita->load(['paciente', 'cita.consulta']);
        $consulta = $visita->cita?->consulta;

        return view('atenciones.dispensacion', [
            'visita' => $visita,
            'consulta' => $consulta,
            'receta' => $consulta ? Receta::with('detalles.medicamento')->where('consulta_id', $consulta->id)->first() : null,
        ]);
    }

    public function dispensacionStore(Request $request, LibroVisita $visita): RedirectResponse
    {
        $visita->load('cita.consulta');
        $consulta = $visita->cita?->consulta;
        abort_unless($consulta, 422, 'Debe registrar la consulta medica antes de dispensar.');

        $data = $request->validate([
            'detalle_id' => ['required', 'exists:detalles_receta,id'],
        ]);

        $receta = Receta::where('consulta_id', $consulta->id)->first();
        abort_unless($receta, 422, 'Debe existir una receta antes de dispensar.');

        $detalle = DetalleReceta::with('medicamento')
            ->where('receta_id', $receta->id)
            ->findOrFail($data['detalle_id']);

        if ($detalle?->dispensado) {
            return redirect()
                ->route('atenciones.visitas.show', $visita)
                ->with('status', 'Este medicamento ya fue dispensado. No se desconto inventario nuevamente.');
        }

        $medicamento = $detalle->medicamento;
        abort_unless($medicamento, 422, 'El medicamento indicado no existe.');
        abort_if($medicamento->cantidad_stock < $detalle->cantidad_medicamento, 422, 'No hay stock suficiente para dispensar esta receta.');

        $detalle->update([
            'dispensado' => true,
            'fecha_dispensado' => now(),
            'dispensado_por_id' => $request->user()?->id,
        ]);

        $medicamento->update([
            'cantidad_stock' => max(0, (int) $medicamento->cantidad_stock - (int) $detalle->cantidad_medicamento),
        ]);

        $receta->update([
            'estado' => $receta->detalles()->where('dispensado', false)->exists() ? 'activa' : 'surtida',
        ]);
        $visita->update(['estado' => 'en_farmacia']);

        return redirect()->route('atenciones.visitas.show', $visita)->with('status', 'Medicamento dispensado.');
    }

    public function cerrar(Request $request, LibroVisita $visita): RedirectResponse
    {
        $visita->update(['estado' => 'finalizado']);
        $visita->cita?->update([
            'estado' => 'completada',
            'completada' => true,
            'fecha_completado' => now(),
        ]);

        return redirect()->route('atenciones.board')->with('status', 'Atencion finalizada.');
    }
}
