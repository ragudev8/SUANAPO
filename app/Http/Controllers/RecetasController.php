<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\DetalleReceta;
use App\Models\Medicamento;
use App\Models\Receta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecetasController extends Controller
{
    public function index(): View
    {
        return view('recetas.index', [
            'recetas' => Receta::with(['paciente', 'medico', 'detalles.medicamento'])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Receta());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateReceta($request);
        $consulta = Consulta::with(['paciente', 'medico'])->findOrFail($data['consulta_id']);
        abort_unless($consulta->paciente_id && $consulta->medico_id, 422, 'La consulta seleccionada no tiene paciente o medico asignado.');
        $data['paciente_id'] = $consulta->paciente_id;
        $data['medico_id'] = $consulta->medico_id;
        $data['codigo_qr'] = $data['codigo_qr'] ?: 'QR-'.$data['folio_unico'];
        $data['firma_digital'] = $data['firma_digital'] ?: 'firma-pendiente';

        $receta = Receta::create($data);
        $this->saveDetalle($request, $receta);

        return redirect()->route('recetas.show', $receta)->with('status', 'Receta creada.');
    }

    public function show(Receta $receta): View
    {
        $receta->load(['consulta', 'paciente', 'medico', 'detalles.medicamento']);

        return view('recetas.show', compact('receta'));
    }

    public function edit(Receta $receta): View
    {
        $this->authorizeDispensedChanges($receta);

        return $this->form($receta->load('detalles'));
    }

    public function update(Request $request, Receta $receta): RedirectResponse
    {
        $this->authorizeDispensedChanges($receta);

        $data = $this->validateReceta($request, $receta->id);
        $consulta = Consulta::with(['paciente', 'medico'])->findOrFail($data['consulta_id']);
        abort_unless($consulta->paciente_id && $consulta->medico_id, 422, 'La consulta seleccionada no tiene paciente o medico asignado.');
        $data['paciente_id'] = $consulta->paciente_id;
        $data['medico_id'] = $consulta->medico_id;
        $data['codigo_qr'] = $data['codigo_qr'] ?: 'QR-'.$data['folio_unico'];
        $data['firma_digital'] = $data['firma_digital'] ?: 'firma-pendiente';

        $receta->update($data);
        $this->saveDetalle($request, $receta);

        return redirect()->route('recetas.show', $receta)->with('status', 'Receta actualizada.');
    }

    public function destroy(Receta $receta): RedirectResponse
    {
        $this->authorizeDispensedChanges($receta);

        $receta->detalles()->delete();
        $receta->delete();

        return redirect()->route('recetas.index')->with('status', 'Receta eliminada.');
    }

    private function form(Receta $receta): View
    {
        return view('recetas.form', [
            'receta' => $receta,
            'consultas' => Consulta::with(['paciente', 'medico'])
                ->whereNotNull('paciente_id')
                ->whereNotNull('medico_id')
                ->latest()
                ->limit(100)
                ->get(),
            'medicamentos' => Medicamento::where('activo', true)->orderBy('nombre')->get(),
            'detalle' => $receta->detalles->first() ?? new DetalleReceta(),
        ]);
    }

    private function validateReceta(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'consulta_id' => ['required', 'exists:consultas,id'],
            'folio_unico' => ['required', 'string', 'max:50', 'unique:recetas,folio_unico,'.($id ?? 'NULL').',id'],
            'codigo_qr' => ['nullable', 'string'],
            'fecha_emision' => ['required', 'date'],
            'fecha_vencimiento' => ['nullable', 'date', 'after_or_equal:fecha_emision'],
            'estado' => ['required', 'in:activa,surtida,vencida,cancelada'],
            'firma_digital' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ]);
    }

    private function saveDetalle(Request $request, Receta $receta): void
    {
        $data = $request->validate([
            'medicamento_id' => ['nullable', 'exists:medicamentos,id'],
            'dosis' => ['nullable', 'string', 'max:100'],
            'frecuencia' => ['nullable', 'string', 'max:100'],
            'cantidad_dias' => ['nullable', 'integer', 'min:1'],
            'cantidad_medicamento' => ['required_with:medicamento_id', 'nullable', 'integer', 'min:1'],
        ]);

        if (empty($data['medicamento_id'])) {
            return;
        }

        $detalle = $receta->detalles()->first();

        if ($detalle?->dispensado && ! auth()->user()?->esAdmin()) {
            abort(403, 'Esta dispensacion ya fue entregada y solo puede modificarla un administrador.');
        }

        if ($detalle && (int) $detalle->medicamento_id !== (int) $data['medicamento_id']) {
            $detalle->delete();
            $detalle = null;
        }

        DetalleReceta::updateOrCreate(
            ['receta_id' => $receta->id, 'medicamento_id' => $data['medicamento_id']],
            $data + [
                'receta_id' => $receta->id,
                'dispensado' => (bool) ($detalle?->dispensado ?? false),
                'fecha_dispensado' => $detalle?->fecha_dispensado,
                'dispensado_por_id' => $detalle?->dispensado_por_id,
            ],
        );
    }

    private function authorizeDispensedChanges(Receta $receta): void
    {
        $hasDispensed = $receta->detalles()->where('dispensado', true)->exists();

        if ($hasDispensed && ! auth()->user()?->esAdmin()) {
            abort(403, 'Esta receta ya tiene dispensaciones entregadas y solo puede modificarla un administrador.');
        }
    }
}
