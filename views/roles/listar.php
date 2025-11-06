<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($titulo) ?></title>
  <style>
    /* ===============================
       🎨 Estilos generales
    ================================ */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
      margin: 0;
      padding: 0;
    }

    .container {
      background: #ffffff;
      max-width: 900px;
      margin: 60px auto;
      padding: 40px 50px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #134074;
      margin-bottom: 25px;
      font-size: 1.8rem;
      letter-spacing: 0.5px;
    }

    /* ===============================
       🔘 Botones
    ================================ */
    .btn-primary,
    .btn-edit,
    .btn-danger {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 0.95rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #134074;
      color: #fff;
      margin-bottom: 20px;
    }
    .btn-primary:hover {
      background-color: #0b2545;
    }

    .btn-edit {
      background-color: #1f7a8c;
      color: #fff;
    }
    .btn-edit:hover {
      background-color: #14596b;
    }

    .btn-danger {
      background-color: #c0392b;
      color: #fff;
    }
    .btn-danger:hover {
      background-color: #922b21;
    }

    /* ===============================
       📊 Tabla
    ================================ */
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .table th, .table td {
      border-bottom: 1px solid #e0e0e0;
      padding: 12px 10px;
      text-align: left;
      font-size: 0.95rem;
    }

    .table th {
      background-color: #f0f4f8;
      color: #0b2545;
      font-weight: 700;
    }

    .table tr:hover {
      background-color: #f9fbff;
    }

    .table td {
      color: #333;
      vertical-align: middle;
    }

    .table td:last-child {
      text-align: center;
    }

    /* ===============================
       🧾 Estados
    ================================ */
    .estado-activo {
      color: #1d8348;
      font-weight: 600;
    }

    .estado-inactivo {
      color: #a93226;
      font-weight: 600;
    }

    /* ===============================
       📱 Responsive
    ================================ */
    @media (max-width: 700px) {
      .container {
        margin: 20px;
        padding: 25px;
      }

      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
      }

      td {
        text-align: right;
        position: relative;
        padding-left: 50%;
      }

      td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: 45%;
        text-align: left;
        font-weight: bold;
        color: #0b2545;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><?= htmlspecialchars($titulo) ?></h1>

    <a href="/logistica_global/controllers/rolController.php?accion=crear" class="btn-primary">+ Nuevo Rol</a>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($roles)): ?>
          <tr><td colspan="5" style="text-align:center; color:#555;">No hay roles registrados.</td></tr>
        <?php else: foreach ($roles as $r): ?>
          <tr>
            <td data-label="ID"><?= htmlspecialchars($r['id_rol']) ?></td>
            <td data-label="Nombre"><?= htmlspecialchars($r['nombre']) ?></td>
            <td data-label="Descripción"><?= htmlspecialchars($r['descripcion'] ?? '-') ?></td>
            <td data-label="Estado">
              <?php if ($r['estado'] === 'Activo'): ?>
                <span class="estado-activo">Activo</span>
              <?php else: ?>
                <span class="estado-inactivo">Inactivo</span>
              <?php endif; ?>
            </td>
            <td data-label="Acciones" style="display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
              <a href="/logistica_global/controllers/rolController.php?accion=editar&id=<?= $r['id_rol'] ?>" class="btn-edit">Editar</a>
              <a href="/logistica_global/controllers/rolController.php?accion=eliminar&id=<?= $r['id_rol'] ?>" class="btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este rol?')">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
