<div class="container">
  <h1><i class="fa-solid fa-route"></i> Registrar Nuevo Viaje</h1>

  <a href="/logistica_global/controllers/viajeController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/viajeController.php?accion=crear">
    
    <!-- Orden -->
    <label for="id_orden">Orden</label>
    <select name="id_orden" required>
      <option value="">-- Seleccione Orden --</option>
      <?php foreach ($ordenes as $o): ?>
        <option value="<?= $o['id_orden'] ?>">
          #<?= $o['id_orden'] ?> - <?= htmlspecialchars($o['tipo_servicio']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- Conductor -->
    <label for="id_conductor">Conductor</label>
    <select name="id_conductor" required>
      <option value="">-- Seleccione Conductor --</option>
      <?php foreach ($conductores as $c): ?>
        <option value="<?= $c['id_conductor'] ?>">
          <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido1']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- Vehículo -->
    <label for="id_vehiculo">Vehículo</label>
    <select name="id_vehiculo" required>
      <option value="">-- Seleccione Vehículo --</option>
      <?php foreach ($vehiculos as $v): ?>
        <option value="<?= $v['id_vehiculo'] ?>">
          <?= htmlspecialchars($v['placa'] . ' - ' . $v['marca']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- Ruta -->
    <label for="id_ruta">Ruta</label>
    <select name="id_ruta">
      <option value="">-- Seleccione Ruta --</option>
      <?php foreach ($rutas as $r): ?>
        <option value="<?= $r['id_ruta'] ?>">
          <?= htmlspecialchars($r['nombre_ruta']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="fecha_inicio">Fecha de Inicio</label>
    <input type="datetime-local" name="fecha_inicio">

    <label for="fecha_fin">Fecha de Fin</label>
    <input type="datetime-local" name="fecha_fin">

    <label for="kilometros_recorridos">Kilómetros Recorridos</label>
    <input type="number" step="0.01" name="kilometros_recorridos">

    <label for="combustible_usado_litros">Combustible Usado (L)</label>
    <input type="number" step="0.01" name="combustible_usado_litros">

    <label for="observaciones">Observaciones</label>
    <input type="text" name="observaciones" placeholder="Notas adicionales (opcional)">

    <label for="estado">Estado</label>
    <select name="estado">
      <option value="Pendiente">Pendiente</option>
      <option value="En_Ruta">En Ruta</option>
      <option value="Finalizado">Finalizado</option>
      <option value="Cancelado">Cancelado</option>
    </select>

    <button type="submit"><i class="fa-solid fa-save"></i> Guardar Viaje</button>
  </form>
</div>
