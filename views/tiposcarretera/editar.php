<div class="container">
  <h1><?= htmlspecialchars($titulo) ?></h1>

  <form method="POST" action="/logistica_global/controllers/tipoCarreteraController.php?accion=editar&id=<?= $tipo['id_tipo_carretera'] ?>">
    <label>Nombre:
      <input type="text" name="nombre" required value="<?= htmlspecialchars($tipo['nombre'] ?? '') ?>">
    </label>

    <label>Descripción:
      <textarea name="descripcion" rows="3"><?= htmlspecialchars($tipo['descripcion'] ?? '') ?></textarea>
    </label>

    <div class="form-actions">
      <a href="/logistica_global/controllers/tipoCarreteraController.php?accion=listar" class="btn-secondary">Cancelar</a>
      <button type="submit" class="btn-primary">Actualizar</button>
    </div>
  </form>
</div>
