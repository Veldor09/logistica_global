<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($titulo) ?></title>
  <style>
    /* ===============================
       🎨 Estilos generales del formulario
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

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 20px;
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

    <form method="POST" action="/logistica_global/controllers/usuarioController.php?accion=crear" class="form-grid">

      <label>Rol:
        <select name="id_rol" required>
          <option value="">-- Seleccionar --</option>
          <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id_rol'] ?>" <?= (($old['id_rol'] ?? '') == $r['id_rol']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
      <?php if (!empty($errores['id_rol'])): ?><div class="error"><?= $errores['id_rol'] ?></div><?php endif; ?>

      <label>Nombre:
        <input type="text" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['nombre'])): ?><div class="error"><?= $errores['nombre'] ?></div><?php endif; ?>

      <label>Primer Apellido:
        <input type="text" name="apellido1" value="<?= htmlspecialchars($old['apellido1'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['apellido1'])): ?><div class="error"><?= $errores['apellido1'] ?></div><?php endif; ?>

      <label>Segundo Apellido:
        <input type="text" name="apellido2" value="<?= htmlspecialchars($old['apellido2'] ?? '') ?>">
      </label>

      <label>Correo:
        <input type="email" name="correo" value="<?= htmlspecialchars($old['correo'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['correo'])): ?><div class="error"><?= $errores['correo'] ?></div><?php endif; ?>

      <label>Teléfono:
        <input type="text" name="telefono" value="<?= htmlspecialchars($old['telefono'] ?? '') ?>">
      </label>

      <label>Contraseña:
        <input type="password" name="password" required>
      </label>
      <?php if (!empty($errores['password'])): ?><div class="error"><?= $errores['password'] ?></div><?php endif; ?>

      <label>Confirmar Contraseña:
        <input type="password" name="password_confirm" required>
      </label>
      <?php if (!empty($errores['password_confirm'])): ?><div class="error"><?= $errores['password_confirm'] ?></div><?php endif; ?>

      <label>Estado:
        <select name="estado">
          <option value="Activo" <?= (($old['estado'] ?? '') == 'Activo') ? 'selected' : '' ?>>Activo</option>
          <option value="Inactivo" <?= (($old['estado'] ?? '') == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
        </select>
      </label>

      <div class="form-actions" style="grid-column: 1 / -1;">
        <a href="/logistica_global/controllers/usuarioController.php?accion=listar" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</body>
</html>
