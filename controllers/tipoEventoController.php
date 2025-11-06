<?php
// ============================================================
// ⚙️ CONTROLADOR: tipoEventoController.php
// Gestión de Tipos de Evento (crear, editar, eliminar, listar)
// ============================================================

ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/models/TipoEvento.php';
require_once $BASE_PATH . '/common/auditoria.php'; // 🧾 Auditoría global
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';

/* ============================================================
   📄 FUNCIÓN VIEW CON LAYOUT GLOBAL
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
   🔁 FUNCIÓN DE REDIRECCIÓN
============================================================ */
function redirect($path) {
  header("Location: $path");
  exit;
}

/* ============================================================
   🚦 ENRUTADOR PRINCIPAL
============================================================ */
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
  case 'listar':
    listarTipos($conn);
    break;

  case 'crear':
    ($_SERVER['REQUEST_METHOD'] === 'POST')
      ? crearTipoPost($conn)
      : crearTipoGet($conn);
    break;

  case 'editar':
    ($_SERVER['REQUEST_METHOD'] === 'POST')
      ? editarTipoPost($conn)
      : editarTipoGet($conn);
    break;

  case 'eliminar':
    eliminarTipo($conn);
    break;

  default:
    listarTipos($conn);
    break;
}

/* ============================================================
   📋 LISTAR TIPOS DE EVENTO
============================================================ */
function listarTipos($conn) {
  try {
    $tipos = TipoEvento::obtenerTodos($conn);
    view('tiposevento/listar.php', [
      'titulo' => 'Gestión de Tipos de Evento',
      'tipos'  => $tipos
    ]);
  } catch (Throwable $e) {
    die("<pre>Error al listar tipos de evento:\n{$e->getMessage()}</pre>");
  }
}

/* ============================================================
   ➕ CREAR NUEVO TIPO DE EVENTO
============================================================ */
function crearTipoGet($conn) {
  view('tiposevento/crear.php', [
    'titulo'  => 'Registrar Tipo de Evento',
    'errores' => [],
    'old'     => []
  ]);
}

function crearTipoPost($conn) {
  $data = $_POST;
  $errores = [];

  // 🧩 Validaciones básicas
  if (empty(trim($data['nombre'] ?? ''))) {
    $errores['nombre'] = 'Debe ingresar un nombre.';
  }

  if (!empty($errores)) {
    view('tiposevento/crear.php', [
      'titulo'  => 'Registrar Tipo de Evento',
      'errores' => $errores,
      'old'     => $data
    ]);
    return;
  }

  try {
    // Crear registro
    $id = TipoEvento::crear($conn, $data);

    // Registrar auditoría (✅ ahora con 6 argumentos)
    registrarAccion(
      $conn,
      'admin@logistica.com',
      'Tipo_Evento',
      'INSERT',
      "Se creó el tipo de evento #$id ({$data['nombre']}).",
      $id
    );

    redirect('/logistica_global/controllers/tipoEventoController.php?accion=listar&success=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear: ' . $e->getMessage();
    view('tiposevento/crear.php', [
      'titulo'  => 'Registrar Tipo de Evento',
      'errores' => $errores,
      'old'     => $data
    ]);
  }
}

/* ============================================================
   ✏️ EDITAR TIPO DE EVENTO
============================================================ */
function editarTipoGet($conn) {
  $id = $_GET['id'] ?? 0;
  $tipo = TipoEvento::obtenerPorId($conn, $id);

  if (!$tipo) {
    redirect('/logistica_global/controllers/tipoEventoController.php?accion=listar');
    return;
  }

  view('tiposevento/editar.php', [
    'titulo'  => 'Editar Tipo de Evento',
    'tipo'    => $tipo,
    'errores' => []
  ]);
}

function editarTipoPost($conn) {
  $id = $_GET['id'] ?? 0;

  try {
    TipoEvento::actualizar($conn, $id, $_POST);

    // ✅ corregido con 6 parámetros
    registrarAccion(
      $conn,
      'admin@logistica.com',
      'Tipo_Evento',
      'UPDATE',
      "Se actualizó el tipo de evento #$id.",
      $id
    );

    redirect('/logistica_global/controllers/tipoEventoController.php?accion=listar&updated=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al actualizar: ' . $e->getMessage();
    view('tiposevento/editar.php', [
      'titulo'  => 'Editar Tipo de Evento',
      'tipo'    => $_POST,
      'errores' => $errores
    ]);
  }
}

/* ============================================================
   🗑️ ELIMINAR TIPO DE EVENTO
============================================================ */
function eliminarTipo($conn) {
  $id = $_GET['id'] ?? 0;

  if ($id) {
    try {
      TipoEvento::eliminar($conn, (int)$id);

      // ✅ corregido con 6 parámetros
      registrarAccion(
        $conn,
        'admin@logistica.com',
        'Tipo_Evento',
        'DELETE',
        "Se eliminó el tipo de evento #$id.",
        $id
      );

    } catch (Throwable $e) {
      die("<pre>Error al eliminar tipo de evento:\n{$e->getMessage()}</pre>");
    }
  }

  redirect('/logistica_global/controllers/tipoEventoController.php?accion=listar&deleted=1');
}
?>
