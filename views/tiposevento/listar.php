<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <!-- Botón para crear nuevo tipo de evento -->
  <a href="/logistica_global/controllers/tipoEventoController.php?accion=crear" class="btn-primary">
    + Nuevo Tipo de Evento
  </a>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php if (empty($tipos)): ?>
        <tr>
          <td colspan="5" style="text-align:center;">No hay tipos de evento registrados.</td>
        </tr>
      <?php else: foreach ($tipos as $t): ?>
        <?php
          // Valores seguros si las columnas no existen en la tabla
          $descripcion = $t['descripcion'] ?? '-';
          $estado = $t['estado'] ?? 'Activo';
          $color = ($estado === 'Activo') ? 'green' : 'red';
        ?>
        <tr>
          <td><?= htmlspecialchars($t['id_tipo_evento']) ?></td>
          <td><?= htmlspecialchars($t['nombre']) ?></td>
          <td><?= htmlspecialchars($descripcion) ?></td>
          <td>
            <span style="color:<?= $color ?>; font-weight:600;">
              <?= htmlspecialchars($estado) ?>
            </span>
          </td>
          <td style="display:flex; gap:6px; flex-wrap:wrap;">
            <a href="/logistica_global/controllers/tipoEventoController.php?accion=editar&id=<?= $t['id_tipo_evento'] ?>" class="btn-edit">
              Editar
            </a>
            <a href="/logistica_global/controllers/tipoEventoController.php?accion=eliminar&id=<?= $t['id_tipo_evento'] ?>"
               class="btn-danger"
               onclick="return confirm('¿Eliminar este tipo de evento?')">
               Eliminar
            </a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
