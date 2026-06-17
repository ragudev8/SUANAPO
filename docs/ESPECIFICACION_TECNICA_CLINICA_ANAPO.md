# Especificacion tecnica - Clinica Policial ANAPO

## Vision

Digitalizar el flujo de atenciones medicas de la Clinica ANAPO, manteniendo confidencialidad, auditoria, reportes mensuales y capacidad de trabajo offline.

## MVP

Incluye:

- Autenticacion, roles y auditoria.
- Gestion de pacientes.
- Registro de atenciones en siete.
- Expediente medico digital.
- Recetas con QR.
- Incapacidades y constancias en PDF.
- Donacion de sangre.
- Examenes medicos periodicos.
- Reportes basicos mensuales.

No incluye en fase inicial:

- App movil.
- Acceso remoto fuera de la clinica.
- Integraciones externas.
- Notificaciones automaticas.
- Analitica avanzada.

## Roles

- `super_admin`: jefe de clinica, control total.
- `admin`: licenciados de enfermeria, administracion operativa.
- `medico`: consultas, diagnosticos, recetas e incapacidades propias.
- `enfermero`: preclinica, libro de visitas, farmacia y procedimientos.
- `paciente`: consulta de expediente propio.
- `auditor`: revision de logs y reportes en fase posterior.

## Flujo de atencion

1. Libro de visitas.
2. Cita o asignacion.
3. Preclinica.
4. Consulta medica.
5. Diagnostico y receta.
6. Farmacia o procedimiento.
7. Finalizacion.

## Stack

- Laravel 11.
- PHP 8.2.
- MariaDB 10.6 o SQLite local para desarrollo.
- Blade + Bootstrap 5.3.
- DomPDF.
- chillerlan/php-qrcode.
- maatwebsite/excel.
- Spatie Permission.

## Seguridad

- Sesion con timeout de 30 minutos.
- Auditoria de accesos y modificaciones.
- Soft delete en registros sensibles cuando aplique.
- HTTPS obligatorio en produccion.
- Acceso local para MVP.
