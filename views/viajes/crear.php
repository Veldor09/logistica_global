<div class="container">
  <h1>🚚 Registrar Viaje</h1>

  <a class="btn" href="/logistica_global/controllers/viajeController.php?accion=listar">⬅ Volver</a>

  <!-- Paso 1: elegir ruta -->
  <form method="GET" action="/logistica_global/controllers/viajeController.php">
    <input type="hidden" name="accion" value="crear" />
    <label>Ruta:</label>
    <select name="id_ruta" onchange="this.form.submit()">
      <option value="">-- Seleccionar --</option>
      <?php foreach ($rutas as $r): ?>
        <option value="<?= $r['id_ruta'] ?>" <?= ($id_ruta ?? 0) == $r['id_ruta'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($r['nombre_ruta']) ?> (<?= htmlspecialchars($r['origen'].' → '.$r['destino']) ?>)
        </option>
      <?php endforeach; ?>
    </select>
    <noscript><button type="submit">Filtrar</button></noscript>
  </form>

  <hr/>

  <!-- Paso 2: datos del viaje + órdenes filtradas -->
  <form method="POST" action="/logistica_global/controllers/viajeController.php?accion=crear">
    <input type="hidden" name="id_ruta" value="<?= (int)($id_ruta ?? 0) ?>" />

    <div class="grid-2">
      <div>
        <label>Conductor:</label>
        <select name="id_conductor" required>
          <option value="">-- Seleccionar --</option>
          <?php foreach ($conductores as $c): ?>
            <option value="<?= $c['id_conductor'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Vehículo:</label>
        <select name="id_vehiculo" required>
          <option value="">-- Seleccionar --</option>
          <?php foreach ($vehiculos as $v): ?>
            <option value="<?= $v['id_vehiculo'] ?>"><?= htmlspecialchars($v['placa']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label>Fecha Inicio:</label>
        <input type="datetime-local" name="fecha_inicio">
      </div>
      <div>
        <label>Fecha Fin:</label>
        <input type="datetime-local" name="fecha_fin">
      </div>
    </div>

    <div class="grid-2">
      <div>
        <label>Kilómetros Recorridos:</label>
        <input type="number" step="0.01" name="kilometros_recorridos">
      </div>
      <div>
        <label>Combustible Usado (L):</label>
        <input type="number" step="0.01" name="combustible_usado_litros">
      </div>
    </div>

    <label>Observaciones:</label>
    <textarea name="observaciones"></textarea>

    <label>Estado:</label>
    <select name="estado">
      <option value="Pendiente">Pendiente</option>
      <option value="En_Ruta">En ruta</option>
      <option value="Finalizado">Finalizado</option>
      <option value="Cancelado">Cancelado</option>
    </select>

    <hr/>

    <h3>📦 Órdenes coincidentes con la ruta seleccionada</h3>
    <?php if (empty($id_ruta)): ?>
      <p>Selecciona primero una ruta para ver las órdenes disponibles.</p>
    <?php else: ?>
      <?php if (empty($ordenes)): ?>
        <p>No hay órdenes “Programada” con ese origen/destino.</p>
      <?php else: ?>
        <p>Selecciona una o más:</p>
        <select name="ordenes[]" multiple size="8" style="min-width:420px;">
          <?php foreach ($ordenes as $o): ?>
            <option value="<?= $o['id_orden'] ?>">
              #<?= $o['id_orden'] ?> — <?= htmlspecialchars($o['direccion_origen'].' → '.$o['direccion_destino']) ?> — <?= number_format((float)$o['peso_estimado_kg'],2) ?> kg
            </option>
          <?php endforeach; ?>
        </select>
        <p style="font-size:.9rem;color:#666;">(Ctrl/⌘ para seleccionar varias)</p>
      <?php endif; ?>
    <?php endif; ?>

    <button type="submit" class="btn primary" <?= empty($id_ruta) ? 'disabled' : '' ?>>💾 Guardar</button>
  </form>
</div>
