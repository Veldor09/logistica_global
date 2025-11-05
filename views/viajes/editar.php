<div class="container">
  <h1><i class="fa-solid fa-pen-to-square"></i> Editar Viaje</h1>

  <a href="/logistica_global/controllers/viajeController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/viajeController.php?accion=editar&id=<?= $viaje['id_viaje'] ?>">
    <label>Conductor</label>
    <select name="id_conductor">
      <?php foreach ($conductores as $c): ?>
        <?php $sel = ($c['id_conductor'] == $viaje['id_conductor']) ? 'selected' : ''; ?>
        <option value="<?= $c['id_conductor'] ?>" <?= $sel ?>>
          <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido1']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Vehículo</label>
    <select name="id_vehiculo">
      <?php foreach ($vehiculos as $v): ?>
        <?php $sel = ($v['id_vehiculo'] == $viaje['id_vehiculo']) ? 'selected' : ''; ?>
        <option value="<?= $v['id_vehiculo'] ?>" <?= $sel ?>><?= htmlspecialchars($v['placa']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Ruta</label>
    <select name="id_ruta">
      <option value="">-- Sin ruta --</option>
      <?php foreach ($rutas as $r): ?>
        <?php $sel = ($r['id_ruta'] == $viaje['id_ruta']) ? 'selected' : ''; ?>
        <option value="<?= $r['id_ruta'] ?>" <?= $sel ?>><?= htmlspecialchars($r['nombre_ruta']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Fecha inicio</label>
    <input type="date" name="fecha_inicio" value="<?= $viaje['fecha_inicio'] ? date_format($viaje['fecha_inicio'], 'Y-m-d') : '' ?>">

    <label>Fecha fin</label>
    <input type="date" name="fecha_fin" value="<?= $viaje['fecha_fin'] ? date_format($viaje['fecha_fin'], 'Y-m-d') : '' ?>">

    <label>Kilómetros recorridos</label>
    <input type="number" step="0.01" name="kilometros_recorridos" value="<?= $viaje['kilometros_recorridos'] ?? '' ?>">

    <label>Combustible usado (L)</label>
    <input type="number" step="0.01" name="combustible_usado_litros" value="<?= $viaje['combustible_usado_litros'] ?? '' ?>">

    <label>Observaciones</label>
    <input type="text" name="observaciones" value="<?= htmlspecialchars($viaje['observaciones'] ?? '') ?>">

    <label>Estado</label>
    <select name="estado">
      <?php
        $estados = ['Pendiente', 'En Ruta', 'Finalizado', 'Cancelado'];
        foreach ($estados as $e):
          $sel = ($e === $viaje['estado']) ? 'selected' : '';
          echo "<option value='$e' $sel>$e</option>";
        endforeach;
      ?>
    </select>

    <button type="submit"><i class="fa-solid fa-save"></i> Actualizar Viaje</button>
  </form>
</div>
