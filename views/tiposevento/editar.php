<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <?php if (!empty($errores['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/tipoEventoController.php?accion=editar&id=<?= htmlspecialchars($tipo['id_tipo_evento']) ?>">
    
    <!-- Nombre -->
    <label>Nombre:
      <input 
        type="text" 
        name="nombre" 
        value="<?= htmlspecialchars($tipo['nombre'] ?? '') ?>" 
        required
      >
    </label>

    <!-- Descripción -->
    <label>Descripción:
      <textarea name="descripcion" rows="3"><?= htmlspecialchars($tipo['descripcion'] ?? '') ?></textarea>
    </label>

    <!-- Estado -->
    <?php $estado = $tipo['estado'] ?? 'Activo'; ?>
    <label>Estado:
      <select name="estado">
        <option value="Activo" <?= ($estado === 'Activo') ? 'selected' : '' ?>>Activo</option>
        <option value="Inactivo" <?= ($estado === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
      </select>
    </label>

    <!-- Acciones -->
    <div class="form-actions" style="margin-top:15px;">
      <a href="/logistica_global/controllers/tipoEventoController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>
