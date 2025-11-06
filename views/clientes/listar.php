<div class="container">
  <h1><i class="fa-solid fa-users"></i> Lista de Clientes</h1>

  <a href="/logistica_global/controllers/clienteController.php?accion=crear" class="btn">
    <i class="fa-solid fa-plus"></i> Nuevo Cliente
  </a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre / Empresa</th>
        <th>Cédula</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Provincia</th>
        <th>Fecha Registro</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($clientes)): ?>
        <?php foreach ($clientes as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['id_cliente']) ?></td>
            <td>
              <?= $c['tipo_identificacion'] === 'FISICO'
                ? htmlspecialchars(trim($c['nombre'] . ' ' . $c['primer_apellido'] . ' ' . $c['segundo_apellido']))
                : htmlspecialchars($c['nombre_empresa']) ?>
            </td>
            <td>
              <?= $c['tipo_identificacion'] === 'FISICO'
                ? htmlspecialchars($c['cedula_fisica'])
                : htmlspecialchars($c['cedula_juridica']) ?>
            </td>
            <td><?= htmlspecialchars($c['correo']) ?></td>
            <td><?= htmlspecialchars($c['telefono']) ?></td>
            <td><?= htmlspecialchars($c['provincia']) ?></td>
            <td><?= $c['fecha_registro']->format('Y-m-d') ?></td>
            <td>
              <a href="/logistica_global/controllers/clienteController.php?accion=editar&id=<?= $c['id_cliente'] ?>" class="btn" style="background:#007bff;margin-right:5px;">
                <i class="fa-solid fa-pen"></i> Editar
              </a>
              <a href="/logistica_global/controllers/clienteController.php?accion=eliminar&id=<?= $c['id_cliente'] ?>"
                 class="btn"
                 style="background:#cc0000;"
                 onclick="return confirm('¿Seguro que deseas eliminar este cliente?');">
                 <i class="fa-solid fa-trash"></i> Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8">No hay clientes registrados.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>