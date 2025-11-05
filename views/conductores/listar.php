<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Conductores - Logística Global S.A.</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h1>🚛 Lista de Conductores</h1>
  <a href="/logistica_global/views/conductores/crear.php">➕ Registrar nuevo conductor</a>
  <a href="/logistica_global/controllers/vehiculoController.php">⬅️ Volver a Vehículos</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Cédula</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Estado</th>
        <th>Fecha Ingreso</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($conductores as $c): ?>
        <tr>
          <td><?= $c['id_conductor'] ?></td>
          <td><?= htmlspecialchars($c['nombre']) ?></td>
          <td><?= htmlspecialchars($c['apellidos']) ?></td>
          <td><?= htmlspecialchars($c['cedula']) ?></td>
          <td><?= htmlspecialchars($c['telefono']) ?></td>
          <td><?= htmlspecialchars($c['correo']) ?></td>
          <td><?= htmlspecialchars($c['estado']) ?></td>
          <td><?= $c['fecha_ingreso'] ? $c['fecha_ingreso']->format('Y-m-d') : '-' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
