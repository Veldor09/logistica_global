<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="POST" action="/logistica_global/controllers/planificacionController.php?accion=editar&id=<?= $plan['id_planificacion'] ?>">
    <label>Carga:
      <select name="id_carga">
        <?php foreach ($cargas as $c): ?>
          <option value="<?= $c['id_carga'] ?>" <?= ($plan['id_carga']==$c['id_carga'])?'selected':'' ?>>
            Carga #<?= $c['id_carga'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Vehículo:
      <select name="id_vehiculo">
        <?php foreach ($vehiculos as $v): ?>
          <option value="<?= $v['id_vehiculo'] ?>" <?= ($plan['id_vehiculo']==$v['id_vehiculo'])?'selected':'' ?>>
            <?= htmlspecialchars($v['placa']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Distribución (%):
      <input type="number" step="0.01" name="distribucion_porcentaje" value="<?= $plan['distribucion_porcentaje'] ?>">
    </label>

    <label>Balance del eje:
      <input type="text" name="balance_eje" value="<?= htmlspecialchars($plan['balance_eje'] ?? '') ?>">
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/planificacionController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>
