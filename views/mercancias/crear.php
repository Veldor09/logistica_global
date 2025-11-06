<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Tipo de Mercancía</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <h1>➕ Registrar Tipo de Mercancía</h1>

  <form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Descripción:</label>
    <textarea name="descripcion" rows="2"></textarea>

    <label>Costo unitario (₡):</label>
    <input type="number" step="0.01" name="costo_unitario">

    <label>Peso unitario (kg):</label>
    <input type="number" step="0.01" name="peso_unitario_kg">

    <label>Volumen unitario (m³):</label>
    <input type="number" step="0.01" name="volumen_unitario_m3">

    <label>Restricciones:</label>
    <input type="text" name="restricciones">

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo">Activo</option>
      <option value="Inactivo">Inactivo</option>
    </select>

    <button type="submit">💾 Guardar</button>
  </form>

  <a href="/logistica_global/controllers/mercanciaController.php?accion=listar">⬅️ Volver</a>
</body>
</html>
