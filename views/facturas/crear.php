<div class="container">
  <h1>🧾 Crear Factura</h1>

  <form method="POST" action="/logistica_global/controllers/facturaController.php?accion=crear" id="formFactura">

    <!-- SELECCIÓN DE ORDEN -->
    <label for="id_orden"><b>Orden:</b></label>
    <select name="id_orden" id="id_orden" required>
      <option value="">-- Seleccione una orden --</option>
      <?php foreach ($ordenes as $o): ?>
        <option value="<?= $o['id_orden'] ?>">
          #<?= htmlspecialchars($o['id_orden']) ?> –
          <?= htmlspecialchars($o['cliente'] ?? '-') ?>
          (<?= htmlspecialchars($o['origen'] ?? '-') ?> → <?= htmlspecialchars($o['destino'] ?? '-') ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- CAMPOS PRINCIPALES -->
    <label for="subtotal"><b>Subtotal:</b></label>
    <input type="number" step="0.01" name="subtotal" id="subtotal" placeholder="0.00" required>

    <label for="impuesto"><b>Impuesto:</b></label>
    <input type="number" step="0.01" name="impuesto" id="impuesto" placeholder="0.00" required>

    <label for="metodo_pago"><b>Método de Pago:</b></label>
    <input type="text" name="metodo_pago" id="metodo_pago" placeholder="Efectivo / Transferencia / SINPE">

    <label for="estado"><b>Estado:</b></label>
    <select name="estado" id="estado">
      <option value="Emitida">Emitida</option>
      <option value="Pagada">Pagada</option>
      <option value="Anulada">Anulada</option>
    </select>

    <hr>
    <h3>🧩 Detalles de la Factura</h3>
    <p>Agregue los conceptos y precios que formarán parte de la factura.</p>

    <!-- CONTENEDOR DE DETALLES -->
    <div id="detalles">
      <div class="detalle-item">
        <input type="text" name="detalle[0][concepto]" placeholder="Concepto (ej: Flete nacional)" required>
        <input type="number" name="detalle[0][cantidad]" placeholder="Cantidad" min="1" required>
        <input type="number" step="0.01" name="detalle[0][precio_unitario]" placeholder="Precio Unitario" required>
      </div>
    </div>

    <button type="button" id="agregarDetalle" class="btn-add">➕ Agregar otro detalle</button>
    <br><br>

    <button type="submit" class="btn-primary">💾 Guardar Factura</button>
    <a href="/logistica_global/controllers/facturaController.php" class="btn-secondary">↩️ Volver</a>
  </form>
</div>

<!-- ============================================================
     📜 SCRIPT PARA AGREGAR DETALLES DINÁMICOS
============================================================ -->
<script>
let detalleIndex = 1;
document.getElementById('agregarDetalle').addEventListener('click', () => {
  const contenedor = document.getElementById('detalles');
  const nuevo = document.createElement('div');
  nuevo.className = 'detalle-item';
  nuevo.innerHTML = `
    <input type="text" name="detalle[${detalleIndex}][concepto]" placeholder="Concepto" required>
    <input type="number" name="detalle[${detalleIndex}][cantidad]" placeholder="Cantidad" min="1" required>
    <input type="number" step="0.01" name="detalle[${detalleIndex}][precio_unitario]" placeholder="Precio Unitario" required>
    <button type="button" onclick="this.parentElement.remove()" class="btn-delete">🗑️</button>
  `;
  contenedor.appendChild(nuevo);
  detalleIndex++;
});
</script>

<!-- ============================================================
     🎨 ESTILO BÁSICO (usa tu CSS global)
============================================================ -->
<style>
.container {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h1, h3 {
  text-align: center;
  color: #003366;
}
label { display: block; margin-top: 10px; font-weight: bold; }
input, select {
  width: 100%; padding: 8px; margin-top: 4px;
  border: 1px solid #ccc; border-radius: 6px;
}
.detalle-item {
  display: flex; gap: 10px; align-items: center;
  margin-top: 8px;
}
.detalle-item input { flex: 1; }
.btn-add, .btn-primary, .btn-secondary, .btn-delete {
  display: inline-block;
  margin-top: 10px; padding: 10px 14px;
  border: none; border-radius: 6px; cursor: pointer;
  font-size: 14px;
}
.btn-add { background: #28a745; color: white; }
.btn-primary { background: #007bff; color: white; }
.btn-secondary { background: #6c757d; color: white; text-decoration: none; }
.btn-delete { background: #dc3545; color: white; }
.btn-add:hover, .btn-primary:hover, .btn-delete:hover {
  opacity: 0.9;
}
</style>
