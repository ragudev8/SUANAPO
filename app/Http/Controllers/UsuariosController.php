<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuariosController extends Controller
{
    private const ROLES = [
        'super_admin',
        'admin',
        'medico',
        'enfermero_media',
        'licenciado_enfermeria',
        'soporte_ti',
        'docente',
        'administrativo_academia',
        'paciente',
        'auditor',
    ];

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $rol = $request->query('rol');

        $usuarios = Usuario::with('especialidad')
            ->when($q, fn ($query) => $query
                ->where('nombre', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('dni', 'like', "%{$q}%"))
            ->when($rol, fn ($query) => $query->where('rol', $rol))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => self::ROLES,
            'q' => $q,
            'rol' => $rol,
        ]);
    }

    public function create(): View
    {
        return view('usuarios.form', [
            'usuario' => new Usuario(['activo' => true]),
            'roles' => self::ROLES,
            'especialidades' => Especialidad::where('activa', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['password_hash'] = Hash::make($data['password']);
        $data['activo'] = $request->boolean('activo');
        unset($data['password']);

        $usuario = Usuario::create($data);

        return redirect()->route('usuarios.show', $usuario)->with('status', 'Usuario creado.');
    }

    public function show(Usuario $usuario): View
    {
        $usuario->load('especialidad');

        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario): View
    {
        return view('usuarios.form', [
            'usuario' => $usuario,
            'roles' => self::ROLES,
            'especialidades' => Especialidad::where('activa', true)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $data = $this->validated($request, $usuario);
        $data['activo'] = $request->boolean('activo');

        if (! empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
        }

        unset($data['password']);
        $usuario->update($data);

        return redirect()->route('usuarios.show', $usuario)->with('status', 'Usuario actualizado.');
    }

    public function destroy(Request $request, Usuario $usuario): RedirectResponse
    {
        abort_unless($request->user()?->canModule('usuarios', 'delete'), 403);
        abort_if($request->user()?->id === $usuario->id, 422, 'No puedes eliminar tu propio usuario.');

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('status', 'Usuario eliminado.');
    }

    private function validated(Request $request, ?Usuario $usuario = null): array
    {
        $id = $usuario?->id;

        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($id)],
            'dni' => ['nullable', 'string', 'max:20', Rule::unique('usuarios', 'dni')->ignore($id)],
            'numero_empleado' => ['nullable', 'string', 'max:50'],
            'rol' => ['required', Rule::in(self::ROLES)],
            'cargo' => ['nullable', 'string', 'max:150'],
            'area_departamento' => ['nullable', 'string', 'max:150'],
            'unidad_asignada' => ['nullable', 'string', 'max:150'],
            'turno' => ['nullable', 'string', 'max:50'],
            'fecha_ingreso' => ['nullable', 'date'],
            'colegiatura' => ['nullable', 'string', 'max:100'],
            'telefono_institucional' => ['nullable', 'string', 'max:30'],
            'celular' => ['nullable', 'string', 'max:30'],
            'observaciones_admin' => ['nullable', 'string', 'max:2000'],
            'especialidad_id' => ['nullable', 'exists:especialidades,id'],
            'password' => [$usuario ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
    }
}
