<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';

require_once $BASE_PATH . '/models/Conductor.php';

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

$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'listar':
        listarConductores($conn);
        break;
    case 'crear':
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearConductorPost($conn) : crearConductorGet($conn);
        break;
    case 'editar':
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarConductorPost($conn) : editarConductorGet($conn);
        break;
    case 'eliminar':
        eliminarConductor($conn);
        break;
    default:
        listarConductores($conn);
}

/* ===========================================================
   📋 Listar
=========================================================== */
function listarConductores($conn) {
    $conductores = Conductor::obtenerTodos($conn);
    view('conductores/listar.php', [
        'titulo' => 'Gestión de Conductores',
        'conductores' => $conductores
    ]);
}

/* ===========================================================
   ➕ Crear
=========================================================== */
function crearConductorGet($conn) {
    view('conductores/crear.php', ['titulo' => 'Registrar Conductor']);
}

function crearConductorPost($conn) {
    try {
        Conductor::crear($conn, $_POST);
        redirect('/logistica_global/controllers/conductorController.php?accion=listar');
    } catch (Throwable $e) {
        $errores['general'] = $e->getMessage();
        view('conductores/crear.php', ['titulo' => 'Registrar Conductor', 'errores' => $errores]);
    }
}

/* ===========================================================
   ✏️ Editar
=========================================================== */
function editarConductorGet($conn) {
    $id = $_GET['id'] ?? 0;
    $conductor = Conductor::obtenerPorId($conn, $id);
    view('conductores/editar.php', ['titulo' => 'Editar Conductor', 'conductor' => $conductor]);
}

function editarConductorPost($conn) {
    $id = $_GET['id'] ?? 0;
    try {
        Conductor::actualizar($conn, $id, $_POST);
        redirect('/logistica_global/controllers/conductorController.php?accion=listar');
    } catch (Throwable $e) {
        $errores['general'] = $e->getMessage();
        $conductor = $_POST;
        view('conductores/editar.php', ['titulo' => 'Editar Conductor', 'conductor' => $conductor, 'errores' => $errores]);
    }
}

/* ===========================================================
   🗑️ Eliminar
=========================================================== */
function eliminarConductor($conn) {
    $id = $_GET['id'] ?? 0;
    if ($id) {
        try {
            Conductor::eliminar($conn, (int)$id);
        } catch (Throwable $e) {
            die("<pre>Error al eliminar conductor:\n{$e->getMessage()}</pre>");
        }
    }
    redirect('/logistica_global/controllers/conductorController.php?accion=listar');
}
?>
