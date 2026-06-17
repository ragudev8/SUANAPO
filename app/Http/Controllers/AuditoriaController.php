<?php

namespace App\Http\Controllers;

use App\Exports\AuditoriaExport;
use App\Models\LogAuditoria;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AuditoriaController extends Controller
{
    public function index(): View
    {
        return view('auditoria.index', [
            'logs' => LogAuditoria::with('usuario')->latest('created_at')->paginate(20),
            'usuarios' => Usuario::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        LogAuditoria::create($this->validateLog($request));

        return back()->with('status', 'Log registrado.');
    }

    public function show(LogAuditoria $log): View
    {
        $log->load('usuario');

        return view('auditoria.show', compact('log'));
    }

    public function update(Request $request, LogAuditoria $log): RedirectResponse
    {
        $log->update($this->validateLog($request));

        return back()->with('status', 'Log actualizado.');
    }

    public function destroy(LogAuditoria $log): RedirectResponse
    {
        $log->delete();

        return back()->with('status', 'Log eliminado.');
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new AuditoriaExport(), 'auditoria-anapo-'.now()->format('Y-m-d-His').'.xlsx');
    }

    private function validateLog(Request $request): array
    {
        $data = $request->validate([
            'usuario_id' => ['nullable', 'exists:usuarios,id'],
            'accion' => ['required', 'in:view,create,update,delete,login,logout,download,print,sign,modify'],
            'tabla_accedida' => ['nullable', 'string', 'max:100'],
            'registro_id' => ['nullable', 'integer', 'min:1'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'user_agent' => ['nullable', 'string', 'max:500'],
        ]);
        $data['cambios_json'] = ['manual' => true];

        return $data;
    }
}
