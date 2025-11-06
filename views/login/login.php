<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión - Logística Global</title>
  <link rel="stylesheet" href="/logistica_global/assets/css/style.css">
  <style>
    body.login-page {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background: linear-gradient(135deg, #0a4b78, #1273a6);
      font-family: "Segoe UI", Arial, sans-serif;
    }

    .login-box {
      background: #fff;
      border-radius: 10px;
      padding: 2rem;
      width: 360px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .login-box h1 {
      text-align: center;
      color: #0a4b78;
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin-bottom: 1rem;
      font-weight: 500;
      color: #333;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #1273a6;
    }

    .btn-primary {
      width: 100%;
      padding: 0.7rem;
      background-color: #198754;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #157347;
    }

    .btn-back {
      display: inline-block;
      width: 100%;
      text-align: center;
      background-color: #6c757d;
      color: white;
      padding: 0.7rem;
      border-radius: 6px;
      font-size: 1rem;
      text-decoration: none;
      font-weight: 500;
      margin-top: 0.8rem;
      transition: background 0.3s ease;
    }

    .btn-back:hover {
      background-color: #5a6268;
    }

    .alert {
      padding: 0.8rem;
      border-radius: 6px;
      margin-bottom: 1rem;
      text-align: center;
      font-weight: 500;
      font-size: 0.95rem;
    }

    .alert.danger {
      background-color: #f8d7da;
      color: #842029;
      border: 1px solid #f5c2c7;
    }

    .alert.success {
      background-color: #d1e7dd;
      color: #0f5132;
      border: 1px solid #badbcc;
    }
  </style>
</head>

<body class="login-page">
  <div class="login-box">
    <h1>🔐 Iniciar Sesión</h1>

    <!-- ✅ Mensaje de cierre de sesión -->
    <?php if (!empty($mensaje)): ?>
      <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- ❌ Mensaje de error -->
    <?php if (!empty($error)): ?>
      <div class="alert danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/logistica_global/controllers/loginController.php">
      <label>Correo electrónico:
        <input type="email" name="correo" required placeholder="usuario@correo.com">
      </label>

      <label>Contraseña:
        <input type="password" name="contrasena" required placeholder="••••••••">
      </label>

      <button type="submit" class="btn-primary">Entrar</button>
    </form>

    <!-- 🔙 Botón para volver al inicio -->
    <a href="/logistica_global/index.php" class="btn-back">⬅ Volver al Inicio</a>
  </div>
</body>
</html>
