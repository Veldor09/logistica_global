<div class="container">
  <h1>✏️ Editar Vehículo</h1>

  <a href="/logistica_global/controllers/vehiculoController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/vehiculoController.php?accion=editar&id=<?= $vehiculo['id_vehiculo'] ?>">
    <label>Tipo de Camión:</label>
    <select name="id_tipo_camion">
      <option value="">-- Seleccione --</option>
      <?php foreach ($tipos as $t): ?>
        <option value="<?= $t['id_tipo_camion'] ?>" <?= $vehiculo['id_tipo_camion'] == $t['id_tipo_camion'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($t['nombre_tipo']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Placa:</label>
    <input type="text" name="placa" value="<?= htmlspecialchars($vehiculo['placa']) ?>" required>

    <label>Marca:</label>
    <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca']) ?>">

    <label>Modelo:</label>
    <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>">

    <label>Año:</label>
    <input type="number" name="anio" value="<?= htmlspecialchars($vehiculo['anio']) ?>">

    <label>Capacidad (kg):</label>
    <input type="number" name="capacidad_kg" step="0.01" value="<?= htmlspecialchars($vehiculo['capacidad_kg']) ?>">

    <label>Fecha de adquisición:</label>
    <input type="date" name="fecha_adquisicion" 
      value="<?= $vehiculo['fecha_adquisicion'] ? $vehiculo['fecha_adquisicion']->format('Y-m-d') : '' ?>">

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo" <?= $vehiculo['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
      <option value="Inactivo" <?= $vehiculo['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
      <option value="Mantenimiento" <?= $vehiculo['estado'] === 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
    </select>

    <button type="submit">💾 Actualizar</button>
  </form>
</div>
