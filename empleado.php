<?php
require_once __DIR__ . '/config.php';
require_login();

$pdo = null;
$empleados = [];
$empleado = [
    'IdEmpleado' => '',
    'Dni' => '',
    'Nombres' => '',
    'Telefono' => '',
    'Estado' => '',
    'User' => '',
];
$error = '';
$flash = take_flash();
$accion = (string) ($_POST['accion'] ?? $_GET['accion'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'La solicitud expiró. Recargue la página e intente nuevamente.';
    } else {
        try {
            $pdo = db_connection();

            if ($accion === 'Agregar' || $accion === 'Actualizar') {
                $empleado = [
                    'IdEmpleado' => trim((string) ($_POST['id'] ?? '')),
                    'Dni' => trim((string) ($_POST['txtDni'] ?? '')),
                    'Nombres' => trim((string) ($_POST['txtNombres'] ?? '')),
                    'Telefono' => trim((string) ($_POST['txtTel'] ?? '')),
                    'Estado' => trim((string) ($_POST['txtEstado'] ?? '')),
                    'User' => trim((string) ($_POST['txtUser'] ?? '')),
                ];

                if ($empleado['Dni'] === '') {
                    $error = 'El DNI es obligatorio.';
                } elseif (strlen($empleado['Dni']) > 8
                    || strlen($empleado['Telefono']) > 9
                    || strlen($empleado['Estado']) > 1
                    || strlen($empleado['User']) > 8) {
                    $error = 'Revise la longitud de los campos e intente nuevamente.';
                } elseif ($accion === 'Agregar') {
                    $query = $pdo->prepare(
                        'INSERT INTO empleado (Dni, Nombres, Telefono, Estado, User)
                         VALUES (:dni, :nombres, :telefono, :estado, :usuario)'
                    );
                    $query->execute([
                        'dni' => $empleado['Dni'],
                        'nombres' => $empleado['Nombres'],
                        'telefono' => $empleado['Telefono'],
                        'estado' => $empleado['Estado'],
                        'usuario' => $empleado['User'],
                    ]);
                    set_flash('Empleado agregado correctamente.');
                    header('Location: empleado.php');
                    exit;
                } else {
                    $query = $pdo->prepare(
                        'UPDATE empleado
                         SET Dni = :dni, Nombres = :nombres, Telefono = :telefono,
                             Estado = :estado, User = :usuario
                         WHERE IdEmpleado = :id'
                    );
                    $query->execute([
                        'dni' => $empleado['Dni'],
                        'nombres' => $empleado['Nombres'],
                        'telefono' => $empleado['Telefono'],
                        'estado' => $empleado['Estado'],
                        'usuario' => $empleado['User'],
                        'id' => (int) $empleado['IdEmpleado'],
                    ]);
                    set_flash('Empleado actualizado correctamente.');
                    header('Location: empleado.php');
                    exit;
                }
            } elseif ($accion === 'Delete') {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if (!$id || $id < 1) {
                    $error = 'El empleado seleccionado no es válido.';
                } else {
                    $query = $pdo->prepare('DELETE FROM empleado WHERE IdEmpleado = :id');
                    $query->execute(['id' => $id]);
                    set_flash('Empleado eliminado correctamente.');
                    header('Location: empleado.php');
                    exit;
                }
            } else {
                $error = 'La acción solicitada no es válida.';
            }
        } catch (PDOException $exception) {
            error_log('Error al guardar empleado: ' . $exception->getMessage());
            $error = 'No se pudo completar la operación. Verifique los datos y que el empleado no tenga ventas asociadas.';
        }
    }
}

