<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Licencias - Logística Global S.A.</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h1>🪪 Licencias de Conductores</h1>
  <a href="/logistica_global/views/licencias/crear.php">➕ Registrar nueva licencia</a>
  <a href="/logistica_global/controllers/conductorController.php">⬅️ Volver a Conductores</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Conductor</th>
        <th>Tipo</th>
        <th>Número</th>
        <th>Emisión</th>
        <th>Vencimiento</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($licencias as $l): ?>
        <tr>
          <td><?= $l['id_licencia_conductor'] ?></td>
          <td><?= htmlspecialchars($l['conductor']) ?></td>
          <td><?= htmlspecialchars($l['categoria']) ?></td>
          <td><?= htmlspecialchars($l['numero_licencia']) ?></td>
          <td><?= $l['fecha_emision'] ? $l['fecha_emision']->format('Y-m-d') : '-' ?></td>
          <td><?= $l['fecha_vencimiento'] ? $l['fecha_vencimiento']->format('Y-m-d') : '-' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
