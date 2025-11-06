<div class="container">
  <h1>🚚 <?= htmlspecialchars($titulo) ?></h1>

  <section class="card">
    <h2>🧾 Datos del Viaje</h2>
    <table class="table table-bordered">
      <tr><th>ID Viaje</th><td>#<?= $viaje['id_viaje'] ?></td></tr>
      <tr><th>Órdenes Asociadas</th><td><?= !empty($ordenes) ? '#' . implode(', #', $ordenes) : '-' ?></td></tr>
      <tr><th>Conductor</th><td><?= htmlspecialchars($viaje['conductor'] ?? '-') ?></td></tr>
      <tr><th>Vehículo</th><td><?= htmlspecialchars($viaje['vehiculo'] ?? '-') ?></td></tr>
      <tr><th>Ruta</th><td><?= htmlspecialchars($viaje['nombre_ruta'] ?? '-') ?></td></tr>
      <tr><th>Fecha Inicio</th><td><?= htmlspecialchars($viaje['fecha_inicio'] ?? '-') ?></td></tr>
      <tr><th>Fecha Fin</th><td><?= htmlspecialchars($viaje['fecha_fin'] ?? '-') ?></td></tr>
      <tr><th>Estado</th><td><strong><?= htmlspecialchars($viaje['estado'] ?? '-') ?></strong></td></tr>
      <tr><th>Observaciones</th><td><?= nl2br(htmlspecialchars($viaje['observaciones'] ?? '-')) ?></td></tr>
    </table>
  </section>

  <section class="card">
    <h2>⚖️ Resumen de Carga</h2>
    <table class="table table-bordered">
      <tr><th>Total Órdenes</th><td><?= htmlspecialchars($resumen['total_ordenes'] ?? 0) ?></td></tr>
      <tr><th>Peso Total (kg)</th><td><?= htmlspecialchars(number_format($resumen['peso_total_kg'] ?? 0, 2)) ?></td></tr>
      <tr><th>Volumen Total (m³)</th><td><?= htmlspecialchars(number_format($resumen['volumen_total_m3'] ?? 0, 2)) ?></td></tr>
    </table>
  </section>

  <div class="form-actions">
    <a href="/logistica_global/controllers/viajeController.php?accion=listar" class="btn-secondary">
      ⬅ Volver al listado
    </a>
  </div>
</div>

<style>
.container { max-width: 900px; margin: 0 auto; }
.card {
  background: #fff; padding: 1.5rem; margin-bottom: 1.5rem;
  border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
.table th, .table td { padding: 8px; border-bottom: 1px solid #ddd; }
.table th { background: #0d6efd; color: #fff; text-align: left; }
.btn-secondary {
  background: #6c757d; color: white; text-decoration: none;
  padding: 10px 16px; border-radius: 8px;
}
.btn-secondary:hover { background: #5a6268; }
</style>
