<div class="container">
  <h1>🚛 Conductores</h1>
  <a href="/logistica_global/controllers/conductorController.php?accion=crear" class="btn success">
    ➕ Nuevo Conductor
  </a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre Completo</th>
        <th>Cédula</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Estado</th>
        <th>Fecha Ingreso</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($conductores)): ?>
        <?php foreach ($conductores as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['id_conductor']) ?></td>

            <td><?= htmlspecialchars(
              trim(($c['nombre'] ?? '') . ' ' . ($c['apellido1'] ?? '') . ' ' . ($c['apellido2'] ?? ''))
            ) ?></td>

            <td><?= htmlspecialchars($c['cedula'] ?? '-') ?></td>
            <td><?= htmlspecialchars($c['telefono'] ?? '-') ?></td>
            <td><?= htmlspecialchars($c['correo'] ?? '-') ?></td>

            <td>
              <span class="chip <?= strtolower($c['estado'] ?? 'desconocido') ?>">
                <?= htmlspecialchars($c['estado'] ?? '-') ?>
              </span>
            </td>

            <td>
              <?php
                $fecha = $c['fecha_ingreso'] ?? null;
                if ($fecha instanceof DateTime) {
                    echo $fecha->format('Y-m-d');
                } elseif (is_string($fecha)) {
                    echo htmlspecialchars(substr($fecha, 0, 10));
                } else {
                    echo '-';
                }
              ?>
            </td>

            <td>
              <a
                href="/logistica_global/controllers/conductorController.php?accion=editar&id=<?= urlencode($c['id_conductor']) ?>"
                class="btn small edit"
              >✏️</a>

              <a
                href="/logistica_global/controllers/conductorController.php?accion=eliminar&id=<?= urlencode($c['id_conductor']) ?>"
                class="btn small delete"
                onclick="return confirm('¿Eliminar este conductor?')"
              >🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" style="text-align:center; padding:1rem;">
            🚫 No hay conductores registrados.
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
