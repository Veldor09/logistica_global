<div class="container">
  <h1><i class="fa-solid fa-pen-to-square"></i> Editar Solicitud de Transporte</h1>

  <!-- 🔔 Alertas -->
  <?php if (isset($_GET['error'])): ?>
    <div class="alert danger">❌ Error al actualizar la solicitud. Verifique los datos ingresados.</div>
  <?php elseif (isset($_GET['no_cliente'])): ?>
    <div class="alert danger">⚠️ El cliente destinatario no existe en el sistema.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert success">✅ Solicitud actualizada correctamente.</div>
  <?php endif; ?>

  <!-- 🔙 Volver -->
  <a href="/logistica_global/controllers/solicitudController.php" class="btn-secondary">
    ⬅️ Volver a la lista
  </a>

  <form method="POST" action="/logistica_global/controllers/solicitudController.php?accion=editar&id=<?= urlencode($solicitud['id_solicitud']) ?>" class="form-solicitud">

    <!-- 🧍‍♂️ Cliente Remitente -->
    <label for="id_cliente">Cliente Remitente</label>
    <select name="id_cliente" id="id_cliente" disabled>
      <?php foreach ($clientes as $c): ?>
        <?php $selected = ($c['correo'] === ($solicitud['correo_remitente'] ?? '')) ? 'selected' : ''; ?>
        <option value="<?= htmlspecialchars($c['id_cliente']) ?>" <?= $selected ?>>
          <?= htmlspecialchars($c['correo']) ?> (<?= htmlspecialchars($c['tipo_identificacion']) ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- 🎯 Cliente Destinatario -->
    <label for="id_destinatario">Cliente Destinatario</label>
    <select name="id_destinatario" id="id_destinatario">
      <option value="">-- Seleccione destinatario (opcional) --</option>
      <?php foreach ($clientes as $c): ?>
        <?php $selected = ($c['correo'] === ($solicitud['correo_destinatario'] ?? '')) ? 'selected' : ''; ?>
        <option value="<?= htmlspecialchars($c['id_cliente']) ?>" <?= $selected ?>>
          <?= htmlspecialchars($c['correo']) ?> (<?= htmlspecialchars($c['tipo_identificacion']) ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- 🚛 Tipo de Servicio -->
    <label for="tipo_servicio">Tipo de Servicio</label>
    <input 
      type="text" 
      name="tipo_servicio" 
      id="tipo_servicio"
      value="<?= htmlspecialchars($solicitud['tipo_servicio'] ?? '') ?>" 
      required
    >

    <!-- 📝 Descripción -->
    <label for="descripcion">Descripción</label>
    <input 
      type="text" 
      name="descripcion" 
      id="descripcion"
      value="<?= htmlspecialchars($solicitud['descripcion'] ?? '') ?>"
    >

    <!-- 🗺️ Origen -->
    <label for="origen">Origen</label>
    <input 
      type="text" 
      name="origen" 
      id="origen"
      value="<?= htmlspecialchars($solicitud['origen'] ?? '') ?>"
    >

    <!-- 🏁 Destino -->
    <label for="destino_general">Destino</label>
    <input 
      type="text" 
      name="destino_general" 
      id="destino_general"
      value="<?= htmlspecialchars($solicitud['destino_general'] ?? '') ?>"
    >

    <!-- 🚦 Prioridad -->
    <label for="prioridad">Prioridad</label>
    <select name="prioridad" id="prioridad">
      <?php
        $prioridades = ['Normal', 'Alta', 'Urgente'];
        foreach ($prioridades as $p):
          $selected = ($p === ($solicitud['prioridad'] ?? 'Normal')) ? 'selected' : '';
          echo "<option value='$p' $selected>$p</option>";
        endforeach;
      ?>
    </select>

    <!-- 📋 Estado -->
    <label for="estado">Estado</label>
    <select name="estado" id="estado">
      <?php
        $estados = ['Pendiente', 'En Proceso', 'Completada', 'Cancelada'];
        foreach ($estados as $e):
          $selected = ($e === ($solicitud['estado'] ?? 'Pendiente')) ? 'selected' : '';
          echo "<option value='$e' $selected>$e</option>";
        endforeach;
      ?>
    </select>

    <!-- 🗒️ Observaciones -->
    <label for="observaciones">Observaciones</label>
    <input 
      type="text" 
      name="observaciones" 
      id="observaciones"
      value="<?= htmlspecialchars($solicitud['observaciones'] ?? '') ?>"
      placeholder="Notas adicionales (opcional)"
    >

    <!-- 💾 Botón -->
    <button type="submit" class="btn-primary" style="margin-top:15px;">
      <i class="fa-solid fa-save"></i> Actualizar Solicitud
    </button>
  </form>
</div>

<!-- 🎨 Estilos -->
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
    color:#134074;
    margin-bottom:15px;
  }
  label {
    display:block;
    margin-top:10px;
    font-weight:bold;
    color:#333;
  }
  select, input[type="text"] {
    width:100%;
    padding:8px;
    margin-top:4px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
  }
  .btn-primary {
    background:#134074;
    color:white;
    padding:10px 16px;
    text-decoration:none;
    border:none;
    border-radius:6px;
    cursor:pointer;
  }
  .btn-primary:hover {
    background:#0e2a50;
  }
  .btn-secondary {
    display:inline-block;
    background:transparent;
    color:#134074;
    border:1px solid #134074;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    margin-bottom:10px;
  }
  .btn-secondary:hover {
    background:#134074;
    color:white;
  }
  .alert {
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    font-weight:500;
  }
  .alert.danger {
    background:#f8d7da;
    color:#721c24;
    border-left:4px solid #f5c6cb;
  }
  .alert.success {
    background:#d4edda;
    color:#155724;
    border-left:4px solid #c3e6cb;
  }
</style>
