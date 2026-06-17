<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\ExamenMedico;
use App\Models\Incapacidad;
use App\Models\LibroVisita;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Receta;
use App\Support\ClinicaStats;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now()->toDateString();
        $month = now()->month;
        $year = now()->year;
        $documentosMes = ClinicaStats::documentosMes($month, $year);

        $visitasHoyQuery = LibroVisita::whereDate('fecha_visita', $today);
        $visitasPorEstado = (clone $visitasHoyQuery)
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $ultimasVisitas = LibroVisita::with(['paciente', 'cita.especialidad'])
            ->whereDate('fecha_visita', $today)
            ->latest('id')
            ->limit(6)
            ->get();

        $stockBajo = ClinicaStats::stockBajoCalculado($month, $year, 5);

        $porVencer = Medicamento::where('activo', true)
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(60)->toDateString())
            ->orderBy('fecha_vencimiento')
            ->limit(5)
            ->get();

        $repososActivos = Incapacidad::with(['paciente', 'medico'])
            ->whereDate('fecha_inicio', '<=', $today)
            ->whereDate('fecha_fin', '>=', $today)
            ->orderBy('fecha_fin')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'pacientes' => Paciente::count(),
            'visitasHoy' => (clone $visitasHoyQuery)->count(),
            'citasPendientes' => Cita::where(function ($query) {
                $query->where('completada', false)->orWhereNull('completada');
            })->count(),
            'consultasMes' => ClinicaStats::consultasMes($month, $year),
            'enEspera' => ($visitasPorEstado['registrado'] ?? 0) + ($visitasPorEstado['esperando_medico'] ?? 0),
            'enPreclinica' => $visitasPorEstado['preclinica'] ?? 0,
            'enConsulta' => $visitasPorEstado['en_consulta'] ?? 0,
            'enFarmacia' => $visitasPorEstado['en_farmacia'] ?? 0,
            'finalizadasHoy' => $visitasPorEstado['finalizado'] ?? 0,
            'visitasPorEstado' => $visitasPorEstado,
            'ultimasVisitas' => $ultimasVisitas,
            'recetasActivas' => Receta::where('estado', 'activa')->count(),
            'recetasDispensadasMes' => ClinicaStats::recetasConDispensacionMes($month, $year),
            'unidadesDispensadasMes' => ClinicaStats::unidadesDispensadasMes($month, $year),
            'stockBajo' => $stockBajo,
            'porVencer' => $porVencer,
            'medicamentosStockBajo' => ClinicaStats::stockBajoCount(),
            'medicamentosPorVencer' => ClinicaStats::medicamentosPorVencerCount(),
            'incapacidadesMes' => $documentosMes['incapacidades'],
            'constanciasMes' => $documentosMes['constancias'],
            'examenesMes' => $documentosMes['examenes'],
            'documentosMes' => $documentosMes['total'],
            'examenesPendientes' => ExamenMedico::where('aprobado', false)->count(),
            'sangrePendiente' => ClinicaStats::solicitudesSangrePendientes(),
            'repososActivos' => $repososActivos,
            'repososActivosTotal' => Incapacidad::whereDate('fecha_inicio', '<=', $today)
                ->whereDate('fecha_fin', '>=', $today)
                ->count(),
        ]);
    }
}
