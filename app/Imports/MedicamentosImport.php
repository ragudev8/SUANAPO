<?php

namespace App\Imports;

use App\Models\Medicamento;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MedicamentosImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $updated = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $nombre = trim((string) ($row['nombre'] ?? ''));

            if ($nombre === '') {
                continue;
            }

            $medicamento = Medicamento::updateOrCreate(
                ['nombre' => $nombre],
                [
                    'presentacion' => $row['presentacion'] ?? null,
                    'dosis' => $row['dosis'] ?? null,
                    'cantidad_stock' => max(0, (int) ($row['cantidad_stock'] ?? $row['stock'] ?? 0)),
                    'cantidad_minima' => max(0, (int) ($row['cantidad_minima'] ?? 10)),
                    'fecha_vencimiento' => $row['fecha_vencimiento'] ?? null,
                    'lote' => $row['lote'] ?? null,
                    'precio_costo' => $row['precio_costo'] ?? null,
                    'activo' => true,
                ],
            );

            $medicamento->wasRecentlyCreated ? $this->created++ : $this->updated++;
        }
    }
}
