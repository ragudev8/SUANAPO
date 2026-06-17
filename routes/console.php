<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('anapo:about', function () {
    $this->info('Sistema de Gestion de Clinica Policial ANAPO');
})->purpose('Muestra informacion del sistema ANAPO');
