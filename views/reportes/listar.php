<!-- ============================================================
📄 views/reportes/listar.php
============================================================ -->
<div class="container">
  <h1>📊 <?= htmlspecialchars($titulo) ?></h1>

  <div class="actions">
    <a href="/logistica_global/controllers/reporteEficienciaController.php?accion=generar" class="btn-primary">+ Generar Reporte</a>
  </div>

  <?php if (empty($reportes)): ?>
    <div class="alert">
      ⚠️ No hay reportes generados todavía.
    </div>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Viaje</th>
          <th>Vehículo</th>
          <th>Conductor</th>
          <th>Kilómetros</th>
          <th>Eficiencia %</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reportes as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['id_reporte'] ?? '-') ?></td>
            <td>#<?= htmlspecialchars($r['id_viaje'] ?? '-') ?></td>
            <td><?= htmlspecialchars($r['vehiculo'] ?? 'Sin vehículo') ?></td>
            <td><?= htmlspecialchars($r['conductor'] ?? 'No asignado') ?></td>
            <td><?= htmlspecialchars(number_format($r['total_km'] ?? 0, 2)) ?></td>
            <td><?= htmlspecialchars(number_format($r['eficiencia_porcentaje'] ?? 0, 2)) ?>%</td>
            <td><?= htmlspecialchars($r['fecha_generacion'] ?? '-') ?></td>
            <td>
              <a href="/logistica_global/controllers/reporteEficienciaController.php?accion=detalle&id=<?= $r['id_reporte'] ?>">Ver</a> |
              <a href="/logistica_global/controllers/reporteEficienciaController.php?accion=eliminar&id=<?= $r['id_reporte'] ?>" onclick="return confirm('¿Eliminar este reporte?')">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<style>
.container {
  max-width: 1100px;
  margin: 0 auto;
  background: #fff;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}
h1 {
  text-align: center;
  color: #003366;
  margin-bottom: 1rem;
  font-weight: 700;
}
.actions {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1rem;
}
.btn-primary {
  background-color: #0d6efd;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
}
.btn-primary:hover {
  background-color: #0b5ed7;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}
thead {
  background-color: #003366;
  color: white;
}
th, td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: center;
}
tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}
tbody tr:hover {
  background-color: #e9ecef;
}
.alert {
  background: #fff3cd;
  color: #664d03;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #ffeeba;
  text-align: center;
  margin-bottom: 1rem;
}
</style>
