<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/eventoController.php?accion=editar&id=<?= (int)$evento['id_evento'] ?>">
    <label>Viaje:
      <select name="id_viaje">
        <?php foreach ($viajes as $v): ?>
          <option value="<?= (int)$v['id_viaje'] ?>" <?= ($evento['id_viaje'] == $v['id_viaje']) ? 'selected' : '' ?>>
            Viaje #<?= $v['id_viaje'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Tipo de Evento:
      <select name="id_tipo_evento">
        <?php foreach ($tipos as $t): ?>
          <option value="<?= (int)$t['id_tipo_evento'] ?>" <?= ($evento['id_tipo_evento'] == $t['id_tipo_evento']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Fecha:
      <input type="datetime-local" name="fecha" value="<?= date('Y-m-d\TH:i', strtotime($evento['fecha'])) ?>" />
    </label>

    <label>Ubicación:
      <input type="text" name="ubicacion" value="<?= htmlspecialchars($evento['ubicacion'] ?? '') ?>" />
    </label>

    <label>Observaciones:
      <textarea name="observaciones"><?= htmlspecialchars($evento['observaciones'] ?? '') ?></textarea>
    </label>

    <label>Estado:
      <select name="estado">
        <?php foreach (['Registrado','Completado','Cancelado'] as $estado): ?>
          <option value="<?= $estado ?>" <?= ($evento['estado']==$estado)?'selected':'' ?>><?= $estado ?></option>
        <?php endforeach; ?>
      </select>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/eventoController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>
