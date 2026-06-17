<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('soporte.dashboard', [
            'stats' => [
                'Tickets abiertos' => 0,
                'Tickets en proceso' => 0,
                'Equipos registrados' => 0,
                'Pendientes criticos' => 0,
            ],
        ]);
    }
}
