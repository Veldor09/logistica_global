<div class="container">
  <h1>✏️ Editar Conductor</h1>
  <a href="/logistica_global/controllers/conductorController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/conductorController.php?accion=editar&id=<?= $conductor['id_conductor'] ?>">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($conductor['nombre']) ?>" required>

    <label>Primer Apellido:</label>
    <input type="text" name="apellido1" value="<?= htmlspecialchars($conductor['apellido1']) ?>" required>

    <label>Segundo Apellido:</label>
    <input type="text" name="apellido2" value="<?= htmlspecialchars($conductor['apellido2']) ?>">

    <label>Cédula:</label>
    <input type="text" name="cedula" value="<?= htmlspecialchars($conductor['cedula']) ?>" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($conductor['telefono']) ?>">

    <label>Correo:</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($conductor['correo']) ?>">

    <label>Dirección:</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($conductor['direccion']) ?>">

    <label>Fecha de Ingreso:</label>
    <input type="date" name="fecha_ingreso" value="<?= $conductor['fecha_ingreso'] ? $conductor['fecha_ingreso']->format('Y-m-d') : '' ?>">

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo" <?= $conductor['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
      <option value="Inactivo" <?= $conductor['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
    </select>

    <button type="submit" class="btn primary">💾 Actualizar</button>
  </form>
</div>
