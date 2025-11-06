<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);

/* ===========================================================
   📦 Dependencias (todas deben cargarse antes del switch)
=========================================================== */
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/Vehiculo.php';          // ✅ ahora se carga antes
require_once $BASE_PATH . '/models/TipoMantenimiento.php'; // ✅ ahora se carga antes
require_once $BASE_PATH . '/models/Mantenimiento.php';

/* ===========================================================
   🧩 Renderizado con layout
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
   🔁 Redirección
=========================================================== */
function redirect($url) {
    header("Location: $url");
    exit;
}

/* ===========================================================
   🔀 Enrutador
=========================================================== */
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'listar': listarMantenimientos($conn); break;
    case 'crear': ($_SERVER['REQUEST_METHOD'] === 'POST')
        ? crearMantenimientoPost($conn)
        : crearMantenimientoGet($conn); break;
    case 'editar': ($_SERVER['REQUEST_METHOD'] === 'POST')
        ? editarMantenimientoPost($conn)
        : editarMantenimientoGet($conn); break;
    case 'eliminar': eliminarMantenimiento($conn); break;
    default: listarMantenimientos($conn); break;
}

/* ===========================================================
   📋 Listar mantenimientos
=========================================================== */
function listarMantenimientos($conn) {
    $mantenimientos = Mantenimiento::obtenerTodos($conn);
    view('mantenimientos/listar.php', [
        'titulo' => 'Gestión de Mantenimientos',
        'mantenimientos' => $mantenimientos
    ]);
}

/* ===========================================================
   ➕ Crear mantenimiento
=========================================================== */
function crearMantenimientoGet($conn) {
    $vehiculos = Vehiculo::obtenerTodos($conn);
    $tipos = TipoMantenimiento::obtenerTodos($conn);

    view('mantenimientos/crear.php', [
        'titulo' => 'Registrar Mantenimiento',
        'vehiculos' => $vehiculos,
        'tipos' => $tipos
    ]);
}

function crearMantenimientoPost($conn) {
    try {
        Mantenimiento::insertar($conn, $_POST);
        redirect('/logistica_global/controllers/mantenimientoController.php?accion=listar');
    } catch (Throwable $e) {
        $errores['general'] = $e->getMessage();
        $vehiculos = Vehiculo::obtenerTodos($conn);
        $tipos = TipoMantenimiento::obtenerTodos($conn);
        view('mantenimientos/crear.php', [
            'titulo' => 'Registrar Mantenimiento',
            'vehiculos' => $vehiculos,
            'tipos' => $tipos,
            'errores' => $errores
        ]);
    }
}

/* ===========================================================
   ✏️ Editar mantenimiento
=========================================================== */
function editarMantenimientoGet($conn) {
    $id = $_GET['id'] ?? 0;
    $mantenimiento = Mantenimiento::obtenerPorId($conn, $id);
    view('mantenimientos/editar.php', [
        'titulo' => 'Editar Mantenimiento',
        'mantenimiento' => $mantenimiento
    ]);
}

function editarMantenimientoPost($conn) {
    $id = $_GET['id'] ?? 0;
    $_POST['id_mantenimiento'] = $id;
    try {
        Mantenimiento::actualizar($conn, $_POST);
        redirect('/logistica_global/controllers/mantenimientoController.php?accion=listar');
    } catch (Throwable $e) {
        $errores['general'] = $e->getMessage();
        $mantenimiento = $_POST;
        view('mantenimientos/editar.php', [
            'titulo' => 'Editar Mantenimiento',
            'mantenimiento' => $mantenimiento,
            'errores' => $errores
        ]);
    }
}

/* ===========================================================
   🗑️ Eliminar mantenimiento
=========================================================== */
function eliminarMantenimiento($conn) {
    $id = $_GET['id'] ?? 0;
    if ($id) {
        try {
            Mantenimiento::eliminar($conn, (int)$id);
        } catch (Throwable $e) {
            die("<pre>Error al eliminar mantenimiento:\n{$e->getMessage()}</pre>");
        }
    }
    redirect('/logistica_global/controllers/mantenimientoController.php?accion=listar');
}
?>
