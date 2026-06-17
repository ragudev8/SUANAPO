<?php

namespace App\Http\Controllers;

use App\Models\Constancia;
use App\Models\DonacionSangre;
use App\Models\ExamenMedico;
use App\Models\Incapacidad;
use App\Models\InventarioSangre;
use App\Models\LogAuditoria;
use App\Models\Receta;
use App\Models\SolicitudSangre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModulosController extends Controller
{
    public function show(string $module): View|RedirectResponse
    {
        abort_unless(auth()->user()?->canModule($module, 'view'), 403);

        return match ($module) {
            'sangre' => redirect()->route('sangre.index'),
            'recetas' => redirect()->route('recetas.index'),
            'documentos' => redirect()->route('documentos.index'),
            'auditoria' => redirect()->route('auditoria.index'),
            default => view('modulos.show', ['module' => $module, 'title' => config("anapo.modules.{$module}", ucfirst($module))]),
        };
    }
}
