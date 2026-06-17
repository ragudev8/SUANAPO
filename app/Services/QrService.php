<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;

class QrService
{
    public function generar(string $contenido): string
    {
        return (new QRCode())->render($contenido);
    }
}
