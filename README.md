# Clinica Policial ANAPO

Sistema de gestion para digitalizar el flujo de atenciones medicas de la Clinica ANAPO.

## Estado actual

Este repositorio contiene una base Laravel 11 lista para instalar dependencias:

- Migracion unificada con tablas del MVP.
- Autenticacion local usando la tabla `usuarios`.
- Seed inicial de especialidades, inventario de sangre y usuario administrador.
- CRUD inicial de pacientes.
- Registro de llegada y board de atenciones del dia.
- Inventario inicial de medicamentos.
- Servicios base para PDF, QR, auditoria y cambios pendientes offline.

## Requisitos

- PHP 8.2 o superior.
- Composer.
- Node.js 20 o superior.
- MariaDB 10.6 o SQLite para desarrollo rapido.

En este equipo, al momento de crear el proyecto, `php`, `composer`, `node` y `git` no estaban disponibles en PATH.

## Instalacion local

```bash
composer install
copy .env.example .env
php artisan key:generate
type nul > database\database.sqlite
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Credenciales iniciales:

- Correo: `jefe@anapo.local`
- Contrasena: `TemporalPassword123!`

## MariaDB

Para usar MariaDB, edita `.env`:

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinica_anapo_local
DB_USERNAME=root
DB_PASSWORD=
```

Luego crea la base:

```sql
CREATE DATABASE clinica_anapo_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

y ejecuta:

```bash
php artisan migrate:fresh --seed
```

## Decisiones de normalizacion

Los nombres fisicos de tablas y campos usan ASCII (`preclinicas`, `diagnosticos`, `examenes_medicos`) para evitar problemas entre Laravel, MariaDB, Windows y despliegues. La interfaz conserva los nombres en espanol para usuarios.

## Proximas fases

- Completar preclinica, consulta, diagnosticos y recetas con QR.
- PDFs profesionales para receta, incapacidad, constancia y boleta de sangre.
- Donacion, solicitud e inventario transaccional de sangre.
- Reportes mensuales en PDF/Excel.
- ACL granular con Spatie Permission.
- Pruebas automatizadas del flujo completo.
