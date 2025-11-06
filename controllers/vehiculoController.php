<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);

// 🔧 Conexión primero
require_once $BASE_PATH . '/config/db.php';

// 🧱 Modelos
require_once $BASE_PATH . '/models/Vehiculo.php';
require_once $BASE_PATH . '/models/TipoCamion.php';

// 🔒 Autenticación
require_once $BASE_PATH . '/config/auth_guard.php';

/* ===========================================================
   🌐 Función para renderizar vistas con layout global
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
   🔁 Redirección limpia
=========================================================== */
function redirect($path) {
    header("Location: $path");
    exit;
}

/* ===========================================================
   🔀 Controlador principal
=========================================================== */

// 🔒 Valor defensivo de $accion
$accion = isset($_GET['accion']) && $_GET['accion'] !== '' ? $_GET['accion'] : 'listar';

switch ($accion) {
    case 'listar':
        listarVehiculos($conn);
        break;

    case 'crear':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? crearVehiculoPost($conn)
            : crearVehiculoGet($conn);
        break;

    case 'editar':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? editarVehiculoPost($conn)
            : editarVehiculoGet($conn);
        break;

    case 'eliminar':
        eliminarVehiculo($conn);
        break;

    default:
        listarVehiculos($conn); // ✅ fallback garantizado
        break;
}

/* ===========================================================
   📋 Listar vehículos
=========================================================== */
function listarVehiculos($conn) {
    try {
        $vehiculos = Vehiculo::obtenerTodos($conn);
        view('vehiculos/listar.php', [
            'titulo' => 'Gestión de Vehículos',
            'vehiculos' => $vehiculos
        ]);
    } catch (Throwable $e) {
        echo "<pre>❌ Error al listar vehículos:\n" . $e->getMessage() . "</pre>";
    }
}

/* ===========================================================
   ➕ Crear vehículo (GET)
=========================================================== */
function crearVehiculoGet($conn) {
    $tipos = TipoCamion::obtenerTodos($conn);
    view('vehiculos/crear.php', [
        'titulo' => 'Registrar Vehículo',
        'tipos' => $tipos
    ]);
}

/* ===========================================================
   ➕ Crear vehículo (POST)
=========================================================== */
function crearVehiculoPost($conn) {
    try {
        Vehiculo::crear($conn, $_POST);
        redirect('/logistica_global/controllers/vehiculoController.php?accion=listar');
    } catch (Throwable $e) {
        $tipos = TipoCamion::obtenerTodos($conn);
        $errores['general'] = $e->getMessage();
        view('vehiculos/crear.php', [
            'titulo' => 'Registrar Vehículo',
            'tipos' => $tipos,
            'errores' => $errores
        ]);
    }
}

/* ===========================================================
   ✏️ Editar vehículo
=========================================================== */
function editarVehiculoGet($conn) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // 🚫 Si no hay ID o no existe el vehículo, volver a lista
    if ($id <= 0) {
        redirect('/logistica_global/controllers/vehiculoController.php?accion=listar');
        return;
    }

    $vehiculo = Vehiculo::obtenerPorId($conn, $id);
    if (!$vehiculo) {
        redirect('/logistica_global/controllers/vehiculoController.php?accion=listar');
        return;
    }

    $tipos = TipoCamion::obtenerTodos($conn);
    view('vehiculos/editar.php', [
        'titulo' => 'Editar Vehículo',
        'vehiculo' => $vehiculo,
        'tipos' => $tipos
    ]);
}

function editarVehiculoPost($conn) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        redirect('/logistica_global/controllers/vehiculoController.php?accion=listar');
        return;
    }

    try {
        Vehiculo::actualizar($conn, $id, $_POST);
        redirect('/logistica_global/controllers/vehiculoController.php?accion=listar');
    } catch (Throwable $e) {
        $vehiculo = $_POST;
        $tipos = TipoCamion::obtenerTodos($conn);
        $errores['general'] = $e->getMessage();
        view('vehiculos/editar.php', [
            'titulo' => 'Editar Vehículo',
            'vehiculo' => $vehiculo,
            'tipos' => $tipos,
            'errores' => $errores
        ]);
    }
}

/* ===========================================================
   🗑️ Eliminar vehículo
=========================================================== */
function eliminarVehiculo($conn) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id > 0) {
        try {
            Vehiculo::eliminar($conn, $id);
        } catch (Throwable $e) {
            die("<pre>Error al eliminar vehículo:\n{$e->getMessage()}</pre>");
        }
    }
    redirect('/logistica_global/controllers/vehiculoController.php?accion=listar');
}
?>
