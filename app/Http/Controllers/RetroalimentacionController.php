<?php

namespace App\Http\Controllers;

use App\Models\Retroalimentacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RetroalimentacionController extends Controller
{
    private const TIPOS = ['mejora', 'error', 'nuevo_modulo', 'diseno', 'otro'];
    private const PRIORIDADES = ['baja', 'media', 'alta'];
    private const ESTADOS = ['pendiente', 'revisando', 'aceptada', 'cerrada'];

    public function index(Request $request): View
    {
        $user = $request->user();
        $q = trim((string) $request->query('q'));
        $estado = $request->query('estado');

        $retroalimentaciones = Retroalimentacion::with(['usuario', 'revisadoPor'])
            ->when(! $user->esAdmin(), fn ($query) => $query->where('usuario_id', $user->id))
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->when($q, fn ($query) => $query->where(function ($subquery) use ($q) {
                $subquery
                    ->where('asunto', 'like', "%{$q}%")
                    ->orWhere('mensaje', 'like', "%{$q}%")
                    ->orWhere('modulo', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('retroalimentacion.index', [
            'retroalimentaciones' => $retroalimentaciones,
            'estados' => self::ESTADOS,
            'tipos' => self::TIPOS,
            'prioridades' => self::PRIORIDADES,
            'modulos' => config('anapo.modules'),
            'q' => $q,
            'estado' => $estado,
        ]);
    }

    public function create(): View
    {
        return view('retroalimentacion.form', [
            'retroalimentacion' => new Retroalimentacion(['prioridad' => 'media', 'tipo' => 'mejora']),
            'tipos' => self::TIPOS,
            'prioridades' => self::PRIORIDADES,
            'modulos' => config('anapo.modules'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['usuario_id'] = $request->user()->id;
        $data['estado'] = 'pendiente';

        $retroalimentacion = Retroalimentacion::create($data);

        return redirect()
            ->route('retroalimentacion.show', $retroalimentacion)
            ->with('status', 'Retroalimentacion enviada. Gracias por ayudar a mejorar el sistema.');
    }

    public function show(Request $request, Retroalimentacion $retroalimentacion): View
    {
        $this->authorizeOwnerOrAdmin($request, $retroalimentacion);
        $retroalimentacion->load(['usuario', 'revisadoPor']);

        return view('retroalimentacion.show', [
            'retroalimentacion' => $retroalimentacion,
            'estados' => self::ESTADOS,
        ]);
    }

    public function update(Request $request, Retroalimentacion $retroalimentacion): RedirectResponse
    {
        abort_unless($request->user()?->esAdmin(), 403);

        $data = $request->validate([
            'estado' => ['required', Rule::in(self::ESTADOS)],
            'respuesta_admin' => ['nullable', 'string', 'max:3000'],
        ]);

        $retroalimentacion->update($data + [
            'revisado_por_id' => $request->user()->id,
            'revisado_en' => now(),
        ]);

        return redirect()
            ->route('retroalimentacion.show', $retroalimentacion)
            ->with('status', 'Retroalimentacion actualizada.');
    }

    public function destroy(Request $request, Retroalimentacion $retroalimentacion): RedirectResponse
    {
        abort_unless($request->user()?->esAdmin(), 403);
        $retroalimentacion->delete();

        return redirect()
            ->route('retroalimentacion.index')
            ->with('status', 'Retroalimentacion eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'modulo' => ['nullable', 'string', 'max:80'],
            'tipo' => ['required', Rule::in(self::TIPOS)],
            'prioridad' => ['required', Rule::in(self::PRIORIDADES)],
            'asunto' => ['required', 'string', 'max:180'],
            'mensaje' => ['required', 'string', 'max:3000'],
        ]);
    }

    private function authorizeOwnerOrAdmin(Request $request, Retroalimentacion $retroalimentacion): void
    {
        abort_unless(
            $request->user()?->esAdmin() || $retroalimentacion->usuario_id === $request->user()?->id,
            403,
        );
    }
}
