<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/viajeController.php?accion=editar&id=<?= $viaje['id_viaje'] ?>">

    <!-- ===================================================== -->
    <!-- 📦 Órdenes -->
    <!-- ===================================================== -->
    <label>Órdenes asociadas:</label>
    <select name="ordenes[]" multiple size="6" style="min-width: 350px;">
      <?php foreach ($ordenes as $o): ?>
        <option 
          value="<?= $o['id_orden'] ?>" 
          <?= (in_array($o['id_orden'], $viaje['ordenes_asociadas'] ?? [])) ? 'selected' : '' ?>
        >
          #<?= $o['id_orden'] ?> — <?= htmlspecialchars($o['direccion_origen'].' → '.$o['direccion_destino']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <p style="font-size:.9rem;color:#666;">(Ctrl/⌘ para seleccionar varias)</p>

    <!-- ===================================================== -->
    <!-- 🧑‍✈️ Conductor -->
    <!-- ===================================================== -->
    <label>Conductor:</label>
    <select name="id_conductor" required>
      <option value="">-- Seleccionar --</option>
      <?php foreach ($conductores as $c): ?>
        <option value="<?= $c['id_conductor'] ?>" <?= ($viaje['id_conductor']==$c['id_conductor'])?'selected':'' ?>>
          <?= htmlspecialchars($c['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- ===================================================== -->
    <!-- 🚗 Vehículo -->
    <!-- ===================================================== -->
    <label>Vehículo:</label>
    <select name="id_vehiculo" required>
      <option value="">-- Seleccionar --</option>
      <?php foreach ($vehiculos as $v): ?>
        <option value="<?= $v['id_vehiculo'] ?>" <?= ($viaje['id_vehiculo']==$v['id_vehiculo'])?'selected':'' ?>>
          <?= htmlspecialchars($v['placa'].' — '.$v['marca'].' '.$v['modelo']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- ===================================================== -->
    <!-- 🗺️ Ruta -->
    <!-- ===================================================== -->
    <label>Ruta:</label>
    <select name="id_ruta">
      <option value="">-- Ninguna --</option>
      <?php foreach ($rutas as $r): ?>
        <option value="<?= $r['id_ruta'] ?>" <?= ($viaje['id_ruta']==$r['id_ruta'])?'selected':'' ?>>
          <?= htmlspecialchars($r['nombre_ruta'].' ('.$r['origen'].' → '.$r['destino'].')') ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- ===================================================== -->
    <!-- 📅 Fechas -->
    <!-- ===================================================== -->
    <div class="grid-2">
      <label>Fecha Inicio:
        <input type="datetime-local" name="fecha_inicio" 
          value="<?= !empty($viaje['fecha_inicio']) ? date('Y-m-d\TH:i', strtotime($viaje['fecha_inicio'])) : '' ?>" />
      </label>
      <label>Fecha Fin:
        <input type="datetime-local" name="fecha_fin" 
          value="<?= !empty($viaje['fecha_fin']) ? date('Y-m-d\TH:i', strtotime($viaje['fecha_fin'])) : '' ?>" />
      </label>
    </div>

    <!-- ===================================================== -->
    <!-- ⚙️ Datos de operación -->
    <!-- ===================================================== -->
    <div class="grid-2">
      <label>Kilómetros Recorridos:
        <input type="number" step="0.01" name="kilometros_recorridos" value="<?= htmlspecialchars($viaje['kilometros_recorridos'] ?? '') ?>" />
      </label>
      <label>Combustible Usado (L):
        <input type="number" step="0.01" name="combustible_usado_litros" value="<?= htmlspecialchars($viaje['combustible_usado_litros'] ?? '') ?>" />
      </label>
    </div>

    <!-- ===================================================== -->
    <!-- 📝 Observaciones -->
    <!-- ===================================================== -->
    <label>Observaciones:</label>
    <textarea name="observaciones" rows="3"><?= htmlspecialchars($viaje['observaciones'] ?? '') ?></textarea>

    <!-- ===================================================== -->
    <!-- 🔄 Estado -->
    <!-- ===================================================== -->
    <label>Estado:</label>
    <select name="estado">
      <?php foreach (['Pendiente','En_Ruta','Finalizado','Cancelado'] as $e): ?>
        <option value="<?= $e ?>" <?= ($viaje['estado']==$e)?'selected':'' ?>><?= $e ?></option>
      <?php endforeach; ?>
    </select>

    <div class="form-actions">
      <a href="/logistica_global/controllers/viajeController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>

<style>
.container { max-width: 900px; margin: 0 auto; }
label { display: block; margin-top: 10px; font-weight: 600; }
input, select, textarea {
  width: 100%; padding: 8px; margin-top: 4px;
  border: 1px solid #ccc; border-radius: 6px;
}
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.btn-primary, .btn-secondary {
  text-decoration: none; border: none; border-radius: 6px; padding: 8px 12px; color: white;
}
.btn-primary { background: #0d6efd; }
.btn-secondary { background: #6c757d; }
.btn-primary:hover { background: #0b5ed7; }
.btn-secondary:hover { background: #5a6268; }
</style>
