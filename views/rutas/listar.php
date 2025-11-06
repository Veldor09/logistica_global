<?php /** @var array $rutas */ ?>
<div class="card">
  <div class="mb-3" style="display:flex;justify-content:space-between;align-items:center;">
    <h2 style="margin:0">Gestión de Rutas</h2>
    <a class="btn btn-primary" href="/logistica_global/controllers/rutaController.php?accion=crear">+ Nueva Ruta</a>
  </div>

  <?php if (empty($rutas)): ?>
    <p>No hay rutas registradas.</p>
  <?php else: ?>
    <div style="overflow:auto">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre ruta</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Distancia (km)</th>
            <th>Tiempo (hr)</th>
            <th>Estado</th>
            <th>Tramos</th>
            <th style="width:210px">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rutas as $r): ?>
            <tr>
              <td><?= (int)$r['id_ruta'] ?></td>
              <td><?= htmlspecialchars($r['nombre_ruta'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['origen'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['destino'] ?? '') ?></td>
              <td><?= htmlspecialchars((string)($r['distancia_total_km'] ?? '')) ?></td>
              <td><?= htmlspecialchars((string)($r['tiempo_estimado_hr'] ?? '')) ?></td>
              <td><?= htmlspecialchars($r['estado'] ?? '') ?></td>
              <td><?= (int)($r['total_tramos'] ?? 0) ?></td>
              <td>
                <a class="btn" href="/logistica_global/controllers/rutaController.php?accion=editar&id=<?= (int)$r['id_ruta'] ?>">✏️ Editar</a>
                <a class="btn btn-danger" href="/logistica_global/controllers/rutaController.php?accion=eliminar&id=<?= (int)$r['id_ruta'] ?>" onclick="return confirm('¿Eliminar la ruta #<?= (int)$r['id_ruta'] ?>?');">🗑️ Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
