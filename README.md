# Sistema de Ventas

Modernización del sistema original hecho con Java/JSP en NetBeans 8.2 a un
monorepo con **Next.js 16**, **React 19**, **NestJS 11**, TypeScript y MySQL /
MariaDB. La aplicación conserva los módulos y la identidad visual azul del
original, y reutiliza las tablas de `base/bd_ventas.sql`.

## Módulos

- Inicio de sesión de empleados y panel con resumen de ventas.
- Administración de empleados, clientes y productos (crear, editar y
  desactivar).
- Registro de ventas con varios productos, verificación transaccional de stock,
  actualización del inventario e historial de ventas.
- Interfaz adaptable a pantallas pequeñas y mensajes de error visibles.

Los registros se desactivan en vez de borrarse para mantener las relaciones
históricas de ventas.

## Requisitos

- Node.js 20.9 o posterior y npm.
- MySQL o MariaDB compatible con el volcado SQL.

## Preparar la base de datos

Importa `base/bd_ventas.sql` en una base llamada `bd_ventas`. El archivo contiene
el esquema y los datos iniciales, pero no crea la base de datos por sí solo. Por
ejemplo, desde una consola de MySQL:

```sql
CREATE DATABASE bd_ventas CHARACTER SET utf8 COLLATE utf8_general_ci;
```

Después selecciona `bd_ventas` e importa el archivo SQL desde phpMyAdmin o con
una herramienta local de MySQL. El volcado incluye empleados de ejemplo: por
ejemplo, usuario `emp01` y DNI `123`.

## Configuración y ejecución

1. Copia `.env.example` a `.env` en la raíz y ajusta los valores `DB_*` para tu
   servidor MySQL. El valor predeterminado es `root` sin contraseña, como en la
   conexión del proyecto Java original.
2. Instala las dependencias y arranca ambos servicios desde la raíz:

   ```bash
   npm install
   npm run dev
   ```

3. Abre `http://localhost:3000`. La API de NestJS escucha en
   `http://localhost:4000/api`; su comprobación de salud está en
   `http://localhost:4000/api/health`.

Para compilar ambos proyectos, ejecuta `npm run build`. Para ejecutar la versión
compilada, usa `npm start`.

Si la web o la API se ejecutan en otros orígenes, configura
`NEXT_PUBLIC_API_URL` y `WEB_ORIGIN` en `.env` según corresponda.

## Nota de autenticación

El esquema heredado no dispone de una columna de contraseña: el inicio de sesión
conserva la validación original de usuario y DNI. Esto sirve para compatibilidad
con los datos existentes, pero **no equivale a autenticación segura para
producción**. Antes de exponer el sistema, incorpora contraseñas con hash y una
sesión autenticada en el servidor; no reutilices el DNI como contraseña.

## Estructura

```text
apps/
  api/       NestJS: API REST, validación, consultas y transacciones MySQL
  web/       Next.js App Router: panel web y módulos de gestión
base/
  bd_ventas.sql
```

La API conserva los nombres de tabla y columna del volcado original
(`empleado`, `cliente`, `producto`, `ventas`, `detalle_ventas`) y presenta una
interfaz JSON en español funcional desde la web.
