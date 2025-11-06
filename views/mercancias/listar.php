<div class="main-content">
  <h1>📦 Tipos de Mercancía</h1>

  <div class="actions">
    <a href="/logistica_global/controllers/mercanciaController.php?accion=crear" class="btn btn-primary">➕ Registrar nuevo tipo</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Costo Unitario (₡)</th>
        <th>Peso (kg)</th>
        <th>Volumen (m³)</th>
        <th>Restricciones</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($mercancias)): ?>
        <?php foreach ($mercancias as $m): ?>
          <tr>
            <td><?= $m['id_tipo_mercancia'] ?></td>
            <td><?= htmlspecialchars($m['nombre']) ?></td>
            <td><?= htmlspecialchars($m['descripcion']) ?></td>
            <td><?= number_format($m['costo_unitario'], 2) ?></td>
            <td><?= htmlspecialchars($m['peso_unitario_kg']) ?></td>
            <td><?= htmlspecialchars($m['volumen_unitario_m3']) ?></td>
            <td><?= htmlspecialchars($m['restricciones']) ?></td>
            <td><?= htmlspecialchars($m['estado']) ?></td>
            <td>
              <a href="/logistica_global/controllers/mercanciaController.php?accion=editar&id=<?= $m['id_tipo_mercancia'] ?>" class="btn-edit">✏️</a>
              <a href="/logistica_global/controllers/mercanciaController.php?accion=eliminar&id=<?= $m['id_tipo_mercancia'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar este tipo de mercancía?')">🗑️</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" style="text-align:center;">No hay tipos de mercancía registrados.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
