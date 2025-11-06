<div class="container">
  <h1><i class="fa-solid fa-file-signature"></i> Lista de Solicitudes de Transporte</h1>

  <!-- 🔔 Alertas visuales -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert success">✅ Solicitud registrada correctamente.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert info">✏️ Solicitud actualizada correctamente.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert danger">🗑️ Solicitud eliminada correctamente.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert danger">⚠️ Ocurrió un error al procesar la solicitud.</div>
  <?php endif; ?>

  <!-- 🟢 Botón para crear nueva solicitud -->
  <a href="/logistica_global/controllers/solicitudController.php?accion=crear" class="btn">
    <i class="fa-solid fa-plus"></i> Nueva Solicitud
  </a>

  <!-- 📋 Tabla -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Remitente</th>
        <th>Destinatario</th>
        <th>Tipo de Servicio</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Estado</th>
        <th>Prioridad</th>
        <th>Fecha</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($solicitudes)): ?>
        <?php foreach ($solicitudes as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['id_solicitud'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['correo_remitente'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['correo_destinatario'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['tipo_servicio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['origen'] ?? '-') ?></td>
            <td><?= htmlspecialchars($s['destino_general'] ?? '-') ?></td>

            <!-- Estado -->
            <td>
              <?php
                $estado = strtolower(str_replace(' ', '-', $s['estado'] ?? ''));
                $label = htmlspecialchars($s['estado'] ?? '-');
              ?>
              <span class="chip <?= $estado ?>"><?= $label ?></span>
            </td>

            <td><?= htmlspecialchars($s['prioridad'] ?? '-') ?></td>

            <!-- Fecha -->
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

            <!-- Acciones -->
            <td>
              <a href="/logistica_global/controllers/solicitudController.php?accion=editar&id=<?= urlencode($s['id_solicitud']) ?>" 
                 class="btn small edit">✏️ Editar</a>

              <a href="/logistica_global/controllers/solicitudController.php?accion=eliminar&id=<?= urlencode($s['id_solicitud']) ?>"
                 class="btn small delete"
                 onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">
                 🗑️ Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="10" style="text-align:center;">No hay solicitudes registradas.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- 🎨 Estilos -->
<style>
  .container {
    padding: 20px;
    max-width: 1200px;
    margin: auto;
  }

  h1 {
    color: #134074;
    margin-bottom: 15px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }

  th, td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
  }

  th {
    background-color: #f4f4f4;
  }

  .alert {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
  }
  .alert.success { background: #d4edda; color: #155724; }
  .alert.info { background: #cce5ff; color: #004085; }
  .alert.danger { background: #f8d7da; color: #721c24; }

  .btn {
    display: inline-block;
    background: #134074;
    color: white;
    text-decoration: none;
    padding: 8px 14px;
    border-radius: 6px;
    margin: 5px 0;
  }
  .btn:hover {
    background: #0e2a50;
  }

  .btn.small {
    font-size: 14px;
    padding: 6px 10px;
    margin-right: 5px;
  }

  .chip {
    padding: 4px 8px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
  }
  .chip.pendiente { background: #f0ad4e; }
  .chip.en-proceso { background: #0275d8; }
  .chip.completada { background: #5cb85c; }
  .chip.cancelada { background: #d9534f; }
</style>
