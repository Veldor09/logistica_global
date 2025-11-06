<?php
// ============================================================
// 📄 views/vehiculos/editar.php
// ============================================================
?>

<div class="container">
  <h1>✏️ Editar Vehículo</h1>

  <a href="/logistica_global/controllers/vehiculoController.php?accion=listar" class="btn secondary">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/vehiculoController.php?accion=editar&id=<?= $vehiculo['id_vehiculo'] ?>">
    <div class="form-grid">
      <div>
        <label>Tipo de Camión:</label>
        <select name="id_tipo_camion" id="id_tipo_camion">
          <option value="">-- Seleccione --</option>
          <?php foreach ($tipos as $t): ?>
            <option 
              value="<?= $t['id_tipo_camion'] ?>"
              data-nombre="<?= htmlspecialchars($t['nombre_tipo']) ?>"
              <?= $vehiculo['id_tipo_camion'] == $t['id_tipo_camion'] ? 'selected' : '' ?>
            >
              <?= htmlspecialchars($t['nombre_tipo']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Placa:</label>
        <input type="text" name="placa" value="<?= htmlspecialchars($vehiculo['placa']) ?>" required>
      </div>

      <div>
        <label>Marca:</label>
        <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca'] ?? '') ?>">
      </div>

      <div>
        <label>Modelo:</label>
        <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo'] ?? '') ?>">
      </div>

      <div>
        <label>Año:</label>
        <input type="number" name="anio" value="<?= htmlspecialchars($vehiculo['anio'] ?? '') ?>">
      </div>

      <div>
        <label>Capacidad (kg):</label>
        <input type="number" name="capacidad_kg" id="capacidad_kg" step="0.01" 
               value="<?= htmlspecialchars($vehiculo['capacidad_kg'] ?? '') ?>" required>
      </div>

      <div>
        <label>Fecha de adquisición:</label>
        <input type="date" name="fecha_adquisicion"
          value="<?= isset($vehiculo['fecha_adquisicion']) ? htmlspecialchars($vehiculo['fecha_adquisicion']) : '' ?>">
      </div>

      <div>
        <label>Estado:</label>
        <select name="estado">
          <option value="Activo" <?= $vehiculo['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
          <option value="Inactivo" <?= $vehiculo['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
          <option value="Mantenimiento" <?= $vehiculo['estado'] === 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
        </select>
      </div>
    </div>

    <button type="submit" class="btn success">💾 Actualizar</button>
  </form>
</div>

<script>
const capacidadesPorTipo = {
  'Camión Liviano': 3500,
  'Camión Mediano': 7000,
  'Camión Pesado': 12000,
  'Camión Plataforma': 15000,
  'Camión Cisterna': 10000,
  'Camión Refrigerado': 9000
};

document.getElementById('id_tipo_camion').addEventListener('change', function() {
  const tipoNombre = this.options[this.selectedIndex].getAttribute('data-nombre');
  const inputCapacidad = document.getElementById('capacidad_kg');
  if (tipoNombre && capacidadesPorTipo[tipoNombre]) {
    inputCapacidad.value = capacidadesPorTipo[tipoNombre];
  } else {
    inputCapacidad.value = '';
  }
});
</script>

<style>
.container {
  background: white;
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  margin-top: 2rem;
}

h1 {
  text-align: center;
  margin-bottom: 1.5rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

label {
  font-weight: bold;
}

input, select {
  width: 100%;
  padding: 0.4rem;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.btn {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  text-decoration: none;
  color: white;
  background: #007bff;
  margin-top: 1rem;
}

.btn.secondary {
  background: #6c757d;
}

.btn.success {
  background: #28a745;
}

.btn:hover {
  opacity: 0.85;
}
</style>
