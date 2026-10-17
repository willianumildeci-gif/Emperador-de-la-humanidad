# Sistema de Ventas Web — FastAPI

Réplica web del proyecto de NetBeans 8. Conserva Bootstrap 4 y el flujo visual original; incluye inicio de sesión, mantenimiento de empleados/clientes/productos y registro de ventas con actualización de existencias.

## Requisitos

- Python 3.10 o posterior
- MySQL o MariaDB activo
- Una base de datos importada desde `base\bd_ventas.sql`

## Preparar la base de datos

El volcado `base\bd_ventas.sql` crea las tablas y carga los datos de ejemplo, pero no crea la base de datos. Créala primero, por ejemplo desde phpMyAdmin o el cliente MySQL:

```sql
CREATE DATABASE bd_ventas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Selecciona `bd_ventas` e importa `base\bd_ventas.sql`. La aplicación usa las tablas ya existentes y no ejecuta migraciones.

## Instalar y ejecutar (Windows)

Desde la carpeta `SistemasVentas`:

```powershell
py -m venv .venv
.\.venv\Scripts\Activate.ps1
py -m pip install -r requirements.txt
Copy-Item .env.example .env
```

Edita `.env` con los datos de tu servidor. La configuración inicial coincide con la conexión local que declara el código Java (`root` sin contraseña) y con el nombre de base de datos del SQL (`bd_ventas`); cambia usuario y contraseña si tu instalación es distinta. Define además `SESSION_SECRET` con una clave privada aleatoria.

Inicia el servidor:

```powershell
py -m uvicorn app.main:app --reload
```

Abre <http://127.0.0.1:8000>.

## Acceso de muestra

El proyecto Java valida el usuario contra `empleado.User` y `empleado.Dni` (no hay una columna de contraseña en el esquema). Por eso el formulario de acceso conserva ese comportamiento y pide el DNI como contraseña. Con los datos incluidos en el volcado, un ejemplo es `emp01` / `123`.

## Notas

- La configuración MySQL se toma de `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_USER`, `MYSQL_PASSWORD` y `MYSQL_DATABASE` en `.env`.
- La sesión de la aplicación usa `SESSION_SECRET`. Para servirla por HTTPS, configura también `HTTPS_ONLY=true`.
- Los errores de conexión/configuración de MySQL se muestran en el registro del servidor; verifica el servicio y las variables de entorno si la aplicación no puede acceder a la base de datos.
