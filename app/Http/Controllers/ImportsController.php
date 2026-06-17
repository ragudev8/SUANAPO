<?php

namespace App\Http\Controllers;

use App\Imports\MedicamentosImport;
use App\Imports\PacientesImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportsController extends Controller
{
    public function pacientes(Request $request): RedirectResponse
    {
        $request->validate(['archivo' => ['required', 'file', 'mimes:xlsx,xls,csv']]);

        $import = new PacientesImport();
        Excel::import($import, $request->file('archivo'));

        return back()->with('status', "Pacientes importados. Nuevos: {$import->created}. Actualizados: {$import->updated}.");
    }

    public function medicamentos(Request $request): RedirectResponse
    {
        $request->validate(['archivo' => ['required', 'file', 'mimes:xlsx,xls,csv']]);

        $import = new MedicamentosImport();
        Excel::import($import, $request->file('archivo'));

        return back()->with('status', "Medicamentos importados. Nuevos: {$import->created}. Actualizados: {$import->updated}.");
    }
}
