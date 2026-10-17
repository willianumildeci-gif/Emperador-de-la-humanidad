# Sistema de Ventas en Django

Esta aplicación reproduce la pantalla de acceso, el menú principal y la gestión
de empleados del proyecto Java. Clientes, productos y registro de ventas
conservan las páginas vacías del original.

La configuración apunta al volcado MySQL incluido en `..\base\bd_ventas.sql`.
La aplicación Django no crea ni modifica las tablas existentes: el modelo
`Empleado` es de solo mapeo (`managed = False`).

## Requisitos e instalación

- Python 3.10 o superior
- MySQL o MariaDB
- La base `bd_ventas` importada desde `..\base\bd_ventas.sql`

El archivo SQL es un volcado de tablas y no crea la base de datos. En
phpMyAdmin, crea una base llamada `bd_ventas`, selecciónala, abre **Importar** y
carga `base\bd_ventas.sql` antes de iniciar Django.

Desde esta carpeta:

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
```

Define credenciales según la instalación local. Los valores por defecto son
`bd_ventas`, usuario `root`, contraseña vacía, host `127.0.0.1` y puerto `3306`.

```powershell
$env:MYSQL_DATABASE = "bd_ventas"
$env:MYSQL_USER = "root"
$env:MYSQL_PASSWORD = "tu_contraseña"
$env:MYSQL_HOST = "127.0.0.1"
$env:MYSQL_PORT = "3306"
```

Inicia el servidor:

```powershell
python manage.py runserver
```

Abre <http://127.0.0.1:8000/>. El acceso replica el esquema existente: el nombre
de usuario se compara con `empleado.User` y la contraseña con `empleado.Dni`.
Por ejemplo, una fila de muestra incluida permite ingresar con el usuario
`emp01` y el DNI `123`. La tabla SQL no tiene un campo de contraseña; para
producción se debe migrar primero a credenciales con hash antes de exponer el
sitio.

No es necesario ejecutar migraciones para las tablas del sistema; están
administradas por la base existente. Los estáticos Bootstrap e imagen de acceso
se sirven desde la carpeta `web` del proyecto Java.
