<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Tipo de Mercancía</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <h1>📦 Registrar Tipo de Mercancía</h1>

  <form method="POST" action="/logistica_global/controllers/mercanciaController.php?accion=crear">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Descripción:</label>
    <textarea name="descripcion" rows="2"></textarea>

    <label>Costo unitario:</label>
    <input type="number" step="0.01" name="costo_unitario">

    <label>Restricciones:</label>
    <input type="text" name="restricciones">

    <button type="submit">💾 Guardar</button>
  </form>

  <a href="/logistica_global/controllers/mercanciaController.php">⬅️ Volver a lista</a>
</body>
</html>
