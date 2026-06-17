<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use App\Models\Usuario;

class PermisosController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($this->canManagePermissions($request, 'view'), 403);

        return view('permisos.index', [
            'modules' => config('anapo.modules'),
            'actions' => config('anapo.actions'),
            'permissions' => config('anapo.permissions'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($this->originalSuperAdmin($request), 403);

        $roles = array_keys(config('anapo.permissions'));
        $modules = array_keys(config('anapo.modules'));
        $actions = config('anapo.actions');
        $submitted = $request->input('permissions', []);

        $permissions = [
            'super_admin' => [
                '*' => $actions,
            ],
        ];

        foreach ($roles as $role) {
            if ($role === 'super_admin') {
                continue;
            }

            foreach ($modules as $module) {
                $allowed = array_values(array_intersect(
                    (array) data_get($submitted, "{$role}.{$module}", []),
                    $actions,
                ));

                if ($allowed !== []) {
                    $permissions[$role][$module] = $allowed;
                }
            }
        }

        $config = config('anapo');
        $config['permissions'] = $permissions;

        file_put_contents(
            config_path('anapo.php'),
            "<?php\n\nreturn ".var_export($config, true).";\n",
        );

        Artisan::call('config:clear');

        return back()->with('status', 'Permisos actualizados.');
    }

    private function canManagePermissions(Request $request, string $action): bool
    {
        return (bool) (
            $this->originalSuperAdmin($request)
            || $request->user()?->canModule('usuarios', $action)
        );
    }

    private function originalSuperAdmin(Request $request): ?Usuario
    {
        $impersonatorId = $request->session()->get('impersonator_id');

        if ($impersonatorId) {
            $impersonator = Usuario::find($impersonatorId);

            return $impersonator?->rol === 'super_admin' ? $impersonator : null;
        }

        return $request->user()?->rol === 'super_admin' ? $request->user() : null;
    }
}
