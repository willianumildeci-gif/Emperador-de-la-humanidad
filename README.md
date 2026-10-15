# SistemaVentas — Next.js

Aplicación web para gestionar clientes, empleados, productos e historial de ventas. Se generó con `npx create-next-app@latest`, usa Next.js App Router, Tailwind CSS, API Routes y Prisma ORM. El proyecto Java/NetBeans original permanece en la carpeta superior.

## Requisitos

- Node.js 20.9 o posterior.
- MySQL 8/MariaDB o una instancia MySQL compatible de TiDB Cloud.
- La base existente `bd_ventas`, cargada desde `../base/bd_ventas.sql`.

## Desarrollo local

1. Instala dependencias desde esta carpeta:

   ```bash
   npm install
   ```

2. Copia `.env.example` a `.env.local` y configura las credenciales MySQL:

   ```env
   DATABASE_URL="mysql://usuario:contraseña@host:4000/bd_ventas?sslaccept=strict"
   SESSION_SECRET="una-clave-aleatoria-de-al-menos-32-caracteres"
   ```

   Para TiDB Cloud, usa el endpoint, puerto, usuario y contraseña que proporciona el clúster y conserva TLS (`sslaccept=strict`). Codifica caracteres especiales de usuario/contraseña como URL-encoding.

3. Asegúrate de que exista la base `bd_ventas` y luego importa `../base/bd_ventas.sql` en ella. Por ejemplo, con el cliente `mysql`:

   ```bash
   mysql --host=HOST --port=4000 --user=USER --password --ssl-mode=VERIFY_IDENTITY --execute="CREATE DATABASE IF NOT EXISTS bd_ventas"
   mysql --host=HOST --port=4000 --user=USER --password --ssl-mode=VERIFY_IDENTITY bd_ventas < ../base/bd_ventas.sql
   ```

   O importa el archivo desde el editor SQL de TiDB Cloud. El esquema de Prisma está mapeado a las tablas y columnas originales; no ejecutes `prisma db push` contra la base con datos.

4. Arranca Next.js:

   ```bash
   npm run dev
   ```

   Abre `http://localhost:3000`.

## Inicio de sesión heredado

La base original no incluye una columna de contraseña: el servlet Java validaba `User` contra `Dni`. La pantalla migrada conserva ese comportamiento por compatibilidad. Los datos de ejemplo del SQL incluyen el usuario `emp01` y DNI `123` (también hay otros usuarios/DNI en `empleado`). Cambia este mecanismo por contraseñas con hash antes de usar el sistema con cuentas reales.

La sesión de Next.js se firma con `SESSION_SECRET`, se guarda en una cookie `HttpOnly`, `SameSite=Lax` y usa `Secure` en producción.

## Funcionalidad

- Panel con recuentos, inventario y ventas recientes.
- Alta, consulta, edición y eliminación de clientes, empleados y productos.
- Registro de ventas con varios productos, cálculo de precios desde la base de datos y descuento de stock transaccional.
- Rutas API protegidas por sesión bajo `/api`.
- Prisma mapea directamente las cinco tablas existentes (`cliente`, `empleado`, `producto`, `ventas`, `detalle_ventas`).

## Despliegue en Vercel + TiDB Cloud

1. Sube el repositorio a GitHub.
2. En Vercel, importa el repositorio y define `nextjs` como **Root Directory**.
3. En las variables de entorno de Vercel, configura `DATABASE_URL` (con TLS y la base `bd_ventas`) y `SESSION_SECRET` (aleatorio, único y de al menos 32 caracteres) para cada entorno que uses.
4. Despliega. El hook `postinstall` genera Prisma Client y `npm run build` vuelve a generarlo antes de compilar Next.js.

Mantén privadas las credenciales de TiDB y nunca las agregues al repositorio.

## API

| Método | Ruta | Uso |
| --- | --- | --- |
| `POST` | `/api/auth/login` | Iniciar sesión (`user`, `password`/DNI) |
| `GET` | `/api/auth/session` | Consultar sesión actual |
| `POST` | `/api/auth/logout` | Cerrar sesión |
| `GET`, `POST` | `/api/clientes` | Listar y crear clientes |
| `PUT`, `DELETE` | `/api/clientes/:id` | Actualizar y eliminar clientes |
| `GET`, `POST` | `/api/empleados` | Listar y crear empleados |
| `PUT`, `DELETE` | `/api/empleados/:id` | Actualizar y eliminar empleados |
| `GET`, `POST` | `/api/productos` | Listar y crear productos |
| `PUT`, `DELETE` | `/api/productos/:id` | Actualizar y eliminar productos |
| `GET`, `POST` | `/api/ventas` | Consultar ventas y registrar una venta |
