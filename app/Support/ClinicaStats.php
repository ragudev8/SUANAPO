<?php

namespace App\Support;

use App\Models\Constancia;
use App\Models\Consulta;
use App\Models\DetalleReceta;
use App\Models\ExamenMedico;
use App\Models\Incapacidad;
use App\Models\LibroVisita;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Receta;
use App\Models\SolicitudSangre;
use App\Models\Usuario;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClinicaStats
{
    public static function period(int $month, int $year): array
    {
        $start = CarbonImmutable::create($year, $month, 1)->startOfDay();

        return [$start, $start->endOfMonth()->endOfDay()];
    }

    public static function consultasMes(int $month, int $year): int
    {
        [$start, $end] = self::period($month, $year);

        return Consulta::whereBetween('created_at', [$start, $end])->count();
    }

    public static function visitasMes(int $month, int $year): int
    {
        [$start, $end] = self::period($month, $year);

        return LibroVisita::whereBetween('fecha_visita', [$start->toDateString(), $end->toDateString()])->count();
    }

    public static function visitasPorEstado(int $month, int $year)
    {
        [$start, $end] = self::period($month, $year);

        return LibroVisita::selectRaw('estado, count(*) as total')
            ->whereBetween('fecha_visita', [$start->toDateString(), $end->toDateString()])
            ->groupBy('estado')
            ->orderBy('estado')
            ->get();
    }

    public static function recetasEmitidasMes(int $month, int $year): int
    {
        [$start, $end] = self::period($month, $year);

        return Receta::whereBetween('fecha_emision', [$start->toDateString(), $end->toDateString()])->count();
    }

    public static function recetasConDispensacionMes(int $month, int $year): int
    {
        [$start, $end] = self::period($month, $year);

        return DetalleReceta::where('dispensado', true)
            ->whereBetween('fecha_dispensado', [$start, $end])
            ->distinct('receta_id')
            ->count('receta_id');
    }

    public static function unidadesDispensadasMes(int $month, int $year): int
    {
        [$start, $end] = self::period($month, $year);

        return (int) DetalleReceta::where('dispensado', true)
            ->whereBetween('fecha_dispensado', [$start, $end])
            ->sum('cantidad_medicamento');
    }

    public static function documentosMes(int $month, int $year): array
    {
        [$start, $end] = self::period($month, $year);

        $incapacidades = Incapacidad::whereBetween('fecha_inicio', [$start->toDateString(), $end->toDateString()])->count();
        $constancias = Constancia::whereBetween('created_at', [$start, $end])->count();
        $examenes = ExamenMedico::whereBetween('created_at', [$start, $end])->count();

        return [
            'incapacidades' => $incapacidades,
            'constancias' => $constancias,
            'examenes' => $examenes,
            'total' => $incapacidades + $constancias + $examenes,
        ];
    }

    public static function stockBajoCount(): int
    {
        return Medicamento::where('activo', true)
            ->whereColumn('cantidad_stock', '<=', 'cantidad_minima')
            ->count();
    }

    public static function medicamentosPorVencerCount(int $days = 60): int
    {
        return Medicamento::where('activo', true)
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<=', now()->addDays($days)->toDateString())
            ->count();
    }

    public static function stockBajoCalculado(int $month, int $year, int $limit = 15): Collection
    {
        [$start, $end] = self::period($month, $year);

        $dispensadoMes = DetalleReceta::query()
            ->select('medicamento_id', DB::raw('SUM(cantidad_medicamento) as dispensado_mes'))
            ->where('dispensado', true)
            ->whereBetween('fecha_dispensado', [$start, $end])
            ->groupBy('medicamento_id');

        $dispensadoTotal = DetalleReceta::query()
            ->select('medicamento_id', DB::raw('SUM(cantidad_medicamento) as dispensado_total'))
            ->where('dispensado', true)
            ->groupBy('medicamento_id');

        return Medicamento::query()
            ->leftJoinSub($dispensadoMes, 'dispensado_mes', fn ($join) => $join->on('medicamentos.id', '=', 'dispensado_mes.medicamento_id'))
            ->leftJoinSub($dispensadoTotal, 'dispensado_total', fn ($join) => $join->on('medicamentos.id', '=', 'dispensado_total.medicamento_id'))
            ->select('medicamentos.*')
            ->selectRaw('COALESCE(dispensado_mes.dispensado_mes, 0) as dispensado_mes')
            ->selectRaw('COALESCE(dispensado_total.dispensado_total, 0) as dispensado_total')
            ->where('medicamentos.activo', true)
            ->whereColumn('medicamentos.cantidad_stock', '<=', 'medicamentos.cantidad_minima')
            ->orderBy('medicamentos.cantidad_stock')
            ->limit($limit)
            ->get();
    }

    public static function resumenMensual(int $month, int $year): array
    {
        $documentos = self::documentosMes($month, $year);

        return [
            'Pacientes' => Paciente::count(),
            'Visitas del mes' => self::visitasMes($month, $year),
            'Consultas del mes' => self::consultasMes($month, $year),
            'Recetas emitidas' => self::recetasEmitidasMes($month, $year),
            'Recetas dispensadas' => self::recetasConDispensacionMes($month, $year),
            'Unidades dispensadas' => self::unidadesDispensadasMes($month, $year),
            'Documentos emitidos' => $documentos['total'],
            'Medicamentos activos' => Medicamento::where('activo', true)->count(),
            'Usuarios activos' => Usuario::where('activo', true)->count(),
        ];
    }

    public static function solicitudesSangrePendientes(): int
    {
        return SolicitudSangre::where('estado', 'pendiente')->count();
    }
}
