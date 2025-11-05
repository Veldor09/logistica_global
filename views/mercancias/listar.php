<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tipos de Mercancía - Logística Global S.A.</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h1>📦 Tipos de Mercancía</h1>
  <a href="/logistica_global/views/mercancias/crear.php">➕ Registrar nuevo tipo</a>
  <a href="/logistica_global/controllers/licenciaController.php">⬅️ Volver a Licencias</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Costo Unitario</th>
        <th>Restricciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tipos as $t): ?>
        <tr>
          <td><?= $t['id_tipo_mercancia'] ?></td>
          <td><?= htmlspecialchars($t['nombre']) ?></td>
          <td><?= htmlspecialchars($t['descripcion']) ?></td>
          <td><?= htmlspecialchars($t['costo_unitario']) ?></td>
          <td><?= htmlspecialchars($t['restricciones']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
