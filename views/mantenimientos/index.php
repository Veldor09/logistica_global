<?php include 'views/layout/header.php'; ?>

<h2>Listado de Mantenimientos</h2>
<a href="index.php?controller=mantenimiento&action=crear" class="btn btn-primary mb-3">+ Nuevo Mantenimiento</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Vehículo</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Costo</th>
            <th>Proveedor</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mantenimientos as $m): ?>
        <tr>
            <td><?= $m['id_mantenimiento'] ?></td>
            <td><?= $m['vehiculo'] ?></td>
            <td><?= date_format($m['fecha_mantenimiento'], 'Y-m-d') ?></td>
            <td><?= $m['tipo_mantenimiento'] ?></td>
            <td><?= $m['descripcion'] ?></td>
            <td><?= $m['costo'] ?></td>
            <td><?= $m['proveedor'] ?></td>
            <td>
                <a href="index.php?controller=mantenimiento&action=editar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="index.php?controller=mantenimiento&action=eliminar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este mantenimiento?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/layout/footer.php'; ?>
