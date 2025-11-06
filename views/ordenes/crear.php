<div class="container">
  <h1>➕ Registrar Orden</h1>

  <form method="POST" action="/logistica_global/controllers/ordenController.php?accion=crear">
    <label>ID Solicitud:</label>
    <select name="id_solicitud" id="id_solicitud" required>
      <option value="">-- Seleccionar solicitud --</option>
      <?php foreach ($solicitudes as $s): ?>
        <option 
          value="<?= $s['id_solicitud'] ?>"
          data-origen="<?= htmlspecialchars($s['origen']) ?>"
          data-destino="<?= htmlspecialchars($s['destino_general']) ?>"
        >
          #<?= $s['id_solicitud'] ?> - <?= htmlspecialchars($s['origen']) ?> → <?= htmlspecialchars($s['destino_general']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Dirección Origen:</label>
    <input type="text" name="direccion_origen" id="direccion_origen" placeholder="Ej: Bodega central" required>

    <label>Dirección Destino:</label>
    <input type="text" name="direccion_destino" id="direccion_destino" placeholder="Ej: Alajuela, centro" required>

    <label>Peso estimado (kg):</label>
    <input type="number" step="0.01" name="peso_estimado_kg" required placeholder="Ej: 10.5">

    <label>Fecha de carga:</label>
    <input type="date" name="fecha_carga">

    <label>Fecha estimada de entrega:</label>
    <input type="date" name="fecha_entrega_estimada">

    <label>Observaciones:</label>
    <textarea name="observaciones" placeholder="Comentarios adicionales..."></textarea>

    <button type="submit" class="btn success">💾 Guardar</button>
    <a href="/logistica_global/controllers/ordenController.php?accion=listar" class="btn secondary">⬅️ Cancelar</a>
  </form>
</div>

<script>
// 🔄 Autocompletar origen/destino según solicitud seleccionada
document.getElementById('id_solicitud').addEventListener('change', function () {
  const selected = this.options[this.selectedIndex];
  const origen = selected.getAttribute('data-origen') || '';
  const destino = selected.getAttribute('data-destino') || '';

  document.getElementById('direccion_origen').value = origen;
  document.getElementById('direccion_destino').value = destino;
});
</script>
