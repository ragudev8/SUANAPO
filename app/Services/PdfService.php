<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function receta($receta)
    {
        return Pdf::loadView('pdfs.receta', compact('receta'))->stream("receta-{$receta->folio_unico}.pdf");
    }

    public function incapacidad($incapacidad)
    {
        return Pdf::loadView('pdfs.incapacidad', compact('incapacidad'))->stream("incapacidad-{$incapacidad->id}.pdf");
    }
}
