<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($titulo) ?></title>
  <style>
    /* ===============================
       🎨 Estilos base
    ================================ */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
      margin: 0;
      padding: 0;
    }

    .container {
      background: #ffffff;
      max-width: 1200px;
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
       🔍 Filtros
    ================================ */
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
      align-items: center;
    }

    .filters input[type="text"],
    .filters select {
      padding: 8px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 0.95rem;
      flex: 1;
      min-width: 180px;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .filters input:focus,
    .filters select:focus {
      border-color: #134074;
      box-shadow: 0 0 0 2px rgba(19, 64, 116, 0.15);
      outline: none;
    }

    /* ===============================
       🔘 Botones
    ================================ */
    .btn-primary,
    .btn-secondary,
    .btn-light,
    .btn-edit,
    .btn-danger {
      display: inline-block;
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 0.95rem;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background-color: #134074;
      color: #fff;
    }
    .btn-primary:hover {
      background-color: #0b2545;
    }

    .btn-secondary {
      background-color: #1f7a8c;
      color: #fff;
    }
    .btn-secondary:hover {
      background-color: #14596b;
    }

    .btn-light {
      background-color: #e0e0e0;
      color: #333;
    }
    .btn-light:hover {
      background-color: #d6d6d6;
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
      margin-top: 20px;
    }

    .table th, .table td {
      border-bottom: 1px solid #e0e0e0;
      padding: 10px 12px;
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
       📱 Responsive tabla
    ================================ */
    @media (max-width: 800px) {
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

      .filters {
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><?= htmlspecialchars($titulo) ?></h1>

    <!-- 🔍 Filtros -->
    <form method="GET" action="/logistica_global/controllers/usuarioController.php" class="filters">
      <input type="hidden" name="accion" value="listar">
      <input type="text" name="search" placeholder="Buscar (nombre, correo...)" value="<?= htmlspecialchars($filtros['search'] ?? '') ?>">
      <select name="id_rol">
        <option value="">Rol (todos)</option>
        <?php foreach ($roles as $r): ?>
          <option value="<?= $r['id_rol'] ?>" <?= (($filtros['id_rol'] ?? '') == $r['id_rol']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <select name="estado">
        <option value="">Estado (todos)</option>
        <?php foreach (['Activo','Inactivo'] as $e): ?>
          <option value="<?= $e ?>" <?= (($filtros['estado'] ?? '') == $e) ? 'selected' : '' ?>><?= $e ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn-secondary">Filtrar</button>
      <a href="/logistica_global/controllers/usuarioController.php?accion=listar" class="btn-light">Limpiar</a>
    </form>

    <!-- ➕ Nuevo Usuario -->
    <a href="/logistica_global/controllers/usuarioController.php?accion=crear" class="btn-primary">+ Nuevo Usuario</a>

    <!-- 📋 Tabla de usuarios -->
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre completo</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Registro</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($usuarios)): ?>
          <tr><td colspan="8" style="text-align:center; color:#555;">No hay usuarios registrados.</td></tr>
        <?php else: foreach ($usuarios as $u): ?>
          <tr>
            <td data-label="ID"><?= $u['id_usuario'] ?></td>
            <td data-label="Nombre completo"><?= htmlspecialchars(trim($u['nombre'])) ?></td>
            <td data-label="Correo"><?= htmlspecialchars($u['correo']) ?></td>
            <td data-label="Teléfono"><?= htmlspecialchars($u['telefono'] ?? '-') ?></td>
            <td data-label="Rol"><?= htmlspecialchars($u['rol']) ?></td>
            <td data-label="Estado">
              <?php $color = ($u['estado'] === 'Activo') ? '#1d8348' : '#a93226'; ?>
              <span style="color:<?= $color ?>; font-weight:600;"><?= htmlspecialchars($u['estado']) ?></span>
            </td>
            <td data-label="Registro"><?= htmlspecialchars($u['fecha_creacion'] ?? '-') ?></td>
            <td data-label="Acciones" style="display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
              <a class="btn-edit" href="/logistica_global/controllers/usuarioController.php?accion=editar&id=<?= $u['id_usuario'] ?>">Editar</a>
              <a class="btn-danger" href="/logistica_global/controllers/usuarioController.php?accion=eliminar&id=<?= $u['id_usuario'] ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
