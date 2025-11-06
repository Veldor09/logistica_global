<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/models/TipoCarretera.php';
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
    listarTipos($conn);
    break;
  case 'crear':
    ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearTipoPost($conn) : crearTipoGet($conn);
    break;
  case 'editar':
    ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarTipoPost($conn) : editarTipoGet($conn);
    break;
  case 'eliminar':
    eliminarTipo($conn);
    break;
  default:
    listarTipos($conn);
    break;
}

/* ============================================================
   📋 LISTAR TIPOS DE CARRETERA
============================================================ */
function listarTipos($conn) {
  $tipos = TipoCarretera::obtenerTodos($conn);
  view('tiposcarretera/listar.php', [
    'titulo' => 'Gestión de Tipos de Carretera',
    'tipos' => $tipos
  ]);
}

/* ============================================================
   ➕ CREAR TIPO
============================================================ */
function crearTipoGet($conn) {
  view('tiposcarretera/crear.php', [
    'titulo' => 'Registrar Tipo de Carretera',
    'errores' => [],
    'old' => []
  ]);
}

function crearTipoPost($conn) {
  $data = $_POST;
  $errores = [];

  if (empty(trim($data['nombre'] ?? '')))
    $errores['nombre'] = 'Debe ingresar un nombre.';

  if ($errores) {
    view('tiposcarretera/crear.php', [
      'titulo' => 'Registrar Tipo de Carretera',
      'errores' => $errores,
      'old' => $data
    ]);
    return;
  }

  try {
    $id = TipoCarretera::crear($conn, $data);
    registrarAccion($conn, 'admin@logistica.com', 'Tipo_Carretera', 'INSERT', "Se creó el tipo #$id ({$data['nombre']}).");
    redirect('/logistica_global/controllers/tipoCarreteraController.php?accion=listar&success=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear tipo: ' . $e->getMessage();
    view('tiposcarretera/crear.php', [
      'titulo' => 'Registrar Tipo de Carretera',
      'errores' => $errores,
      'old' => $data
    ]);
  }
}

/* ============================================================
   ✏️ EDITAR TIPO
============================================================ */
function editarTipoGet($conn) {
  $id = $_GET['id'] ?? 0;
  $tipo = TipoCarretera::obtenerPorId($conn, $id);

  if (!$tipo)
    redirect('/logistica_global/controllers/tipoCarreteraController.php?accion=listar');

  view('tiposcarretera/editar.php', [
    'titulo' => 'Editar Tipo de Carretera',
    'tipo' => $tipo,
    'errores' => []
  ]);
}

function editarTipoPost($conn) {
  $id = $_GET['id'] ?? 0;
  $data = $_POST;

  try {
    TipoCarretera::actualizar($conn, $id, $data);
    registrarAccion($conn, 'admin@logistica.com', 'Tipo_Carretera', 'UPDATE', "Se actualizó el tipo #$id.");
    redirect('/logistica_global/controllers/tipoCarreteraController.php?accion=listar&updated=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al actualizar: ' . $e->getMessage();
    view('tiposcarretera/editar.php', [
      'titulo' => 'Editar Tipo de Carretera',
      'tipo' => $data,
      'errores' => $errores
    ]);
  }
}

/* ============================================================
   🗑️ ELIMINAR TIPO
============================================================ */
function eliminarTipo($conn) {
  $id = $_GET['id'] ?? 0;

  if ($id) {
    TipoCarretera::eliminar($conn, (int)$id);
    registrarAccion($conn, 'admin@logistica.com', 'Tipo_Carretera', 'DELETE', "Se eliminó el tipo #$id.");
    redirect('/logistica_global/controllers/tipoCarreteraController.php?accion=listar&deleted=1');
  } else {
    redirect('/logistica_global/controllers/tipoCarreteraController.php?accion=listar&error=1');
  }
}
