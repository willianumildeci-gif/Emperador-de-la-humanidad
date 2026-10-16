# Sistema de Ventas (NestJS)

Migración del sistema web de NetBeans 8.2 a NestJS. Mantiene el menú y el estilo Bootstrap del proyecto original, sirve vistas con Handlebars y usa Prisma para conectarse a la base de datos MariaDB/MySQL `bd_ventas`.

## Requisitos

- Node.js 20 o posterior
- MariaDB/MySQL

## Instalación

1. Importe `base/bd_ventas.sql` en MariaDB/MySQL. Si aún no existe la base, créela primero y selecciónela para importar el archivo:

   ```sql
   CREATE DATABASE bd_ventas CHARACTER SET utf8 COLLATE utf8_general_ci;
   USE bd_ventas;
   ```

   El archivo contiene las tablas y los datos iniciales, pero asume que `bd_ventas` ya está seleccionada. Haga una copia de seguridad antes de importarlo en una base existente.
2. Configure las variables de entorno:

   ```powershell
   Copy-Item .env.example .env
   ```

   Ajuste `DATABASE_URL` con el usuario, contraseña, host y puerto de su servidor MariaDB. Configure también un valor aleatorio y privado para `SESSION_SECRET`.
3. Instale dependencias, genere el cliente Prisma y ejecute la aplicación:

   ```powershell
   npm install
   npx prisma generate
   npm run start:dev
   ```

4. Abra <http://localhost:3000>.

El inicio de sesión conserva el comportamiento de `Validar.java`: use el campo `User` del empleado como usuario y su DNI como contraseña. Por ejemplo, en los datos iniciales: usuario `emp01`, contraseña `123`. Esta contraseña en texto plano es una limitación heredada del SQL original; no se debe exponer esta base de prueba en producción sin implementar almacenamiento de contraseñas con hash.

## API REST

Todos los recursos, excepto autenticación, requieren una sesión iniciada. La sesión se almacena en una cookie `HttpOnly`.

| Recurso | Operaciones |
| --- | --- |
| `/auth/login`, `/auth/logout`, `/auth/me` | Inicio, cierre y consulta de sesión |
| `/productos` | GET, POST, GET `/:id`, PATCH `/:id`, DELETE `/:id` |
| `/clientes` | GET, POST, GET `/:id`, PATCH `/:id`, DELETE `/:id` |
| `/empleados` | GET, POST, GET `/:id`, PATCH `/:id`, DELETE `/:id` |
| `/ventas` | GET, POST, GET `/:id`, PATCH `/:id`, DELETE `/:id` |

Al crear una venta, envíe `clienteId`, `empleadoId` y `items: [{ "productoId": 1, "cantidad": 2 }]`. El servidor calcula el total y actualiza el stock dentro de una transacción; al eliminar una venta, devuelve las cantidades al inventario.
