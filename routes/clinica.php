<?php

use App\Http\Controllers\AtencionesController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\MedicamentosController;
use App\Http\Controllers\ModulosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\RecetasController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RetroalimentacionController;
use App\Http\Controllers\SangreController;
use Illuminate\Support\Facades\Route;

Route::get('/pacientes', [PacientesController::class, 'index'])->middleware('module:pacientes,view')->name('pacientes.index');
Route::get('/pacientes/exportar', [PacientesController::class, 'export'])->middleware('module:pacientes,view')->name('pacientes.export');
Route::get('/pacientes/create', [PacientesController::class, 'create'])->middleware('module:pacientes,create')->name('pacientes.create');
Route::post('/pacientes', [PacientesController::class, 'store'])->middleware('module:pacientes,create')->name('pacientes.store');
Route::get('/pacientes/{paciente}', [PacientesController::class, 'show'])->middleware('module:pacientes,view')->name('pacientes.show');
Route::get('/pacientes/{paciente}/edit', [PacientesController::class, 'edit'])->middleware('module:pacientes,edit')->name('pacientes.edit');
Route::put('/pacientes/{paciente}', [PacientesController::class, 'update'])->middleware('module:pacientes,edit')->name('pacientes.update');
Route::patch('/pacientes/{paciente}', [PacientesController::class, 'update'])->middleware('module:pacientes,edit');
Route::delete('/pacientes/{paciente}', [PacientesController::class, 'destroy'])->middleware('module:pacientes,delete')->name('pacientes.destroy');

Route::get('/atenciones/board', [AtencionesController::class, 'board'])->middleware('module:atenciones,view')->name('atenciones.board');
Route::get('/atenciones/llegada', [AtencionesController::class, 'createLlegada'])->middleware('module:atenciones,create')->name('atenciones.llegada.create');
Route::post('/atenciones/llegada', [AtencionesController::class, 'storeLlegada'])->middleware('module:atenciones,create')->name('atenciones.llegada.store');
Route::get('/atenciones/visitas/{visita}', [AtencionesController::class, 'showVisita'])->middleware('module:atenciones,view')->name('atenciones.visitas.show');
Route::get('/atenciones/visitas/{visita}/edit', [AtencionesController::class, 'editVisita'])->middleware('module:atenciones,edit')->name('atenciones.visitas.edit');
Route::put('/atenciones/visitas/{visita}', [AtencionesController::class, 'updateVisita'])->middleware('module:atenciones,edit')->name('atenciones.visitas.update');
Route::delete('/atenciones/visitas/{visita}', [AtencionesController::class, 'destroyVisita'])->middleware('module:atenciones,delete')->name('atenciones.visitas.destroy');
Route::get('/atenciones/visitas/{visita}/preclinica', [AtencionesController::class, 'preclinicaForm'])->middleware('module:atenciones,edit')->name('atenciones.preclinica');
Route::post('/atenciones/visitas/{visita}/preclinica', [AtencionesController::class, 'preclinicaStore'])->middleware('module:atenciones,edit')->name('atenciones.preclinica.store');
Route::get('/atenciones/visitas/{visita}/consulta', [AtencionesController::class, 'consultaForm'])->middleware('module:atenciones,edit')->name('atenciones.consulta');
Route::post('/atenciones/visitas/{visita}/consulta', [AtencionesController::class, 'consultaStore'])->middleware('module:atenciones,edit')->name('atenciones.consulta.store');
Route::get('/atenciones/visitas/{visita}/dispensacion', [AtencionesController::class, 'dispensacionForm'])->middleware('module:atenciones,edit')->name('atenciones.dispensacion');
Route::post('/atenciones/visitas/{visita}/dispensacion', [AtencionesController::class, 'dispensacionStore'])->middleware('module:atenciones,edit')->name('atenciones.dispensacion.store');
Route::post('/atenciones/visitas/{visita}/cerrar', [AtencionesController::class, 'cerrar'])->middleware('module:atenciones,edit')->name('atenciones.cerrar');

Route::get('/medicamentos', [MedicamentosController::class, 'index'])->middleware('module:medicamentos,view')->name('medicamentos.index');
Route::get('/medicamentos/exportar', [MedicamentosController::class, 'export'])->middleware('module:medicamentos,view')->name('medicamentos.export');
Route::post('/medicamentos', [MedicamentosController::class, 'store'])->middleware('module:medicamentos,create')->name('medicamentos.store');
Route::get('/medicamentos/{medicamento}', [MedicamentosController::class, 'show'])->middleware('module:medicamentos,view')->name('medicamentos.show');
Route::get('/medicamentos/{medicamento}/edit', [MedicamentosController::class, 'edit'])->middleware('module:medicamentos,edit')->name('medicamentos.edit');
Route::put('/medicamentos/{medicamento}', [MedicamentosController::class, 'update'])->middleware('module:medicamentos,edit')->name('medicamentos.update');
Route::delete('/medicamentos/{medicamento}', [MedicamentosController::class, 'destroy'])->middleware('module:medicamentos,delete')->name('medicamentos.destroy');

Route::get('/recetas', [RecetasController::class, 'index'])->middleware('module:recetas,view')->name('recetas.index');
Route::get('/recetas/create', [RecetasController::class, 'create'])->middleware('module:recetas,create')->name('recetas.create');
Route::post('/recetas', [RecetasController::class, 'store'])->middleware('module:recetas,create')->name('recetas.store');
Route::get('/recetas/{receta}', [RecetasController::class, 'show'])->middleware('module:recetas,view')->name('recetas.show');
Route::get('/recetas/{receta}/edit', [RecetasController::class, 'edit'])->middleware('module:recetas,edit')->name('recetas.edit');
Route::put('/recetas/{receta}', [RecetasController::class, 'update'])->middleware('module:recetas,edit')->name('recetas.update');
Route::delete('/recetas/{receta}', [RecetasController::class, 'destroy'])->middleware('module:recetas,delete')->name('recetas.destroy');

