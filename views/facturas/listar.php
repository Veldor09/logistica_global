<div class="container">
  <h1>💰 Facturas Emitidas</h1>

  <div class="actions">
    <a href="/logistica_global/controllers/facturaController.php?accion=crear" class="btn btn-primary">
      ➕ Nueva Factura
    </a>
  </div>

  <?php if (empty($facturas)): ?>
    <p class="alert">No hay facturas registradas actualmente.</p>
  <?php else: ?>
    <div class="table-wrapper">
      <table class="table-facturas">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Orden</th>
            <th>Fecha</th>
            <th>Subtotal</th>
            <th>Impuesto</th>
            <th>Total</th>
            <th>Método</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($facturas as $f): ?>
          <tr>
            <td>#<?= $f['id_factura'] ?></td>
            <td><?= htmlspecialchars($f['cliente']) ?></td>
            <td>#<?= $f['id_orden'] ?></td>
            <td><?= is_object($f['fecha_emision']) ? $f['fecha_emision']->format('Y-m-d') : htmlspecialchars($f['fecha_emision']) ?></td>
            <td>₡<?= number_format($f['subtotal'], 2) ?></td>
            <td>₡<?= number_format($f['impuesto'], 2) ?></td>
            <td><strong>₡<?= number_format($f['total'], 2) ?></strong></td>
            <td><?= htmlspecialchars($f['metodo_pago']) ?></td>
            <td>
              <?php
                $estado = htmlspecialchars($f['estado']);
                $color = match($estado) {
                  'Pagada'  => 'green',
                  'Anulada' => 'red',
                  default   => 'gray'
                };
              ?>
              <span class="estado <?= $color ?>"><?= $estado ?></span>
            </td>
            <td class="acciones">
              <a href="/logistica_global/controllers/facturaController.php?accion=pdf&id=<?= $f['id_factura'] ?>" title="Ver PDF">📄</a>
              <a href="/logistica_global/controllers/facturaController.php?accion=eliminar&id=<?= $f['id_factura'] ?>"
                 onclick="return confirm('¿Eliminar factura #<?= $f['id_factura'] ?>?')" title="Eliminar">🗑️</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- ============================================================
     🎨 ESTILOS BÁSICOS
============================================================ -->
<style>
.container {
  max-width: 1000px;
  margin: 20px auto;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h1 {
  text-align: center;
  color: #003366;
}
.actions {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px;
}
.btn {
  display: inline-block;
  padding: 8px 14px;
  border-radius: 6px;
  text-decoration: none;
  color: white;
  font-weight: bold;
}
.btn-primary {
  background-color: #007bff;
}
.btn-primary:hover {
  background-color: #0056b3;
}
.alert {
  background: #fffae6;
  padding: 10px;
  border: 1px solid #ffd966;
  border-radius: 6px;
  text-align: center;
}
.table-wrapper {
  overflow-x: auto;
}
.table-facturas {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
.table-facturas th {
  background-color: #f2f2f2;
  text-align: center;
  padding: 10px;
}
.table-facturas td {
  text-align: center;
  border-bottom: 1px solid #ddd;
  padding: 8px;
}
.table-facturas tr:hover {
  background-color: #f9f9f9;
}
.estado {
  padding: 4px 8px;
  border-radius: 6px;
  color: white;
  font-weight: bold;
}
.estado.green { background: #28a745; }
.estado.red { background: #dc3545; }
.estado.gray { background: #6c757d; }
.acciones a {
  margin: 0 4px;
  text-decoration: none;
  font-size: 18px;
}
.acciones a:hover {
  opacity: 0.7;
}
</style>
