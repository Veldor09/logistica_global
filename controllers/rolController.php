<?php
// ============================================================
// 🎛️ Controlador de Roles del Sistema
// ============================================================

session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);

// ============================================================
// 📦 Dependencias principales
// ============================================================
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/config/auth_guard.php';   // 🔒 Protección de sesión
require_once $BASE_PATH . '/models/Rol.php';
require_once $BASE_PATH . '/common/auditoria.php';

/* ============================================================
   ⚙️ Helpers de vista y redirección
============================================================ */
function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

function redirect($path) {
  header("Location: $path");
  exit;
}

/* ============================================================
   🚦 Enrutamiento principal (acciones)
============================================================ */
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
  case 'listar':   listarRoles($conn); break;
  case 'crear':    ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearRolPost($conn) : crearRolGet($conn); break;
  case 'editar':   ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarRolPost($conn) : editarRolGet($conn); break;
  case 'eliminar': eliminarRol($conn); break;
  default:         listarRoles($conn); break;
}

/* ============================================================
   📋 LISTAR ROLES
============================================================ */
function listarRoles($conn) {
  try {
    $roles = Rol::obtenerTodos($conn);
    view('roles/listar.php', [
      'titulo' => 'Gestión de Roles del Sistema',
      'roles'  => $roles
    ]);
  } catch (Throwable $e) {
    echo "❌ Error al listar roles: " . $e->getMessage();
  }
}

/* ============================================================
   ➕ CREAR ROL
============================================================ */
function crearRolGet($conn) {
  view('roles/crear.php', [
    'titulo'  => 'Registrar Rol',
    'errores' => [],
    'old'     => []
  ]);
}

function crearRolPost($conn) {
  $data = $_POST;
  $errores = [];

  if (empty(trim($data['nombre'] ?? ''))) {
    $errores['nombre'] = 'Debe ingresar un nombre de rol.';
  }

  if (!empty($errores)) {
    view('roles/crear.php', [
      'titulo'  => 'Registrar Rol',
      'errores' => $errores,
      'old'     => $data
    ]);
    return;
  }

  try {
    $id = Rol::crear($conn, $data);

    // 🧾 Auditoría
    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Rol',
      'INSERT',
      "Se creó el rol #$id ({$data['nombre']}).",
      $_SESSION['usuario']['id'] ?? null
    );

    redirect('/logistica_global/controllers/rolController.php?accion=listar&success=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear el rol: ' . $e->getMessage();
    view('roles/crear.php', [
      'titulo'  => 'Registrar Rol',
      'errores' => $errores,
      'old'     => $data
    ]);
  }
}

/* ============================================================
   ✏️ EDITAR ROL
============================================================ */
function editarRolGet($conn) {
  $id  = $_GET['id'] ?? 0;
  $rol = Rol::obtenerPorId($conn, $id);

  if (!$rol) {
    redirect('/logistica_global/controllers/rolController.php?accion=listar&notfound=1');
  }

  view('roles/editar.php', [
    'titulo'  => 'Editar Rol',
    'rol'     => $rol,
    'errores' => []
  ]);
}

function editarRolPost($conn) {
  $id   = $_GET['id'] ?? 0;
  $data = $_POST;

  try {
    Rol::actualizar($conn, $id, $data);

    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Rol',
      'UPDATE',
      "Se actualizó el rol #$id ({$data['nombre']}).",
      $_SESSION['usuario']['id'] ?? null
    );

    redirect('/logistica_global/controllers/rolController.php?accion=listar&updated=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al actualizar el rol: ' . $e->getMessage();
    view('roles/editar.php', [
      'titulo'  => 'Editar Rol',
      'rol'     => array_merge(['id_rol' => $id], $data),
      'errores' => $errores
    ]);
  }
}

/* ============================================================
   🗑️ ELIMINAR ROL
============================================================ */
function eliminarRol($conn) {
  $id = $_GET['id'] ?? 0;

  if (!$id) {
    redirect('/logistica_global/controllers/rolController.php?accion=listar');
  }

  try {
    Rol::eliminar($conn, (int)$id);

    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Rol',
      'DELETE',
      "Se eliminó el rol #$id.",
      $_SESSION['usuario']['id'] ?? null
    );

    redirect('/logistica_global/controllers/rolController.php?accion=listar&deleted=1');
  } catch (Throwable $e) {
    echo "❌ Error al eliminar rol: " . $e->getMessage();
  }
}
?>
