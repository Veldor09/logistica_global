<!-- ============================================================
📄 views/reportes/generar.php
============================================================ -->
<div class="container">
  <h1>📊 <?= htmlspecialchars($titulo) ?></h1>

  <?php if (empty($viajes)): ?>
    <div class="alert">
      ⚠️ No hay viajes registrados en el sistema.  
      Crea al menos un viaje antes de generar reportes de eficiencia.
    </div>
  <?php else: ?>
    <form method="POST" action="/logistica_global/controllers/reporteEficienciaController.php?accion=generar">
      <div class="form-group">
        <label for="id_viaje">Selecciona el Viaje:</label>
        <select name="id_viaje" id="id_viaje" required>
          <option value="">-- Seleccionar viaje --</option>
          <?php foreach ($viajes as $v): ?>
            <?php
              $id = htmlspecialchars($v['id_viaje']);
              $vehiculo = htmlspecialchars($v['vehiculo'] ?? 'Sin vehículo');
              $ruta = htmlspecialchars($v['nombre_ruta'] ?? '-');
              $conductor = htmlspecialchars($v['conductor'] ?? 'No asignado');
              $fecha = htmlspecialchars($v['fecha_inicio'] ?? '');
              $km = htmlspecialchars($v['kilometros_recorridos'] ?? '0');
            ?>
            <option value="<?= $id ?>">
              #<?= $id ?> — <?= $vehiculo ?> — <?= $ruta ?> — <?= $conductor ?> — <?= $km ?> km — <?= $fecha ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (!empty($errores['id_viaje'])): ?>
          <p class="error"><?= htmlspecialchars($errores['id_viaje']) ?></p>
        <?php endif; ?>
      </div>

      <div class="form-actions">
        <a href="/logistica_global/controllers/reporteEficienciaController.php?accion=listar" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Generar</button>
      </div>
    </form>
  <?php endif; ?>
</div>

<style>
.container {
  max-width: 750px;
  margin: 0 auto;
  background: #fff;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}
h1 {
  text-align: center;
  color: #003366;
  margin-bottom: 1.5rem;
  font-weight: 700;
}
.alert {
  background: #fff3cd;
  color: #664d03;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #ffeeba;
  text-align: center;
  margin-bottom: 1rem;
}
.form-group {
  margin-bottom: 1.5rem;
  display: flex;
  flex-direction: column;
}
label {
  font-weight: bold;
  margin-bottom: .5rem;
}
select {
  padding: .6rem;
  font-size: 1rem;
  border-radius: 8px;
  border: 1px solid #ccc;
}
.error {
  color: red;
  margin-top: .5rem;
}
.form-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
}
.btn-primary {
  background-color: #0d6efd;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
  border: none;
  cursor: pointer;
}
.btn-primary:hover {
  background-color: #0b5ed7;
}
.btn-secondary {
  background-color: #6c757d;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
}
.btn-secondary:hover {
  background-color: #5a6268;
}
</style>
