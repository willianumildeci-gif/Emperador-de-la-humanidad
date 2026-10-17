<?php
require_once __DIR__ . '/config.php';
start_app_session();

if (!empty($_SESSION['usuario'])) {
    header('Location: principal.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'La solicitud expiró. Intente ingresar nuevamente.';
    } else {
        $username = trim((string) ($_POST['txtuser'] ?? ''));
        $password = (string) ($_POST['txtpass'] ?? '');

        if ($username === '' || $password === '') {
            $error = 'Ingrese su usuario y contraseña.';
        } else {
            try {
                $query = db_connection()->prepare(
                    'SELECT IdEmpleado, User, Nombres, Telefono
                     FROM empleado
                     WHERE User = :username AND Dni = :password
                     LIMIT 1'
                );
                $query->execute([
                    'username' => $username,
                    'password' => $password,
                ]);
                $usuario = $query->fetch();

                if ($usuario) {
                    session_regenerate_id(true);
                    $_SESSION['usuario'] = $usuario;
                    header('Location: principal.php');
                    exit;
                }

                $error = 'Usuario o contraseña incorrectos.';
            } catch (PDOException $exception) {
                error_log('Error al iniciar sesión: ' . $exception->getMessage());
                $error = 'No fue posible conectar con la base de datos. Revise la configuración.';
            }
        }
    }
}
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
        <div class="container mt-4 col-lg-4">
            <div class="card col-sm-10">
                <div class="card-body">
                    <form class="form-sign" action="index.php" method="POST">
                        <div class="form-group text-center">
                            <h3>Login</h3>
                            <img src="web/img/no bitches.jpg" height="70" width="170" alt="">
                            <label class="d-block">Bienvenido al Sistema</label>
                        </div>
                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert"><?= escape_html($error) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="txtuser">Usuario:</label>
                            <input id="txtuser" class="form-control" type="text" name="txtuser"
                                   placeholder="Ingrese su Usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="txtpass">Contraseña:</label>
                            <input id="txtpass" class="form-control" type="password" name="txtpass"
                                   placeholder="Ingrese su Contraseña" required>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                        <button class="btn btn-primary btn-block" type="submit">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="web/bootstrap/jquery-3.3.1.slim.min.js"></script>
        <script src="web/bootstrap/popper.min.js"></script>
        <script src="web/bootstrap/bootstrap.min.js"></script>
    </body>
</html>