Route::get('/sangre', [SangreController::class, 'index'])->middleware('module:sangre,view')->name('sangre.index');
Route::post('/sangre/donaciones', [SangreController::class, 'storeDonacion'])->middleware('module:sangre,create')->name('sangre.donaciones.store');
Route::put('/sangre/donaciones/{donacion}', [SangreController::class, 'updateDonacion'])->middleware('module:sangre,edit')->name('sangre.donaciones.update');
Route::delete('/sangre/donaciones/{donacion}', [SangreController::class, 'destroyDonacion'])->middleware('module:sangre,delete')->name('sangre.donaciones.destroy');
Route::post('/sangre/solicitudes', [SangreController::class, 'storeSolicitud'])->middleware('module:sangre,create')->name('sangre.solicitudes.store');
Route::put('/sangre/solicitudes/{solicitud}', [SangreController::class, 'updateSolicitud'])->middleware('module:sangre,edit')->name('sangre.solicitudes.update');
Route::delete('/sangre/solicitudes/{solicitud}', [SangreController::class, 'destroySolicitud'])->middleware('module:sangre,delete')->name('sangre.solicitudes.destroy');

Route::get('/documentos', [DocumentosController::class, 'index'])->middleware('module:documentos,view')->name('documentos.index');
Route::post('/documentos/incapacidades', [DocumentosController::class, 'storeIncapacidad'])->middleware('module:documentos,create')->name('documentos.incapacidades.store');
Route::get('/documentos/incapacidades/{incapacidad}', [DocumentosController::class, 'showIncapacidad'])->middleware('module:documentos,view')->name('documentos.incapacidades.show');
Route::put('/documentos/incapacidades/{incapacidad}', [DocumentosController::class, 'updateIncapacidad'])->middleware('module:documentos,edit')->name('documentos.incapacidades.update');
Route::delete('/documentos/incapacidades/{incapacidad}', [DocumentosController::class, 'destroyIncapacidad'])->middleware('module:documentos,delete')->name('documentos.incapacidades.destroy');
Route::post('/documentos/constancias', [DocumentosController::class, 'storeConstancia'])->middleware('module:documentos,create')->name('documentos.constancias.store');
Route::get('/documentos/constancias/{constancia}', [DocumentosController::class, 'showConstancia'])->middleware('module:documentos,view')->name('documentos.constancias.show');
Route::put('/documentos/constancias/{constancia}', [DocumentosController::class, 'updateConstancia'])->middleware('module:documentos,edit')->name('documentos.constancias.update');
Route::delete('/documentos/constancias/{constancia}', [DocumentosController::class, 'destroyConstancia'])->middleware('module:documentos,delete')->name('documentos.constancias.destroy');
Route::post('/documentos/examenes', [DocumentosController::class, 'storeExamen'])->middleware('module:documentos,create')->name('documentos.examenes.store');
Route::get('/documentos/examenes/{examen}', [DocumentosController::class, 'showExamen'])->middleware('module:documentos,view')->name('documentos.examenes.show');
Route::put('/documentos/examenes/{examen}', [DocumentosController::class, 'updateExamen'])->middleware('module:documentos,edit')->name('documentos.examenes.update');
Route::delete('/documentos/examenes/{examen}', [DocumentosController::class, 'destroyExamen'])->middleware('module:documentos,delete')->name('documentos.examenes.destroy');

Route::post('/imports/pacientes', [ImportsController::class, 'pacientes'])->middleware('module:pacientes,create')->name('imports.pacientes');
Route::post('/imports/medicamentos', [ImportsController::class, 'medicamentos'])->middleware('module:medicamentos,create')->name('imports.medicamentos');

Route::get('/reportes', [ReportesController::class, 'index'])->middleware('module:reportes,view')->name('reportes.index');
Route::get('/reportes/descargar', [ReportesController::class, 'descargar'])->middleware('module:reportes,view')->name('reportes.descargar');

Route::get('/retroalimentacion', [RetroalimentacionController::class, 'index'])->middleware('module:retroalimentacion,view')->name('retroalimentacion.index');
Route::get('/retroalimentacion/create', [RetroalimentacionController::class, 'create'])->middleware('module:retroalimentacion,create')->name('retroalimentacion.create');
Route::post('/retroalimentacion', [RetroalimentacionController::class, 'store'])->middleware('module:retroalimentacion,create')->name('retroalimentacion.store');
Route::get('/retroalimentacion/{retroalimentacion}', [RetroalimentacionController::class, 'show'])->middleware('module:retroalimentacion,view')->name('retroalimentacion.show');
Route::put('/retroalimentacion/{retroalimentacion}', [RetroalimentacionController::class, 'update'])->middleware('module:retroalimentacion,edit')->name('retroalimentacion.update');
Route::delete('/retroalimentacion/{retroalimentacion}', [RetroalimentacionController::class, 'destroy'])->middleware('module:retroalimentacion,delete')->name('retroalimentacion.destroy');

Route::get('/modulos/{module}', [ModulosController::class, 'show'])
    ->whereIn('module', ['recetas', 'documentos', 'sangre'])
    ->name('modulos.show');
