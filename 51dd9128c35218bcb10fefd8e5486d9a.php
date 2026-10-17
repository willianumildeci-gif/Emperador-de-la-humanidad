<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Sistema de Ventas Web'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sistema.css')); ?>">
</head>
<body>
    <nav class="navbar">
        <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">Sistema de Ventas Web</a>
        <div class="navbar-links">
            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Home</a>
            <a class="nav-link <?php echo e(request()->routeIs('productos') ? 'active' : ''); ?>" href="<?php echo e(route('productos')); ?>">Producto</a>
            <a class="nav-link <?php echo e(request()->routeIs('empleados.*') ? 'active' : ''); ?>" href="<?php echo e(route('empleados.index')); ?>">Empleado</a>
            <a class="nav-link <?php echo e(request()->routeIs('clientes') ? 'active' : ''); ?>" href="<?php echo e(route('clientes')); ?>">Clientes</a>
            <a class="nav-link <?php echo e(request()->routeIs('ventas.*') ? 'active' : ''); ?>" href="<?php echo e(route('ventas.create')); ?>">Nueva Venta</a>
        </div>
        <details class="user-menu">
            <summary><?php echo e(Auth::user()->Nombres ?? Auth::user()->User); ?></summary>
            <div class="user-menu-content">
                <strong><?php echo e(Auth::user()->User); ?></strong>
                <span><?php echo e(Auth::user()->Telefono); ?></span>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="dropdown-action" type="submit">Salir</button>
                </form>
            </div>
        </details>
    </nav>
    <main class="page-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>
<?php /**PATH C:\Users\comel\OneDrive\Desktop\ROYECTO A PHP PURO\SistemasVentas\laravel\resources\views\layouts\app.blade.php ENDPATH**/ ?>