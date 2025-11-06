<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/tipoEventoController.php?accion=crear">

    <!-- Campo: Nombre -->
    <label>Nombre:
      <input 
        type="text" 
        name="nombre" 
        value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" 
        required
      >
    </label>
    <?php if (!empty($errores['nombre'])): ?>
      <p class="error"><?= htmlspecialchars($errores['nombre']) ?></p>
    <?php endif; ?>

    <!-- Campo: Descripción -->
    <label>Descripción:
      <textarea 
        name="descripcion" 
        rows="3"
      ><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
    </label>

    <!-- Campo: Estado -->
    <?php $estado = $old['estado'] ?? 'Activo'; ?>
    <label>Estado:
      <select name="estado">
        <option value="Activo" <?= ($estado === 'Activo') ? 'selected' : '' ?>>Activo</option>
        <option value="Inactivo" <?= ($estado === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
      </select>
    </label>

    <!-- Botones de acción -->
    <div class="form-actions" style="margin-top:15px;">
      <a href="/logistica_global/controllers/tipoEventoController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Guardar</button>
    </div>
  </form>
</div>
