<?php

namespace App\Http\Controllers;

use App\Models\DonacionSangre;
use App\Models\Paciente;
use App\Models\SolicitudSangre;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SangreController extends Controller
{
    public function index(): View
    {
        return view('sangre.index', [
            'donaciones' => DonacionSangre::with('pacienteDonante')->latest()->paginate(10, ['*'], 'donaciones'),
            'solicitudes' => SolicitudSangre::with(['director', 'paciente', 'donanteAsignado'])->latest()->paginate(10, ['*'], 'solicitudes'),
            'pacientes' => Paciente::orderBy('nombre')->get(),
            'donantes' => Paciente::whereIn('grado_militar', ['Cadete', 'Aspirante', 'Oficial', 'Escala_Basica'])->orderBy('nombre')->get(),
            'directores' => Usuario::whereIn('rol', ['super_admin', 'admin', 'medico'])->orderBy('nombre')->get(),
        ]);
    }

    public function storeDonacion(Request $request): RedirectResponse
    {
        DonacionSangre::create($request->validate([
            'paciente_donante_id' => ['required', 'exists:pacientes,id'],
            'tipo_sangre' => ['required', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'cantidad_unidades' => ['required', 'integer', 'min:1'],
            'fecha_donacion' => ['required', 'date'],
            'estado_salud' => ['required', 'in:apto,no_apto'],
            'notas_salud' => ['nullable', 'string'],
        ]) + ['registrado_por_id' => auth()->id()]);

        return back()->with('status', 'Donacion registrada.');
    }

    public function updateDonacion(Request $request, DonacionSangre $donacion): RedirectResponse
    {
        $donacion->update($request->validate([
            'tipo_sangre' => ['required', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'cantidad_unidades' => ['required', 'integer', 'min:1'],
            'fecha_donacion' => ['required', 'date'],
            'estado_salud' => ['required', 'in:apto,no_apto'],
            'notas_salud' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Donacion actualizada.');
    }

    public function destroyDonacion(DonacionSangre $donacion): RedirectResponse
    {
        $donacion->delete();

        return back()->with('status', 'Donacion eliminada.');
    }

    public function storeSolicitud(Request $request): RedirectResponse
    {
        SolicitudSangre::create($request->validate([
            'paciente_id' => ['nullable', 'exists:pacientes,id'],
            'donante_asignado_id' => ['nullable', 'exists:pacientes,id'],
            'tipo_sangre' => ['required', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'cantidad_unidades' => ['required', 'integer', 'min:1'],
            'solicitante_nombre' => ['required', 'string', 'max:255'],
            'institucion' => ['nullable', 'string', 'max:255'],
            'director_id' => ['required', 'exists:usuarios,id'],
            'fecha_solicitud' => ['required', 'date'],
            'fecha_entrega' => ['nullable', 'date', 'after_or_equal:fecha_solicitud'],
            'estado' => ['required', 'in:pendiente,entregada,rechazada'],
            'indicaciones' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Solicitud registrada.');
    }

    public function updateSolicitud(Request $request, SolicitudSangre $solicitud): RedirectResponse
    {
        $solicitud->update($request->validate([
            'paciente_id' => ['nullable', 'exists:pacientes,id'],
            'donante_asignado_id' => ['nullable', 'exists:pacientes,id'],
            'tipo_sangre' => ['required', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'cantidad_unidades' => ['required', 'integer', 'min:1'],
            'solicitante_nombre' => ['required', 'string', 'max:255'],
            'institucion' => ['nullable', 'string', 'max:255'],
            'director_id' => ['required', 'exists:usuarios,id'],
            'fecha_solicitud' => ['required', 'date'],
            'fecha_entrega' => ['nullable', 'date', 'after_or_equal:fecha_solicitud'],
            'estado' => ['required', 'in:pendiente,entregada,rechazada'],
            'indicaciones' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Solicitud actualizada.');
    }

    public function destroySolicitud(SolicitudSangre $solicitud): RedirectResponse
    {
        $solicitud->delete();

        return back()->with('status', 'Solicitud eliminada.');
    }

}
