

<?php $__env->startSection('title', 'Empleado'); ?>

<?php $__env->startSection('content'); ?>
    <div class="employee-page">
        <section class="card employee-form-card">
            <div class="card-body">
                <h1 class="section-title"><?php echo e($empleado->exists ? 'Editar empleado' : 'Empleado'); ?></h1>
                <?php if(session('success')): ?>
                    <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php $__errorArgs = ['employee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger" role="alert"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($errors->any() && !$errors->has('employee')): ?>
                    <div class="alert alert-danger" role="alert">Revise los campos e intente nuevamente.</div>
                <?php endif; ?>
                <form action="<?php echo e($empleado->exists ? route('empleados.update', $empleado->IdEmpleado) : route('empleados.store')); ?>"
                      method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($empleado->exists): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="Dni">Dni</label>
                        <input id="Dni" name="Dni" class="form-control" type="text" maxlength="8" required
                               value="<?php echo e(old('Dni', $empleado->Dni)); ?>">
                        <?php $__errorArgs = ['Dni'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="field-error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="Nombres">Nombres</label>
                        <input id="Nombres" name="Nombres" class="form-control" type="text" maxlength="255"
                               value="<?php echo e(old('Nombres', $empleado->Nombres)); ?>">
                        <?php $__errorArgs = ['Nombres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="field-error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="Telefono">Telefono</label>
                        <input id="Telefono" name="Telefono" class="form-control" type="text" maxlength="9"
                               value="<?php echo e(old('Telefono', $empleado->Telefono)); ?>">
                        <?php $__errorArgs = ['Telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="field-error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <input id="Estado" name="Estado" class="form-control" type="text" maxlength="1"
                               value="<?php echo e(old('Estado', $empleado->Estado)); ?>">
                        <?php $__errorArgs = ['Estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="field-error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="User">Usuario</label>
                        <input id="User" name="User" class="form-control" type="text" maxlength="8"
                               value="<?php echo e(old('User', $empleado->User)); ?>">
                        <?php $__errorArgs = ['User'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="field-error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-info" type="submit"> <?php echo e($empleado->exists ? 'Actualizar' : 'Agregar'); ?></button>
                        <?php if($empleado->exists): ?>
                            <a class="btn btn-secondary" href="<?php echo e(route('empleados.index')); ?>">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>
        <section class="employee-table">
            <div class="table-responsive">
                <table class="table">
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
                        <?php $__empty_1 = true; $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->IdEmpleado); ?></td>
                                <td><?php echo e($item->Dni); ?></td>
                                <td><?php echo e($item->Nombres); ?></td>
                                <td><?php echo e($item->Telefono); ?></td>
                                <td><?php echo e($item->Estado); ?></td>
                                <td><?php echo e($item->User); ?></td>
                                <td class="actions-cell">
                                    <a class="btn btn-warning btn-sm" href="<?php echo e(route('empleados.edit', $item->IdEmpleado)); ?>">Editar</a>
                                    <form action="<?php echo e(route('empleados.destroy', $item->IdEmpleado)); ?>" method="POST"
                                          onsubmit="return confirm('¿Eliminar este empleado?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center">No hay empleados registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\comel\OneDrive\Desktop\ROYECTO A PHP PURO\SistemasVentas\laravel\resources\views\empleados\index.blade.php ENDPATH**/ ?>