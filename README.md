# Sistema de Ventas Web (Flask)

Port del proyecto Java/JSP de NetBeans 8. Conserva la pantalla de acceso, el
menú principal con contenido en iframe y el mantenimiento de empleados. Las
pantallas de Clientes, Producto y Nueva Venta siguen siendo marcadores, tal
como en la versión original.

## Requisitos

- Python 3.10 o posterior
- MySQL o MariaDB con la base `bd_ventas`

## Instalación en Windows

Desde la carpeta del proyecto, crea un entorno virtual e instala las
dependencias:

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
```

El archivo `base\bd_ventas.sql` contiene la estructura y los datos iniciales.
Si todavía no has importado el volcado, crea la base e impórtalo desde
PowerShell:

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS bd_ventas CHARACTER SET utf8mb4"
cmd /c "mysql -u root bd_ventas < base\bd_ventas.sql"
```

La aplicación usa por defecto `localhost:3306`, usuario `root`, contraseña
vacía y base `bd_ventas`, coherente con el volcado de `base`. Configura estas
variables de entorno si tus credenciales son distintas:

```powershell
$env:DB_HOST = "localhost"
$env:DB_PORT = "3306"
$env:DB_USER = "root"
$env:DB_PASSWORD = "tu contraseña"
$env:DB_NAME = "bd_ventas"
$env:SECRET_KEY = "reemplaza-por-una-clave-aleatoria"
```

Ejecuta la aplicación localmente con:

```powershell
python app.py
```

Abre <http://127.0.0.1:5000>. Para iniciar sesión se usa el campo `User` como
usuario y `Dni` como contraseña, igual que en el servlet original.

## Pruebas

```powershell
python -m unittest discover -s test -p "test_*.py"
```
