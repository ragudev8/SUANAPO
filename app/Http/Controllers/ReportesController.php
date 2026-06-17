<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Support\ClinicaStats;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportesController extends Controller
{
    public function index(Request $request): View
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        return view('reportes.index', [
            'month' => $month,
            'year' => $year,
            'totals' => ClinicaStats::resumenMensual($month, $year),
            'documentos' => ClinicaStats::documentosMes($month, $year),
            'porGrado' => Paciente::selectRaw('grado_militar, count(*) as total')->groupBy('grado_militar')->orderBy('grado_militar')->get(),
            'stockBajo' => ClinicaStats::stockBajoCalculado($month, $year),
            'visitasPorEstado' => ClinicaStats::visitasPorEstado($month, $year),
        ]);
    }

    public function descargar(Request $request): StreamedResponse
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);
        $filename = "reporte-anapo-{$year}-".str_pad((string) $month, 2, '0', STR_PAD_LEFT).'.csv';

        return response()->streamDownload(function () use ($month, $year) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Reporte ANAPO', "{$month}/{$year}"]);
            fputcsv($out, []);
            fputcsv($out, ['Indicador', 'Total']);
            foreach (ClinicaStats::resumenMensual($month, $year) as $label => $total) {
                fputcsv($out, [$label, $total]);
            }
            fputcsv($out, []);
            fputcsv($out, ['Pacientes por tipo']);
            fputcsv($out, ['Tipo de paciente', 'Total']);
            foreach (Paciente::selectRaw('grado_militar, count(*) as total')->groupBy('grado_militar')->orderBy('grado_militar')->get() as $item) {
                fputcsv($out, [config('anapo.patient_types.'.$item->grado_militar, str_replace('_', ' ', $item->grado_militar)), $item->total]);
            }
            fputcsv($out, []);
            fputcsv($out, ['Visitas por estado']);
            fputcsv($out, ['Estado', 'Total']);
            foreach (ClinicaStats::visitasPorEstado($month, $year) as $item) {
                fputcsv($out, [str_replace('_', ' ', $item->estado), $item->total]);
            }
            fputcsv($out, []);
            fputcsv($out, ['Documentos emitidos']);
            fputcsv($out, ['Tipo', 'Total']);
            foreach (ClinicaStats::documentosMes($month, $year) as $tipo => $total) {
                fputcsv($out, [str_replace('_', ' ', $tipo), $total]);
            }
            fputcsv($out, []);
            fputcsv($out, ['Stock bajo calculado']);
            fputcsv($out, ['Medicamento', 'Stock actual', 'Minimo', 'Entregado en recetas del mes', 'Entregado total']);
            foreach (ClinicaStats::stockBajoCalculado($month, $year, 200) as $medicamento) {
                fputcsv($out, [
                    $medicamento->nombre,
                    $medicamento->cantidad_stock,
                    $medicamento->cantidad_minima,
                    $medicamento->dispensado_mes,
                    $medicamento->dispensado_total,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
