<div class="container">
  <h1><i class="fa-solid fa-file-signature"></i> Registrar Nueva Solicitud</h1>

  <!-- 🔔 Alertas -->
  <?php if (isset($_GET['error'])): ?>
    <div class="alert danger">❌ Error al registrar la solicitud. Verifique los datos ingresados.</div>
  <?php elseif (isset($_GET['no_cliente'])): ?>
    <div class="alert danger">⚠️ El cliente remitente o destinatario no existe en el sistema.</div>
  <?php endif; ?>

  <!-- 🔙 Volver -->
  <a href="/logistica_global/controllers/solicitudController.php" class="btn">
    ⬅️ Volver a la lista
  </a>

  <form method="POST" action="/logistica_global/controllers/solicitudController.php?accion=crear">

    <!-- Cliente Remitente -->
    <div>
      <label for="id_cliente" class="block font-semibold mb-1">Cliente Remitente:</label>
      <select name="id_cliente" id="id_cliente" class="input-field" required>
        <option value="">-- Seleccione el remitente --</option>
        <?php foreach ($clientes as $c): ?>
          <option value="<?= $c['id_cliente'] ?>">
            <?= htmlspecialchars($c['correo']) ?> (<?= htmlspecialchars($c['tipo_identificacion']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Cliente Destinatario -->
    <div>
      <label for="id_destinatario" class="block font-semibold mb-1">Cliente Destinatario:</label>
      <select name="id_destinatario" id="id_destinatario" class="input-field">
        <option value="">-- Seleccione el destinatario (opcional) --</option>
        <?php foreach ($clientes as $c): ?>
          <option value="<?= $c['id_cliente'] ?>">
            <?= htmlspecialchars($c['correo']) ?> (<?= htmlspecialchars($c['tipo_identificacion']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Tipo de servicio -->
    <label for="tipo_servicio">Tipo de Servicio</label>
    <input type="text" name="tipo_servicio" id="tipo_servicio" required placeholder="Ej. Transporte de carga pesada">

    <!-- Descripción -->
    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion" placeholder="Detalles breves del servicio">

    <!-- Origen -->
    <label for="origen">Origen</label>
    <input type="text" name="origen" id="origen" placeholder="Ej. Alajuela, centro" required>

    <!-- Destino -->
    <label for="destino_general">Destino</label>
    <input type="text" name="destino_general" id="destino_general" placeholder="Ej. San José, Pavas" required>

    <!-- Prioridad -->
    <label for="prioridad">Prioridad</label>
    <select name="prioridad" id="prioridad">
      <option value="Normal">Normal</option>
      <option value="Alta">Alta</option>
      <option value="Urgente">Urgente</option>
    </select>

    <!-- Estado -->
    <label for="estado">Estado</label>
    <select name="estado" id="estado">
      <option value="Pendiente">Pendiente</option>
      <option value="En Proceso">En Proceso</option>
      <option value="Completada">Completada</option>
      <option value="Cancelada">Cancelada</option>
    </select>

    <!-- Observaciones -->
    <label for="observaciones">Observaciones</label>
    <input type="text" name="observaciones" id="observaciones" placeholder="Notas adicionales (opcional)">

    <!-- Botón -->
    <button type="submit" class="btn-primary" style="margin-top:10px;">
      <i class="fa-solid fa-save"></i> Guardar Solicitud
    </button>
  </form>
</div>

<!-- 🎨 Estilos -->
<style>
  .container h1 {
    color:#134074;
    margin-bottom:15px;
  }
  label {
    display:block;
    margin-top:10px;
    font-weight:bold;
  }
  .input-field, input[type="text"], select {
    width:100%;
    padding:8px;
    margin-top:4px;
    border:1px solid #ccc;
    border-radius:6px;
  }
  .btn, .btn-primary {
    background:#134074;
    color:white;
    padding:8px 14px;
    text-decoration:none;
    border:none;
    border-radius:6px;
    cursor:pointer;
    display:inline-block;
  }
  .btn:hover, .btn-primary:hover {
    background:#0e2a50;
  }
  .alert {
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
  }
  .alert.danger {
    background:#f8d7da;
    color:#721c24;
  }
</style>
