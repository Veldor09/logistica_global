<div class="container">
  <h1><i class="fa-solid fa-pen-to-square"></i> Editar Solicitud de Transporte</h1>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert danger">❌ Error al actualizar la solicitud.</div>
  <?php endif; ?>

  <a href="/logistica_global/controllers/solicitudController.php" class="btn">
    ⬅️ Volver a la lista
  </a>

  <form method="POST" action="/logistica_global/controllers/solicitudController.php?accion=editar&id=<?= $solicitud['id_solicitud'] ?>">
    
    <!-- Cliente remitente -->
    <label for="id_cliente">Cliente Remitente</label>
    <select name="id_cliente" id="id_cliente" class="input-field" disabled>
      <?php foreach ($clientes as $c): ?>
        <?php $selected = ($c['correo'] === ($solicitud['correo_remitente'] ?? '')) ? 'selected' : ''; ?>
        <option value="<?= $c['id_cliente'] ?>" <?= $selected ?>>
          <?= htmlspecialchars($c['correo']) ?> (<?= $c['tipo_identificacion'] ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- Cliente destinatario -->
    <label for="id_destinatario">Cliente Destinatario</label>
    <select name="id_destinatario" id="id_destinatario" class="input-field">
      <option value="">-- Seleccione destinatario --</option>
      <?php foreach ($clientes as $c): ?>
        <?php $selected = ($c['correo'] === ($solicitud['correo_destinatario'] ?? '')) ? 'selected' : ''; ?>
        <option value="<?= $c['id_cliente'] ?>" <?= $selected ?>>
          <?= htmlspecialchars($c['correo']) ?> (<?= $c['tipo_identificacion'] ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <!-- Tipo de servicio -->
    <label for="tipo_servicio">Tipo de Servicio</label>
    <input type="text" name="tipo_servicio" id="tipo_servicio"
           value="<?= htmlspecialchars($solicitud['tipo_servicio']) ?>" required>

    <!-- Descripción -->
    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion"
           value="<?= htmlspecialchars($solicitud['descripcion'] ?? '') ?>">

    <!-- Origen -->
    <label for="origen">Origen</label>
    <input type="text" name="origen" id="origen"
           value="<?= htmlspecialchars($solicitud['origen'] ?? '') ?>">

    <!-- Destino -->
    <label for="destino_general">Destino</label>
    <input type="text" name="destino_general" id="destino_general"
           value="<?= htmlspecialchars($solicitud['destino_general'] ?? '') ?>">

    <!-- Prioridad -->
    <label for="prioridad">Prioridad</label>
    <select name="prioridad" id="prioridad">
      <?php
        $prioridades = ['Normal', 'Alta', 'Urgente'];
        foreach ($prioridades as $p):
          $selected = ($p === $solicitud['prioridad']) ? 'selected' : '';
          echo "<option value='$p' $selected>$p</option>";
        endforeach;
      ?>
    </select>

    <!-- Estado -->
    <label for="estado">Estado</label>
    <select name="estado" id="estado">
      <?php
        $estados = ['Pendiente', 'En Proceso', 'Completada', 'Cancelada'];
        foreach ($estados as $e):
          $selected = ($e === $solicitud['estado']) ? 'selected' : '';
          echo "<option value='$e' $selected>$e</option>";
        endforeach;
      ?>
    </select>

    <!-- Observaciones -->
    <label for="observaciones">Observaciones</label>
    <input type="text" name="observaciones" id="observaciones"
           value="<?= htmlspecialchars($solicitud['observaciones'] ?? '') ?>">

    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-save"></i> Actualizar Solicitud
    </button>
  </form>
</div>