try {
    if (!$pdo instanceof PDO) {
        $pdo = db_connection();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $accion === 'Editar') {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id && $id > 0) {
            $query = $pdo->prepare('SELECT * FROM empleado WHERE IdEmpleado = :id');
            $query->execute(['id' => $id]);
            $loadedEmployee = $query->fetch();
            if ($loadedEmployee) {
                $empleado = $loadedEmployee;
            } else {
                $error = 'No se encontró el empleado seleccionado.';
            }
        } else {
            $error = 'El empleado seleccionado no es válido.';
        }
    }

    $empleados = $pdo->query('SELECT * FROM empleado ORDER BY IdEmpleado')->fetchAll();
} catch (PDOException $exception) {
    error_log('Error al consultar empleados: ' . $exception->getMessage());
    $error = 'No fue posible cargar los empleados. Revise la conexión y la base de datos.';
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Empleado</title>
        <link rel="stylesheet" href="web/bootstrap/bootstrap.min.css">
    </head>
    <body>
        <div class="d-flex">
            <div class="card col-sm-5">
                <div class="card-body">
                    <form action="empleado.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                        <?php if ($empleado['IdEmpleado'] !== ''): ?>
                            <input type="hidden" name="id" value="<?= escape_html((string) $empleado['IdEmpleado']) ?>">
                        <?php endif; ?>
                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert"><?= escape_html($error) ?></div>
                        <?php endif; ?>
                        <?php if ($flash): ?>
                            <div class="alert alert-<?= escape_html($flash['type']) ?>" role="alert">
                                <?= escape_html($flash['message']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="txtDni">Dni</label>
                            <input id="txtDni" type="text" value="<?= escape_html($empleado['Dni']) ?>"
                                   name="txtDni" class="form-control" maxlength="8" required>
                        </div>
                        <div class="form-group">
                            <label for="txtNombres">Nombres</label>
                            <input id="txtNombres" type="text" value="<?= escape_html($empleado['Nombres']) ?>"
                                   name="txtNombres" class="form-control" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="txtTel">Telefono</label>
                            <input id="txtTel" type="text" value="<?= escape_html($empleado['Telefono']) ?>"
                                   name="txtTel" class="form-control" maxlength="9">
                        </div>
                        <div class="form-group">
                            <label for="txtEstado">Estado</label>
                            <input id="txtEstado" type="text" value="<?= escape_html($empleado['Estado']) ?>"
                                   name="txtEstado" class="form-control" maxlength="1">
                        </div>
                        <div class="form-group">
                            <label for="txtUser">Usuario</label>
                            <input id="txtUser" type="text" value="<?= escape_html($empleado['User']) ?>"
                                   name="txtUser" class="form-control" maxlength="8">
                        </div>
                        <button type="submit" name="accion" value="Agregar" class="btn btn-info">Agregar</button>
                        <button type="submit" name="accion" value="Actualizar" class="btn btn-success"
                                <?= $empleado['IdEmpleado'] === '' ? 'disabled' : '' ?>>Actualizar</button>
                        <?php if ($empleado['IdEmpleado'] !== ''): ?>
                            <a href="empleado.php" class="btn btn-secondary">Cancelar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>NOMBRES</th>
                                <th>TELEFONO</th>
                                <th>ESTADO</th>
                                <th>USUARIO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($empleados as $item): ?>
                                <tr>
                                    <td><?= escape_html((string) $item['IdEmpleado']) ?></td>
                                    <td><?= escape_html($item['Dni']) ?></td>
                                    <td><?= escape_html($item['Nombres']) ?></td>
                                    <td><?= escape_html($item['Telefono']) ?></td>
                                    <td><?= escape_html($item['Estado']) ?></td>
                                    <td><?= escape_html($item['User']) ?></td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-warning btn-sm"
                                           href="empleado.php?accion=Editar&amp;id=<?= escape_html((string) $item['IdEmpleado']) ?>">
                                            Editar
                                        </a>
                                        <form action="empleado.php" method="POST" class="d-inline">
                                            <input type="hidden" name="csrf_token"
                                                   value="<?= escape_html(csrf_token()) ?>">
                                            <input type="hidden" name="id"
                                                   value="<?= escape_html((string) $item['IdEmpleado']) ?>">
                                            <button class="btn btn-danger btn-sm" type="submit" name="accion"
                                                    value="Delete"
                                                    onclick="return confirm('¿Eliminar este empleado?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$empleados): ?>
                                <tr><td colspan="7" class="text-center">No hay empleados registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="web/bootstrap/jquery-3.3.1.slim.min.js"></script>
        <script src="web/bootstrap/popper.min.js"></script>
        <script src="web/bootstrap/bootstrap.min.js"></script>
    </body>
</html>
