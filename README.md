# SUANAPO

Sistema Unificado Academia Nacional de Policia.

SUANAPO es una plataforma Laravel para centralizar modulos operativos de la Academia Nacional de Policia. El primer modulo funcional es Clinica ANAPO, y la arquitectura ya deja preparada la incorporacion de otras unidades como Soporte TI, administracion academica, docentes y auditoria.

## Modulos actuales

- Clinica ANAPO: pacientes, expedientes, llegadas, board de atenciones, preclinica, consultas, recetas, dispensacion, medicamentos, documentos medicos, sangre y reportes.
- Administracion: usuarios, roles, permisos, auditoria e impersonacion para super admin.
- Soporte TI: base inicial para dashboard, tickets, equipos y reportes.
- Retroalimentacion: modulo para que usuarios propongan mejoras o reporten necesidades.

## Funcionalidades destacadas

- Roles para super admin, administrador, doctor, enfermeria, soporte TI, docente, administrativo academia, auditor y paciente.
- Pacientes vinculables con usuarios del sistema.
- Clasificacion de pacientes como civil o parte de la Policia.
- Registro de llegada por tipo de consulta y servicio/especialidad.
- Soporte para varias especialidades el mismo dia.
- Reutilizacion de preclinica del dia cuando el paciente pasa a otra especialidad.
- Inventario de medicamentos con descuento al dispensar recetas.
- Reportes con datos calculados desde la base de datos.
- Interfaz responsive para escritorio y movil.

## Requisitos

- PHP 8.2 o superior.
- Composer.
- Node.js 20 o superior.
- MariaDB/MySQL.
- Git.

## Instalacion local

```bash
composer install
copy .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan serve
```

Credenciales iniciales:

- Correo: `jefe@anapo.local`
- Contrasena: `TemporalPassword123!`

## Configuracion MariaDB

Edita `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinica_anapo
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de datos:

```sql
CREATE DATABASE clinica_anapo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ejecuta migraciones:

```bash
php artisan migrate --force
```

Para cargar datos de ejemplo:

```bash
php artisan db:seed --class=DemoDataSeeder --force
```

## Despliegue

Para produccion:

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

El dominio debe apuntar a la carpeta:

```text
public/
```

## Git

Flujo recomendado:

```bash
git status
git add .
git commit -m "Descripcion del cambio"
git push
```

## Notas tecnicas

- El proyecto usa nombres fisicos ASCII para tablas y campos, por compatibilidad con Windows, Laravel y MariaDB.
- La interfaz muestra textos en espanol para los usuarios.
- `vendor`, `node_modules`, `.env`, logs y caches no deben subirse al repositorio.

## Proximas fases

- Completar modulos de Soporte TI: tickets, inventario de equipos y reportes.
- Crear modulos academicos para docentes y administracion academica.
- Mejorar reportes PDF/Excel por unidad.
- Fortalecer permisos por modulo y accion.
- Agregar pruebas automatizadas para los flujos principales.
