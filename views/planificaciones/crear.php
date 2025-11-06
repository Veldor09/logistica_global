<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="POST" action="/logistica_global/controllers/planificacionController.php?accion=crear">
    <label>Carga:
      <select name="id_carga">
        <option value="">-- Seleccionar --</option>
        <?php foreach ($cargas as $c): ?>
          <option value="<?= $c['id_carga'] ?>">Carga #<?= $c['id_carga'] ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Vehículo:
      <select name="id_vehiculo">
        <option value="">-- Seleccionar --</option>
        <?php foreach ($vehiculos as $v): ?>
          <option value="<?= $v['id_vehiculo'] ?>"><?= htmlspecialchars($v['placa']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Distribución (%):
      <input type="number" step="0.01" name="distribucion_porcentaje" required>
    </label>

    <label>Balance del eje:
      <input type="text" name="balance_eje">
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/planificacionController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Guardar</button>
    </div>
  </form>
</div>
