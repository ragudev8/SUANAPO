-- SQL base recomendado: usar las migraciones Laravel como fuente principal.
-- Este archivo queda como referencia para creacion manual de base.

CREATE DATABASE IF NOT EXISTS clinica_anapo_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_anapo_local;

-- Para crear todas las tablas de forma mantenible:
-- 1. Configurar .env con DB_CONNECTION=mariadb
-- 2. Ejecutar: php artisan migrate --seed

-- Las migraciones normalizan nombres con ASCII:
-- preclinicas, diagnosticos, examenes_medicos, accion, notas_medicas.
