# Versión PHP del Sistema de Ventas

La versión PHP pura mantiene la pantalla de acceso y el menú del proyecto Java. También implementa las operaciones de listar, agregar, editar y eliminar empleados. Las pantallas de Producto, Clientes y Nueva Venta conservan el contenido que tienen en la versión original; los JSP originales solo muestran sus títulos.

## Requisitos

- PHP con las extensiones `pdo` y `pdo_mysql`.
- MySQL o MariaDB.
- Apache (por ejemplo, el incluido en XAMPP).

## Preparación

1. Inicie MySQL y cree/importa la base de datos usando `base\bd_ventas.sql`. El nombre usado por defecto es `bd_ventas`.
2. Si su MySQL usa credenciales distintas de `root` sin contraseña, defina las variables `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASSWORD`, o ajuste sus valores predeterminados en `config.php`.
3. Coloque el proyecto en la carpeta pública de Apache (por ejemplo, `htdocs\SistemasVentas`) e inicie Apache.
4. Abra `http://localhost/SistemasVentas/`.

El acceso conserva la validación Java original: el usuario se compara con `empleado.User` y la contraseña con `empleado.Dni`. Por ejemplo, las filas de ejemplo del SQL incluyen el usuario `emp01` con DNI `123`.
