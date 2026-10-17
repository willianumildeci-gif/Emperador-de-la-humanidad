<?php
require_once __DIR__ . '/config.php';
require_login();
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Ventas Web</title>
        <link rel="stylesheet" href="web/css/estilos.css">
        <link rel="stylesheet" href="web/bootstrap/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-info">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Mostrar navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="principal.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a style="margin-left: 10px; border: none" class="btn btn-outline-light"
                           href="producto.php" target="myFrame">Producto</a>
                    </li>
                    <li class="nav-item">
                        <a style="margin-left: 10px; border: none" class="btn btn-outline-light"
                           href="empleado.php" target="myFrame">Empleado</a>
                    </li>
                    <li class="nav-item">
                        <a style="margin-left: 10px; border: none" class="btn btn-outline-light"
                           href="clientes.php" target="myFrame">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a style="margin-left: 10px; border: none" class="btn btn-outline-light"
                           href="registrar_venta.php" target="myFrame">Nueva Venta</a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button style="border: none" class="btn btn-outline-light dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= escape_html($usuario['Nombres']) ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right text-center">
                    <a class="dropdown-item" href="#">
                        <img src="web/img/no bitches.jpg" height="60" width="60" alt="">
                    </a>
                    <span class="dropdown-item"><?= escape_html($usuario['User']) ?></span>
                    <span class="dropdown-item"><?= escape_html($usuario['Telefono']) ?></span>
                    <div class="dropdown-divider"></div>
                    <form action="logout.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                        <button class="dropdown-item" type="submit">Salir</button>
                    </form>
                </div>
            </div>
        </nav>
        <div class="m-4" style="height: 550px;">
            <iframe name="myFrame" title="Contenido del sistema"
                    style="height: 100%; width: 100%; border: none"></iframe>
        </div>
        <script src="web/bootstrap/jquery-3.3.1.slim.min.js"></script>
        <script src="web/bootstrap/popper.min.js"></script>
        <script src="web/bootstrap/bootstrap.min.js"></script>
    </body>
</html>
