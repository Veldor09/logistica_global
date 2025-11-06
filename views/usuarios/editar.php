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
      margin-bottom: 30px;
      font-size: 1.8rem;
      letter-spacing: 0.5px;
    }

    .alert {
      padding: 12px 20px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-size: 0.95rem;
      text-align: center;
    }

    .alert-danger {
      background: #ffe5e5;
      color: #a30000;
      border: 1px solid #f2aaaa;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 18px 25px;
    }

    label {
      display: flex;
      flex-direction: column;
      font-weight: 600;
      color: #0b2545;
      font-size: 0.95rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select,
    textarea {
      margin-top: 6px;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: #134074;
      box-shadow: 0 0 0 2px rgba(19, 64, 116, 0.15);
      outline: none;
    }

    .error {
      color: #a30000;
      font-size: 0.9rem;
      margin-top: -8px;
      margin-bottom: 10px;
    }

    /* ===============================
       🔐 Sección de contraseña
    ================================ */
    .card {
      grid-column: 1 / -1;
      background: #f8f9fc;
      border: 1px solid #dcdcdc;
      border-radius: 10px;
      padding: 15px 20px;
      margin-top: 10px;
    }

    .card strong {
      color: #0b2545;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 10px;
    }

    /* ===============================
       ⚙️ Botones
    ================================ */
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 25px;
      grid-column: 1 / -1;
    }

    .btn-primary,
    .btn-secondary {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      font-size: 1rem;
      cursor: pointer;
      font-weight: 600;
      text-decoration: none;
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
      background-color: #e0e0e0;
      color: #333;
    }

    .btn-secondary:hover {
      background-color: #d6d6d6;
    }

    /* ===============================
       📱 Responsive
    ================================ */
    @media (max-width: 700px) {
      .container {
        margin: 20px;
        padding: 30px;
      }

      .form-actions {
        flex-direction: column;
      }

      .btn-primary,
      .btn-secondary {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><?= htmlspecialchars($titulo) ?></h1>

    <?php if (!empty($errores['general'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($errores['general']) ?></div>
    <?php endif; ?>

    <form method="POST" action="/logistica_global/controllers/usuarioController.php?accion=editar&id=<?= $usuario['id_usuario'] ?>" class="form-grid">
      
      <!-- Rol -->
      <label>Rol:
        <select name="id_rol" required>
          <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id_rol'] ?>" <?= ($usuario['id_rol'] == $r['id_rol']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
      <?php if (!empty($errores['id_rol'])): ?><div class="error"><?= $errores['id_rol'] ?></div><?php endif; ?>

      <!-- Nombre -->
      <label>Nombre:
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['nombre'])): ?><div class="error"><?= $errores['nombre'] ?></div><?php endif; ?>

      <!-- Apellidos -->
      <label>Primer Apellido:
        <input type="text" name="apellido1" value="<?= htmlspecialchars($usuario['apellido1'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['apellido1'])): ?><div class="error"><?= $errores['apellido1'] ?></div><?php endif; ?>

      <label>Segundo Apellido:
        <input type="text" name="apellido2" value="<?= htmlspecialchars($usuario['apellido2'] ?? '') ?>">
      </label>

      <!-- Correo -->
      <label>Correo:
        <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['correo'])): ?><div class="error"><?= $errores['correo'] ?></div><?php endif; ?>

      <!-- Teléfono -->
      <label>Teléfono:
        <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
      </label>

      <!-- Contraseña -->
      <div class="card">
        <strong>Contraseña:</strong>
        <p style="margin:6px 0 12px;">Déjala en blanco si no deseas cambiarla.</p>
        <div class="grid-2">
          <label>Nueva contraseña:
            <input type="password" name="password">
          </label>
          <label>Confirmar nueva:
            <input type="password" name="password_confirm">
          </label>
        </div>
        <?php if (!empty($errores['password_confirm'])): ?>
          <div class="error"><?= $errores['password_confirm'] ?></div>
        <?php endif; ?>
      </div>

      <!-- Estado -->
      <label>Estado:
        <select name="estado">
          <option value="Activo" <?= ($usuario['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
          <option value="Inactivo" <?= ($usuario['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
        </select>
      </label>

      <!-- Acciones -->
      <div class="form-actions">
        <a href="/logistica_global/controllers/usuarioController.php?accion=listar" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Actualizar</button>
      </div>
    </form>
  </div>
</body>
</html>
