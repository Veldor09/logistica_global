<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/TipoMercancia.php';
require_once $BASE_PATH . '/common/auditoria.php';


/* ============================================================
   🔁 Redirección y vista (usa el mismo layout global)
============================================================ */
function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

function redirect($url) {
  header("Location: $url");
  exit;
}

/* ============================================================
   🔀 Controlador principal
============================================================ */
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
  case 'listar':
    $mercancias = TipoMercancia::obtenerTodos($conn);
    view('mercancias/listar.php', [
      'titulo' => 'Gestión de Tipos de Mercancía',
      'mercancias' => $mercancias
    ]);
    break;

  case 'crear':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      TipoMercancia::crear($conn, $_POST);
      registrarAccion($conn, 'admin@logistica.com', 'Tipo_Mercancia', 'INSERT', 'Se registró un nuevo tipo de mercancía.');
      redirect('/logistica_global/controllers/mercanciaController.php?accion=listar');
    }
    view('mercancias/crear.php', ['titulo' => 'Registrar Tipo de Mercancía']);
    break;

  case 'editar':
    $id = $_GET['id'] ?? 0;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      TipoMercancia::actualizar($conn, $id, $_POST);
      registrarAccion($conn, 'admin@logistica.com', 'Tipo_Mercancia', 'UPDATE', "Se actualizó la mercancía #$id.");
      redirect('/logistica_global/controllers/mercanciaController.php?accion=listar');
    }
    $mercancia = TipoMercancia::obtenerPorId($conn, $id);
    view('mercancias/editar.php', [
      'titulo' => 'Editar Tipo de Mercancía',
      'mercancia' => $mercancia
    ]);
    break;

  case 'eliminar':
    $id = $_GET['id'] ?? 0;
    if ($id) {
      TipoMercancia::eliminar($conn, $id);
      registrarAccion($conn, 'admin@logistica.com', 'Tipo_Mercancia', 'DELETE', "Se eliminó la mercancía #$id.");
    }
    redirect('/logistica_global/controllers/mercanciaController.php?accion=listar');
    break;

  default:
    $mercancias = TipoMercancia::obtenerTodos($conn);
    view('mercancias/listar.php', [
      'titulo' => 'Gestión de Tipos de Mercancía',
      'mercancias' => $mercancias
    ]);
    break;
}
?>
