<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>
  <p><strong>ID Reporte:</strong> <?= $r['id_reporte'] ?></p>
  <p><strong>Viaje:</strong> <?= $r['id_viaje'] ?></p>
  <p><strong>Vehículo:</strong> <?= $r['id_vehiculo'] ?></p>
  <p><strong>Conductor:</strong> <?= $r['id_conductor'] ?></p>
  <p><strong>Kilómetros:</strong> <?= $r['total_km'] ?></p>
  <p><strong>Eficiencia:</strong> <?= $r['eficiencia_porcentaje'] ?>%</p>
  <p><strong>Observaciones:</strong> <?= htmlspecialchars($r['observaciones'] ?? '-') ?></p>
  <a href="/logistica_global/controllers/reporteEficienciaController.php?accion=listar" class="btn-secondary">Volver</a>
</div>
