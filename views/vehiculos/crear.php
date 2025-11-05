<div class="container">
  <h1>🚗 Registrar Vehículo</h1>

  <a href="/logistica_global/controllers/vehiculoController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/vehiculoController.php?accion=crear">
    <label>Tipo de Camión:</label>
    <select name="id_tipo_camion">
      <option value="">-- Seleccione --</option>
      <?php foreach ($tipos as $t): ?>
        <option value="<?= $t['id_tipo_camion'] ?>"><?= htmlspecialchars($t['nombre_tipo']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Placa:</label>
    <input type="text" name="placa" required>

    <label>Marca:</label>
    <input type="text" name="marca">

    <label>Modelo:</label>
    <input type="text" name="modelo">

    <label>Año:</label>
    <input type="number" name="anio" min="1990" max="2050">

    <label>Capacidad (kg):</label>
    <input type="number" name="capacidad_kg" step="0.01">

    <label>Fecha de adquisición:</label>
    <input type="date" name="fecha_adquisicion">

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo">Activo</option>
      <option value="Inactivo">Inactivo</option>
      <option value="Mantenimiento">Mantenimiento</option>
    </select>

    <button type="submit">💾 Guardar</button>
  </form>
</div>
