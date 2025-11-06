<div class="container">
  <h1><i class="fa-solid fa-file-signature"></i> Registrar Nueva Solicitud</h1>

  <!-- 🔔 Alertas -->
  <?php if (isset($_GET['error'])): ?>
    <div class="alert danger">❌ Error al registrar la solicitud. Verifique los datos ingresados.</div>
  <?php elseif (isset($_GET['no_cliente'])): ?>
    <div class="alert danger">⚠️ El cliente remitente o destinatario no existe en el sistema.</div>
  <?php elseif (isset($_GET['success'])): ?>
    <div class="alert success">✅ Solicitud registrada correctamente.</div>
  <?php endif; ?>

  <!-- 🔙 Volver -->
  <a href="/logistica_global/controllers/solicitudController.php" class="btn-secondary">
    ⬅️ Volver a la lista
  </a>

  <form method="POST" action="/logistica_global/controllers/solicitudController.php?accion=crear" class="form-solicitud">

    <!-- 🧍‍♂️ Cliente Remitente -->
    <label for="id_cliente">Cliente Remitente:</label>
    <select name="id_cliente" id="id_cliente" required>
      <option value="">-- Seleccione el remitente --</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?= htmlspecialchars($c['id_cliente']) ?>">
          <?= htmlspecialchars($c['correo']) ?> (<?= htmlspecialchars($c['tipo_identificacion']) ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- 🎯 Cliente Destinatario -->
    <label for="id_destinatario">Cliente Destinatario:</label>
    <select name="id_destinatario" id="id_destinatario">
      <option value="">-- Seleccione el destinatario (opcional) --</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?= htmlspecialchars($c['id_cliente']) ?>">
          <?= htmlspecialchars($c['correo']) ?> (<?= htmlspecialchars($c['tipo_identificacion']) ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- 🚛 Tipo de servicio -->
    <label for="tipo_servicio">Tipo de Servicio</label>
    <input type="text" name="tipo_servicio" id="tipo_servicio" required placeholder="Ej. Transporte de carga pesada">

    <!-- 📝 Descripción -->
    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion" placeholder="Detalles breves del servicio">

    <!-- 🗺️ Origen -->
    <label for="origen">Origen</label>
    <input type="text" name="origen" id="origen" placeholder="Ej. Alajuela, centro" required>

    <!-- 🏁 Destino -->
    <label for="destino_general">Destino</label>
    <input type="text" name="destino_general" id="destino_general" placeholder="Ej. San José, Pavas" required>

    <!-- 🚦 Prioridad -->
    <label for="prioridad">Prioridad</label>
    <select name="prioridad" id="prioridad">
      <option value="Normal">Normal</option>
      <option value="Alta">Alta</option>
      <option value="Urgente">Urgente</option>
    </select>

    <!-- 📋 Estado -->
    <label for="estado">Estado</label>
    <select name="estado" id="estado">
      <option value="Pendiente">Pendiente</option>
      <option value="En Proceso">En Proceso</option>
      <option value="Completada">Completada</option>
      <option value="Cancelada">Cancelada</option>
    </select>

    <!-- 🗒️ Observaciones -->
    <label for="observaciones">Observaciones</label>
    <input type="text" name="observaciones" id="observaciones" placeholder="Notas adicionales (opcional)">

    <!-- 💾 Botón -->
    <button type="submit" class="btn-primary" style="margin-top:15px;">
      <i class="fa-solid fa-save"></i> Guardar Solicitud
    </button>
  </form>
</div>

<!-- 🎨 Estilos coherentes -->
<style>
  .container {
    max-width: 800px;
    margin: auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  .container h1 {
    color: #134074;
    margin-bottom: 15px;
  }
  label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    color: #333;
  }
  select, input[type="text"] {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
  }
  .btn-primary {
    background: #134074;
    color: white;
    padding: 10px 16px;
    text-decoration: none;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }
  .btn-primary:hover {
    background: #0e2a50;
  }
  .btn-secondary {
    display: inline-block;
    background: transparent;
    color: #134074;
    border: 1px solid #134074;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    margin-bottom: 10px;
  }
  .btn-secondary:hover {
    background: #134074;
    color: white;
  }
  .alert {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-weight: 500;
  }
  .alert.danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #f5c6cb;
  }
  .alert.success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #c3e6cb;
  }
</style>
