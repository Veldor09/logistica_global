<div class="container">
  <h1>👷 Registrar Conductor</h1>
  <a href="/logistica_global/controllers/conductorController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/conductorController.php?accion=crear">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Primer Apellido:</label>
    <input type="text" name="apellido1" required>

    <label>Segundo Apellido:</label>
    <input type="text" name="apellido2">

    <label>Cédula:</label>
    <input type="text" name="cedula" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono">

    <label>Correo:</label>
    <input type="email" name="correo">

    <label>Dirección:</label>
    <input type="text" name="direccion">

    <label>Fecha de Ingreso:</label>
    <input type="date" name="fecha_ingreso">

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo">Activo</option>
      <option value="Inactivo">Inactivo</option>
    </select>

    <button type="submit" class="btn primary">💾 Guardar</button>
  </form>
</div>
