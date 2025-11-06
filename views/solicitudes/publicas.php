<div class="container" style="max-width: 1000px; margin:auto; padding:25px;">
  <h1><i class="fa-solid fa-file-signature"></i> Solicitudes de Transporte (Público)</h1>
  <p style="color:#333;">Estas son las solicitudes registradas en el sistema. Si desea solicitar un servicio, puede registrar una nueva solicitud.</p>

  <!-- 🟢 Botón para crear solicitud pública -->
  <div style="margin:15px 0;">
    <a href="/logistica_global/controllers/solicitudController.php?accion=crear_publica" class="btn-primary">
      📝 Registrar nueva solicitud
    </a>
  </div>

  <!-- 📋 Tabla -->
  <table style="width:100%; border-collapse:collapse; border:1px solid #ccc; background:#fff;">
    <thead style="background:#134074; color:white;">
      <tr>
        <th style="padding:8px;">ID</th>
        <th>Tipo de Servicio</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Estado</th>
        <th>Prioridad</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($solicitudes)): ?>
        <?php foreach ($solicitudes as $s): ?>
          <tr style="text-align:center;">
            <td><?= htmlspecialchars($s['id_solicitud'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['tipo_servicio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['origen'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['destino_general'] ?? '-') ?></td>
            <td>
              <span class="chip <?= strtolower(str_replace(' ', '-', $s['estado'] ?? '')) ?>">
                <?= htmlspecialchars($s['estado'] ?? '-') ?>
              </span>
            </td>
            <td><?= htmlspecialchars($s['prioridad'] ?? '-') ?></td>
            <td>
              <?php
                if (!empty($s['fecha_solicitud'])) {
                  echo is_object($s['fecha_solicitud'])
                    ? $s['fecha_solicitud']->format('Y-m-d')
                    : date('Y-m-d', strtotime($s['fecha_solicitud']));
                } else {
                  echo '-';
                }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align:center; padding:15px;">No hay solicitudes registradas.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- 🔙 Volver -->
  <div style="margin-top: 25px; text-align:center;">
    <a href="/logistica_global/index.php" class="btn-secondary">⬅ Volver al inicio</a>
  </div>
</div>

<!-- 🎨 Estilos coherentes con todo el sistema -->
<style>
  .container h1 {
    color:#134074;
    margin-bottom:10px;
  }

  table th, table td {
    border:1px solid #ccc;
    padding:8px;
  }

  table th {
    background:#134074;
    color:white;
    text-transform:uppercase;
    font-size:0.9em;
  }

  table tr:nth-child(even) {
    background:#f9f9f9;
  }

  .btn-primary {
    display:inline-block;
    background:#134074;
    color:white;
    padding:8px 14px;
    border:none;
    border-radius:6px;
    text-decoration:none;
    transition:background 0.2s;
  }
  .btn-primary:hover {
    background:#0e2a50;
  }

  .btn-secondary {
    display:inline-block;
    color:#134074;
    border:1px solid #134074;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    transition:all 0.2s;
  }
  .btn-secondary:hover {
    background:#134074;
    color:white;
  }

  /* Chips de estado */
  .chip {
    display:inline-block;
    padding:3px 8px;
    border-radius:12px;
    font-size:0.85em;
    color:white;
    font-weight:bold;
  }
  .chip.pendiente { background:#e67e22; }
  .chip.en-proceso { background:#3498db; }
  .chip.completada { background:#2ecc71; }
  .chip.cancelada { background:#e74c3c; }
</style>
