<div class="container" style="max-width:800px; margin:auto; padding:25px;">
  <h1><i class="fa-solid fa-truck-fast"></i> Registrar Solicitud de Transporte</h1>
  <p>Complete el formulario para solicitar un servicio de transporte. Un asesor lo contactará pronto.</p>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert success">✅ Su solicitud ha sido registrada exitosamente. Le contactaremos pronto.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert danger">❌ Error al registrar su solicitud. Intente nuevamente.</div>
  <?php endif; ?>

  <form method="POST" action="/logistica_global/controllers/solicitudController.php?accion=crear_publica" class="form-publica">

    <!-- 📧 Correo -->
    <label for="correo">Correo de contacto</label>
    <input type="email" name="correo" id="correo" required placeholder="ejemplo@correo.com">

    <!-- 📞 Teléfono -->
    <label for="telefono">Teléfono</label>
    <input type="text" name="telefono" id="telefono" required placeholder="8888-8888">

    <!-- 👤 Nombre -->
    <label for="nombre">Nombre completo</label>
    <input type="text" name="nombre" id="nombre" placeholder="Ej. Juan Pérez" required>

    <!-- 🚛 Tipo de Servicio -->
    <label for="tipo_servicio">Tipo de Servicio</label>
    <select name="tipo_servicio" id="tipo_servicio" required>
      <option value="">-- Seleccione --</option>
      <option value="Transporte de carga">Transporte de carga</option>
      <option value="Mudanza">Mudanza</option>
      <option value="Recolección">Recolección</option>
    </select>

    <!-- 📦 Descripción -->
    <label for="descripcion">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="3" placeholder="Ej. Transporte de materiales desde el centro a Guanacaste"></textarea>

    <!-- 🗺️ Origen -->
    <label for="origen">Origen</label>
    <input type="text" name="origen" id="origen" placeholder="Ej. Alajuela, centro" required>

    <!-- 🏁 Destino -->
    <label for="destino_general">Destino</label>
    <input type="text" name="destino_general" id="destino_general" placeholder="Ej. San José, Pavas" required>

    <!-- 🗒️ Observaciones -->
    <label for="observaciones">Observaciones adicionales</label>
    <textarea name="observaciones" id="observaciones" rows="2" placeholder="Información extra o solicitudes especiales"></textarea>

    <div style="margin-top:20px;">
      <button type="submit" class="btn-primary">
        <i class="fa-solid fa-paper-plane"></i> Enviar Solicitud
      </button>
      <a href="/logistica_global/controllers/solicitudController.php" class="btn-secondary">⬅ Volver</a>
    </div>
  </form>
</div>

<style>
  .container h1 { color:#134074; margin-bottom:10px; }
  .alert {
    padding:10px; border-radius:6px; margin-bottom:15px;
  }
  .alert.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
  .alert.danger { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
  .form-publica label { display:block; margin-top:10px; font-weight:bold; }
  .form-publica input, .form-publica select, .form-publica textarea {
    width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;
  }
  .btn-primary {
    background:#134074; color:white; padding:10px 16px;
    border:none; border-radius:6px; cursor:pointer;
  }
  .btn-primary:hover { background:#0e2a50; }
  .btn-secondary {
    margin-left:10px; color:#134074; text-decoration:none;
    border:1px solid #134074; padding:9px 14px; border-radius:6px;
  }
  .btn-secondary:hover { background:#134074; color:white; }
</style>
