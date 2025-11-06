<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/models/TramoRuta.php';
require_once $BASE_PATH . '/common/auditoria.php'; // 🧾 Auditoría global
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
/* ============================================================
   📄 FUNCIÓN VIEW CENTRALIZADA (usa layout global)
============================================================ */
function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

/* ============================================================
   🔁 REDIRECCIÓN SIMPLE
============================================================ */
function redirect($path) {
  header("Location: $path");
  exit;
}

/* ============================================================
   🚦 CONTROLADOR PRINCIPAL
============================================================ */
$accion = $_GET['accion'] ?? 'listar';
switch ($accion) {
  case 'listar':
    listarTramos($conn);
    break;
  case 'crear':
    ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearTramoPost($conn) : crearTramoGet($conn);
    break;
  case 'editar':
    ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarTramoPost($conn) : editarTramoGet($conn);
    break;
  case 'eliminar':
    eliminarTramo($conn);
    break;
  default:
    listarTramos($conn);
    break;
}

/* ============================================================
   📋 LISTAR TRAMOS
============================================================ */
function listarTramos($conn) {
  $tramos = TramoRuta::obtenerTodos($conn);
  view('tramos/listar.php', [
    'titulo' => 'Gestión de Tramos de Ruta',
    'tramos' => $tramos
  ]);
}

/* ============================================================
   ➕ CREAR TRAMO
============================================================ */
function crearTramoGet($conn) {
  $rutas = obtenerRutas($conn);
  $tipos = obtenerTiposCarretera($conn);
  view('tramos/crear.php', [
    'titulo' => 'Registrar Tramo',
    'rutas' => $rutas,
    'tipos' => $tipos,
    'errores' => [],
    'old' => []
  ]);
}

function crearTramoPost($conn) {
  $data = $_POST;
  $errores = [];

  if (empty($data['id_ruta'])) $errores['id_ruta'] = 'Seleccione una ruta.';
  if (empty($data['orden_tramo'])) $errores['orden_tramo'] = 'Debe indicar el orden del tramo.';
  if (empty($data['punto_inicio'])) $errores['punto_inicio'] = 'Ingrese punto de inicio.';
  if (empty($data['punto_fin'])) $errores['punto_fin'] = 'Ingrese punto final.';

  if ($errores) {
    $rutas = obtenerRutas($conn);
    $tipos = obtenerTiposCarretera($conn);
    view('tramos/crear.php', [
      'titulo' => 'Registrar Tramo',
      'rutas' => $rutas,
      'tipos' => $tipos,
      'errores' => $errores,
      'old' => $data
    ]);
    return;
  }

  try {
    $id = TramoRuta::crear($conn, $data);
    registrarAccion($conn, 'admin@logistica.com', 'Tramo_Ruta', 'INSERT', "Se creó el tramo #$id en la ruta {$data['id_ruta']}.");
    redirect('/logistica_global/controllers/tramoController.php?accion=listar&success=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear tramo: ' . $e->getMessage();
    $rutas = obtenerRutas($conn);
    $tipos = obtenerTiposCarretera($conn);
    view('tramos/crear.php', [
      'titulo' => 'Registrar Tramo',
      'rutas' => $rutas,
      'tipos' => $tipos,
      'errores' => $errores,
      'old' => $data
    ]);
  }
}

/* ============================================================
   ✏️ EDITAR TRAMO
============================================================ */
function editarTramoGet($conn) {
  $id = $_GET['id'] ?? 0;
  $tramo = TramoRuta::obtenerPorId($conn, $id);
  if (!$tramo)
    redirect('/logistica_global/controllers/tramoController.php?accion=listar');

  $rutas = obtenerRutas($conn);
  $tipos = obtenerTiposCarretera($conn);
  view('tramos/editar.php', [
    'titulo' => 'Editar Tramo',
    'tramo' => $tramo,
    'rutas' => $rutas,
    'tipos' => $tipos,
    'errores' => []
  ]);
}

function editarTramoPost($conn) {
  $id = $_GET['id'] ?? 0;
  $data = $_POST;

  try {
    TramoRuta::actualizar($conn, $id, $data);
    registrarAccion($conn, 'admin@logistica.com', 'Tramo_Ruta', 'UPDATE', "Se actualizó el tramo #$id.");
    redirect('/logistica_global/controllers/tramoController.php?accion=listar&updated=1');
  } catch (Throwable $e) {
    $rutas = obtenerRutas($conn);
    $tipos = obtenerTiposCarretera($conn);
    $errores['general'] = 'Error al actualizar: ' . $e->getMessage();
    view('tramos/editar.php', [
      'titulo' => 'Editar Tramo',
      'tramo' => $data,
      'rutas' => $rutas,
      'tipos' => $tipos,
      'errores' => $errores
    ]);
  }
}

/* ============================================================
   🗑️ ELIMINAR TRAMO
============================================================ */
function eliminarTramo($conn) {
  $id = $_GET['id'] ?? 0;
  if ($id) {
    TramoRuta::eliminar($conn, (int)$id);
    registrarAccion($conn, 'admin@logistica.com', 'Tramo_Ruta', 'DELETE', "Se eliminó el tramo #$id.");
    redirect('/logistica_global/controllers/tramoController.php?accion=listar&deleted=1');
  } else {
    redirect('/logistica_global/controllers/tramoController.php?accion=listar&error=1');
  }
}

/* ============================================================
   🔗 FUNCIONES AUXILIARES
============================================================ */
function obtenerRutas($conn) {
  $sql = "SELECT id_ruta, nombre_ruta FROM Ruta WHERE estado='Activa'";
  $stmt = sqlsrv_query($conn, $sql);
  $r = [];
  if ($stmt) while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $r[] = $row;
  return $r;
}

function obtenerTiposCarretera($conn) {
  $sql = "SELECT id_tipo_carretera, nombre FROM Tipo_Carretera";
  $stmt = sqlsrv_query($conn, $sql);
  $t = [];
  if ($stmt) while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $t[] = $row;
  return $t;
}
