<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/Licencia.php';
require_once __DIR__ . '/../../models/Conductor.php';
$conductores = Conductor::obtenerTodos($conn);
$tipos = Licencia::obtenerTipos($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Licencia</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <h1>🪪 Registrar Licencia de Conductor</h1>

  <form method="POST" action="/logistica_global/controllers/licenciaController.php?accion=crear">
    <label>Conductor:</label>
    <select name="id_conductor" required>
      <option value="">-- Seleccione conductor --</option>
      <?php foreach ($conductores as $c): ?>
        <option value="<?= $c['id_conductor'] ?>">
          <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Tipo de Licencia:</label>
    <select name="id_tipo_licencia" required>
      <option value="">-- Seleccione tipo --</option>
      <?php foreach ($tipos as $t): ?>
        <option value="<?= $t['id_tipo_licencia'] ?>"><?= htmlspecialchars($t['categoria']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Número de licencia:</label>
    <input type="text" name="numero_licencia" required>

    <label>Fecha de emisión:</label>
    <input type="date" name="fecha_emision" required>

    <label>Fecha de vencimiento:</label>
    <input type="date" name="fecha_vencimiento" required>

    <button type="submit">💾 Guardar Licencia</button>
  </form>

  <a href="/logistica_global/controllers/licenciaController.php">⬅️ Volver a lista</a>
</body>
</html>
