<?php
// ============================================================
// 🔐 controllers/loginController.php
// Controla el inicio y cierre de sesión del sistema
// ============================================================

session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/models/Usuario.php';
require_once $BASE_PATH . '/common/auditoria.php';

/* ============================================================
   🚪 LOGOUT (Cerrar sesión)
============================================================ */
if (isset($_GET['logout'])) {
  if (!empty($_SESSION['usuario'])) {
    // 🧾 Registrar acción de cierre de sesión
    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Login',
      'LOGOUT',
      'Cierre de sesión exitoso',
      $_SESSION['usuario']['id'] ?? null
    );
  }

  // Limpiar y destruir la sesión
  session_unset();
  session_destroy();

  // Redirigir al login con mensaje
  header("Location: /logistica_global/controllers/loginController.php?logout_success=1");
  exit;
}

/* ============================================================
   🔑 LOGIN (Iniciar sesión)
============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = trim($_POST['correo'] ?? '');
  $clave  = trim($_POST['contrasena'] ?? '');

  // ⚠️ Validación básica
  if ($correo === '' || $clave === '') {
    $error = "Debe ingresar el correo y la contraseña.";
    include $BASE_PATH . '/views/login/login.php';
    exit;
  }

  try {
    // 🔎 Verificar credenciales contra la BD
    $user = Usuario::login($conn, $correo, $clave);

    if ($user) {
      // ✅ Crear sesión
      $_SESSION['usuario'] = [
        'id'     => $user['id_usuario'],
        'nombre' => $user['nombre'],
        'correo' => $user['correo'],
        'rol'    => $user['rol']
      ];

      // 🧾 Registrar acción de login
      registrarAccion(
        $conn,
        $user['correo'],
        $user['rol'],
        'Login',
        'LOGIN',
        'Inicio de sesión exitoso',
        $user['id_usuario']
      );

      // Redirigir al panel principal
      header('Location: /logistica_global/');
      exit;
    } else {
      // ❌ Credenciales inválidas
      $error = "⚠️ Credenciales incorrectas o usuario inactivo.";
      include $BASE_PATH . '/views/login/login.php';
    }
  } catch (Throwable $e) {
    $error = "Error al iniciar sesión: " . htmlspecialchars($e->getMessage());
    include $BASE_PATH . '/views/login/login.php';
  }

} else {
  // 📢 Si viene del logout, mostrar mensaje
  $mensaje = isset($_GET['logout_success']) ? "✅ Sesión cerrada correctamente." : '';
  include $BASE_PATH . '/views/login/login.php';
}
?>
