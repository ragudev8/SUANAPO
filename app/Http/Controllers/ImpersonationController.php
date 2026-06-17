<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Request $request): RedirectResponse
    {
        $impersonator = $this->impersonator($request);
        abort_unless($impersonator?->rol === 'super_admin', 403);

        $data = $request->validate([
            'usuario_id' => ['required', 'exists:usuarios,id'],
        ]);

        $target = Usuario::where('activo', true)->findOrFail($data['usuario_id']);

        if (! $request->session()->has('impersonator_id')) {
            $request->session()->put('impersonator_id', Auth::id());
        }

        Auth::login($target);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Ahora esta viendo el sistema como '.$target->nombre.'.');
    }

    public function stop(Request $request): RedirectResponse
    {
        $impersonator = $this->impersonator($request);
        abort_unless($impersonator?->rol === 'super_admin', 403);

        $request->session()->forget('impersonator_id');
        Auth::login($impersonator);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Regreso a la cuenta super admin.');
    }

    private function impersonator(Request $request): ?Usuario
    {
        $impersonatorId = $request->session()->get('impersonator_id');

        if ($impersonatorId) {
            return Usuario::find($impersonatorId);
        }

        return $request->user();
    }
}
