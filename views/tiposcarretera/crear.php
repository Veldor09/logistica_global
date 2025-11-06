<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="POST" action="/logistica_global/controllers/tipoCarreteraController.php?accion=crear">
    <label>Nombre:
      <input type="text" name="nombre" required value="<?= htmlspecialchars($old['nombre'] ?? '') ?>">
    </label>
    <?php if (!empty($errores['nombre'])): ?><p class="error"><?= $errores['nombre'] ?></p><?php endif; ?>

    <label>Descripción:
      <textarea name="descripcion" rows="3"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/tipoCarreteraController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Guardar</button>
    </div>
  </form>
</div>
