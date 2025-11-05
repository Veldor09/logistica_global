<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Conductor</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <h1>👷 Registrar Conductor</h1>

  <form method="POST" action="/logistica_global/controllers/conductorController.php?accion=crear">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Apellidos:</label>
    <input type="text" name="apellidos" required>

    <label>Cédula:</label>
    <input type="text" name="cedula">

    <label>Teléfono:</label>
    <input type="text" name="telefono">

    <label>Correo:</label>
    <input type="email" name="correo">

    <label>Dirección:</label>
    <input type="text" name="direccion">

    <button type="submit">💾 Guardar Conductor</button>
  </form>

  <a href="/logistica_global/controllers/conductorController.php">⬅️ Volver a lista</a>
</body>
</html>
