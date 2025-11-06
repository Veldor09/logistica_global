<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Tipo de Mercancía</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <h1>✏️ Editar Tipo de Mercancía</h1>

  <form method="POST" action="">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($mercancia['nombre']) ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" rows="2"><?= htmlspecialchars($mercancia['descripcion']) ?></textarea>

    <label>Costo unitario (₡):</label>
    <input type="number" step="0.01" name="costo_unitario" value="<?= $mercancia['costo_unitario'] ?>">

    <label>Peso unitario (kg):</label>
    <input type="number" step="0.01" name="peso_unitario_kg" value="<?= $mercancia['peso_unitario_kg'] ?>">

    <label>Volumen unitario (m³):</label>
    <input type="number" step="0.01" name="volumen_unitario_m3" value="<?= $mercancia['volumen_unitario_m3'] ?>">

    <label>Restricciones:</label>
    <input type="text" name="restricciones" value="<?= htmlspecialchars($mercancia['restricciones']) ?>">

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo" <?= $mercancia['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
      <option value="Inactivo" <?= $mercancia['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
    </select>

    <button type="submit">💾 Actualizar</button>
  </form>

  <a href="/logistica_global/controllers/mercanciaController.php?accion=listar">⬅️ Volver</a>
</body>
</html>
