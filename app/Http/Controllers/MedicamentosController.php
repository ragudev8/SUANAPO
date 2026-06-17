<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MedicamentosController extends Controller
{
    public function index(): View
    {
        return view('medicamentos.index', ['medicamentos' => Medicamento::orderBy('nombre')->paginate(20)]);
    }

    public function show(Medicamento $medicamento): View
    {
        return view('medicamentos.show', compact('medicamento'));
    }

    public function edit(Medicamento $medicamento): View
    {
        return view('medicamentos.form', compact('medicamento'));
    }

    public function store(Request $request): RedirectResponse
    {
        Medicamento::create($this->validated($request));

        return back()->with('status', 'Medicamento registrado.');
    }

    public function update(Request $request, Medicamento $medicamento): RedirectResponse
    {
        $medicamento->update($this->validated($request, $medicamento->id));

        return redirect()->route('medicamentos.show', $medicamento)->with('status', 'Medicamento actualizado.');
    }

    public function destroy(Medicamento $medicamento): RedirectResponse
    {
        $medicamento->delete();

        return redirect()->route('medicamentos.index')->with('status', 'Medicamento eliminado.');
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nombre', 'presentacion', 'dosis', 'cantidad_stock', 'cantidad_minima', 'fecha_vencimiento', 'lote', 'precio_costo']);

            Medicamento::orderBy('nombre')->chunk(200, function ($medicamentos) use ($out) {
                foreach ($medicamentos as $medicamento) {
                    fputcsv($out, [
                        $medicamento->nombre,
                        $medicamento->presentacion,
                        $medicamento->dosis,
                        $medicamento->cantidad_stock,
                        $medicamento->cantidad_minima,
                        optional($medicamento->fecha_vencimiento)->format('Y-m-d'),
                        $medicamento->lote,
                        $medicamento->precio_costo,
                    ]);
                }
            });

            fclose($out);
        }, 'medicamentos-anapo.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('medicamentos', 'nombre')->ignore($id)],
            'presentacion' => ['nullable', 'string', 'max:100'],
            'dosis' => ['nullable', 'string', 'max:50'],
            'cantidad_stock' => ['required', 'integer', 'min:0'],
            'cantidad_minima' => ['required', 'integer', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'lote' => ['nullable', 'string', 'max:50'],
        ], [
            'nombre.unique' => 'Ya existe un medicamento con ese nombre.',
        ]);
    }
}
