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
      max-width: 600px;
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

    label {
      display: block;
      margin-bottom: 18px;
      font-weight: 600;
      color: #0b2545;
    }

    input[type="text"],
    textarea,
    select {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    input:focus,
    textarea:focus,
    select:focus {
      border-color: #134074;
      box-shadow: 0 0 0 2px rgba(19, 64, 116, 0.15);
      outline: none;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    .error {
      color: #a30000;
      font-size: 0.9rem;
      margin-top: -10px;
      margin-bottom: 15px;
    }

    .form-actions {
      margin-top: 25px;
      display: flex;
      justify-content: flex-end;
      gap: 15px;
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

    @media (max-width: 600px) {
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

    <form method="POST" action="/logistica_global/controllers/rolController.php?accion=crear">
      <label>Nombre del Rol:
        <input type="text" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>
      </label>
      <?php if (!empty($errores['nombre'])): ?>
        <div class="error"><?= htmlspecialchars($errores['nombre']) ?></div>
      <?php endif; ?>

      <label>Descripción:
        <textarea name="descripcion"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
      </label>

      <label>Estado:
        <select name="estado">
          <option value="Activo" <?= (($old['estado'] ?? '') == 'Activo') ? 'selected' : '' ?>>Activo</option>
          <option value="Inactivo" <?= (($old['estado'] ?? '') == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
        </select>
      </label>

      <div class="form-actions">
        <a href="/logistica_global/controllers/rolController.php?accion=listar" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</body>
</html>
