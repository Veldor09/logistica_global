<?php
// views/clientes/listar.php
// Variables esperadas: $clientes, $busqueda, $estado, $msg, $type
?>
<?php require __DIR__ . '/../layout.php'; ?>

<div class="container">
  <h1>Clientes</h1>

  <?php if (!empty($msg)): ?>
    <div class="alert <?= $type === 'danger' ? 'danger' : 'success' ?>"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="form-actions" style="justify-content: space-between; align-items:center;">
    <form method="GET" action="/logistica_global/controllers/clienteController.php" style="display:flex; gap:10px; align-items:center;">
      <input type="hidden" name="accion" value="listar">
      <input type="text" name="q" value="<?= htmlspecialchars($busqueda ?? '') ?>" placeholder="Buscar por nombre, empresa, cédula o correo" style="min-width:280px;">
      <select name="estado">
        <option value="todos"   <?= ($estado ?? '')==='todos'?'selected':'' ?>>Todos</option>
        <option value="Activo"  <?= ($estado ?? '')==='Activo'?'selected':'' ?>>Activos</option>
        <option value="Inactivo"<?= ($estado ?? '')==='Inactivo'?'selected':'' ?>>Inactivos</option>
      </select>
      <button type="submit" class="btn primary btn-sm">Buscar</button>
      <a class="btn secondary btn-sm" href="/logistica_global/controllers/clienteController.php?accion=listar">Limpiar</a>
    </form>

    <a class="btn success" href="/logistica_global/controllers/clienteController.php?accion=crear">➕ Nuevo Cliente</a>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Nombre / Empresa</th>
          <th>Cédula</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Estado</th>
          <th style="width:150px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($clientes)): ?>
          <tr><td colspan="8">No hay resultados.</td></tr>
        <?php else: foreach ($clientes as $i => $c): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><span class="badge <?= $c['tipo_identificacion']==='Fisica'?'badge-success':'badge-danger' ?>">
              <?= htmlspecialchars($c['tipo_identificacion']) ?>
            </span></td>
            <td><?= htmlspecialchars($c['display_nombre']) ?></td>
            <td><?= htmlspecialchars($c['display_cedula'] ?? '') ?></td>
            <td><?= htmlspecialchars($c['correo'] ?? '') ?></td>
            <td><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
            <td><?= htmlspecialchars($c['estado']) ?></td>
            <td>
              <a class="btn primary btn-sm" href="/logistica_global/controllers/clienteController.php?accion=editar&id=<?= (int)$c['id_cliente'] ?>">Editar</a>

              <form method="POST"
                    action="/logistica_global/controllers/clienteController.php?accion=eliminar&id=<?= (int)$c['id_cliente'] ?>"
                    style="display:inline;"
                    onsubmit="return confirmarEliminar(<?= (int)$c['id_cliente'] ?>)">
                <button type="submit" class="btn danger btn-sm">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function confirmarEliminar(id) {
  return confirm("¿Seguro que deseas INACTIVAR al cliente #" + id + "?\n(No se eliminará físicamente; podrás reactivarlo editándolo).");
}
</script>
