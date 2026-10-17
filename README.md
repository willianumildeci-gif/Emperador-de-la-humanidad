# Sistema de Ventas en Laravel

Esta aplicación Laravel conserva la pantalla de acceso, el menú y el mantenimiento de empleados de la versión PHP pura. Las opciones Producto, Clientes y Nueva Venta muestran los mismos títulos que la versión original. Ambas versiones utilizan el esquema `bd_ventas`; Laravel no migra ni modifica automáticamente esa base de datos.

## Requisitos

- Laragon con PHP 8.2 o superior y las extensiones PDO MySQL.
- MySQL/MariaDB iniciado desde Laragon.
- Composer (incluido con Laragon).

## Preparación en Laragon

1. Importe `..\base\bd_ventas.sql` en MySQL/MariaDB si todavía no existe la base `bd_ventas`.
2. Revise las credenciales en `.env` (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). La configuración inicial usa `127.0.0.1`, `bd_ventas`, `root` y contraseña vacía.
3. En esta carpeta ejecute `composer install` si la carpeta `vendor` no está presente.
4. Inicie Laragon y ejecute `php artisan serve` desde esta carpeta.
5. Abra `http://127.0.0.1:8000`.

El inicio de sesión mantiene la regla original: el usuario se verifica contra `empleado.User` y la contraseña contra `empleado.Dni`. Con los registros de ejemplo, se puede usar `emp01` y `123`.

El proyecto Laravel está separado en esta subcarpeta para mantener disponible la aplicación PHP pura original. No ejecute `php artisan migrate` sobre `bd_ventas`: el SQL original define el esquema compatible que consume esta aplicación.
