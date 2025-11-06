<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

/* ===========================================================
   📦 Dependencias base
=========================================================== */
$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/config/auth_guard.php'; // 🛡️ protección
require_once $BASE_PATH . '/models/Ruta.php';
require_once $BASE_PATH . '/common/auditoria.php'; // 🧾 Auditoría global

/* ===========================================================
   🧩 Render con layout principal
=========================================================== */
function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

/* ===========================================================
   🔁 Redirección simple
=========================================================== */
function redirect($path) {
  header("Location: $path");
  exit;
}

/* ===========================================================
   🔀 Enrutador principal
=========================================================== */
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
  case 'listar':
    listarRutas($conn);
    break;

  case 'crear':
    ($_SERVER['REQUEST_METHOD'] === 'POST')
      ? crearRutaPost($conn)
      : crearRutaGet($conn);
    break;

  case 'editar':
    ($_SERVER['REQUEST_METHOD'] === 'POST')
      ? editarRutaPost($conn)
      : editarRutaGet($conn);
    break;

  case 'eliminar':
    eliminarRuta($conn);
    break;

  default:
    listarRutas($conn);
    break;
}

/* ===========================================================
   📋 Listar rutas
=========================================================== */
function listarRutas($conn) {
  $rutas = Ruta::obtenerTodas($conn);
  view('rutas/listar.php', [
    'titulo' => 'Gestión de Rutas',
    'rutas' => $rutas
  ]);
}

/* ===========================================================
   ➕ Crear ruta
=========================================================== */
function crearRutaGet($conn) {
  view('rutas/crear.php', [
    'titulo' => 'Registrar Nueva Ruta',
    'errores' => [],
    'old' => []
  ]);
}

function crearRutaPost($conn) {
  $data = $_POST;
  $errores = [];

  // ✅ Validaciones básicas
  if (empty($data['nombre_ruta'])) $errores['nombre_ruta'] = 'Debe ingresar un nombre de ruta.';
  if (empty($data['origen']))      $errores['origen']      = 'Debe ingresar el punto de origen.';
  if (empty($data['destino']))     $errores['destino']     = 'Debe ingresar el punto de destino.';

  if (!empty($errores)) {
    view('rutas/crear.php', [
      'titulo' => 'Registrar Nueva Ruta',
      'errores' => $errores,
      'old' => $data
    ]);
    return;
  }

  try {
    $id = Ruta::crear($conn, $data);
    registrarAccion($conn, $_SESSION['usuario']['correo'] ?? 'admin@logistica.com', 'Ruta', 'INSERT', "Se creó la ruta #$id.");
    redirect('/logistica_global/controllers/rutaController.php?accion=listar');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear la ruta: ' . $e->getMessage();
    view('rutas/crear.php', [
      'titulo' => 'Registrar Nueva Ruta',
      'errores' => $errores,
      'old' => $data
    ]);
  }
}

/* ===========================================================
   ✏️ Editar ruta
=========================================================== */
function editarRutaGet($conn) {
  $id = (int)($_GET['id'] ?? 0);
  $ruta = Ruta::obtenerPorId($conn, $id);
  if (!$ruta) {
    redirect('/logistica_global/controllers/rutaController.php?accion=listar');
    return;
  }

  view('rutas/editar.php', [
    'titulo' => 'Editar Ruta',
    'ruta' => $ruta,
    'errores' => []
  ]);
}

function editarRutaPost($conn) {
  $id = (int)($_GET['id'] ?? 0);
  $errores = [];

  if ($id <= 0) {
    redirect('/logistica_global/controllers/rutaController.php?accion=listar');
    return;
  }

  if (empty($_POST['nombre_ruta'])) $errores['nombre_ruta'] = 'Debe ingresar un nombre de ruta.';
  if (empty($_POST['origen']))      $errores['origen']      = 'Debe ingresar el punto de origen.';
  if (empty($_POST['destino']))     $errores['destino']     = 'Debe ingresar el punto de destino.';

  if (!empty($errores)) {
    $relleno = $_POST;
    $relleno['id_ruta'] = $id; // 👈 necesario para el action del form
    view('rutas/editar.php', [
      'titulo' => 'Editar Ruta',
      'ruta' => $relleno,
      'errores' => $errores
    ]);
    return;
  }

  try {
    Ruta::actualizar($conn, $id, $_POST);
    registrarAccion($conn, $_SESSION['usuario']['correo'] ?? 'admin@logistica.com', 'Ruta', 'UPDATE', "Se actualizó la ruta #$id.");
    redirect('/logistica_global/controllers/rutaController.php?accion=listar');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al actualizar: ' . $e->getMessage();
    $relleno = $_POST;
    $relleno['id_ruta'] = $id;
    view('rutas/editar.php', [
      'titulo' => 'Editar Ruta',
      'ruta' => $relleno,
      'errores' => $errores
    ]);
  }
}

/* ===========================================================
   🗑️ Eliminar ruta
=========================================================== */
function eliminarRuta($conn) {
  $id = (int)($_GET['id'] ?? 0);
  if ($id > 0) {
    try {
      Ruta::eliminar($conn, $id);
      registrarAccion($conn, $_SESSION['usuario']['correo'] ?? 'admin@logistica.com', 'Ruta', 'DELETE', "Se eliminó la ruta #$id.");
    } catch (Throwable $e) {
      die("<pre>Error al eliminar ruta:\n{$e->getMessage()}</pre>");
    }
  }
  redirect('/logistica_global/controllers/rutaController.php?accion=listar');
}
