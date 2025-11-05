<div class="p-4 border bg-[#71c2ff] rounded-2xl">
  <h2 class="text-3xl font-bold text-center mb-4">Lista de Órdenes de Transporte</h2>

  <!-- 🔔 Mensajes de éxito -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert success">✅ Orden registrada correctamente.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert info">✏️ Orden actualizada correctamente.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert danger">🗑️ Orden eliminada correctamente.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert danger">❌ Ocurrió un error en la operación.</div>
  <?php endif; ?>

  <!-- BOTÓN CREAR -->
  <div class="mb-3 text-right">
    <a href="/logistica_global/controllers/ordenController.php?accion=crear" class="btn btn-primary">
      ➕ Nueva Orden
    </a>
  </div>

  <!-- TABLA DE ÓRDENES -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-700">
        <tr>
          <th class="px-6 py-3 text-left">ID</th>
          <th class="px-6 py-3 text-left">Remitente</th>
          <th class="px-6 py-3 text-left">Destinatario</th>
          <th class="px-6 py-3 text-left">Solicitud</th>
          <th class="px-6 py-3 text-left">Tipo Servicio</th>
          <th class="px-6 py-3 text-left">Origen</th>
          <th class="px-6 py-3 text-left">Destino</th>
          <th class="px-6 py-3 text-left">Peso (kg)</th>
          <th class="px-6 py-3 text-left">Estado</th>
          <th class="px-6 py-3 text-left">Acciones</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-100">
        <?php if (!empty($ordenes)): ?>
          <?php foreach ($ordenes as $o): ?>
            <tr class="hover:bg-blue-50">
              <td class="px-6 py-3"><?= htmlspecialchars($o['id_orden']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['correo_remitente']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['correo_destinatario']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['id_solicitud']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['tipo_servicio'] ?? '-') ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['direccion_origen'] ?? '-') ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['direccion_destino'] ?? '-') ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($o['peso_estimado_kg'] ?? '0') ?></td>
              <td class="px-6 py-3">
                <span class="estado <?= strtolower($o['estado']) ?>">
                  <?= htmlspecialchars($o['estado']) ?>
                </span>
              </td>
              <td class="px-6 py-3 space-x-2">
                <a href="/logistica_global/controllers/ordenController.php?accion=editar&id=<?= $o['id_orden'] ?>" 
                   class="btn btn-warning text-sm">✏️ Editar</a>
                <a href="/logistica_global/controllers/ordenController.php?accion=eliminar&id=<?= $o['id_orden'] ?>" 
                   class="btn btn-danger text-sm"
                   onclick="return confirm('¿Seguro que deseas eliminar esta orden?');">🗑️ Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center py-6 text-gray-500">
              No hay órdenes registradas aún.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
