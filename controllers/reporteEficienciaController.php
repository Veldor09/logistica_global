<?php
// ============================================================
// 📂 controllers/reporteEficienciaController.php
// Gestión de reportes de eficiencia vehicular
// ============================================================

session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/ReporteEficiencia.php';
require_once $BASE_PATH . '/models/Viaje.php';
require_once $BASE_PATH . '/models/OrdenViaje.php';

// ============================================================
// 🔧 Helper de vistas
// ============================================================
function view($ruta, $data = [])
{
    extract($data);
    $BASE_PATH = dirname(__DIR__);
    ob_start();
    include $BASE_PATH . "/views/$ruta";
    $contenido = ob_get_clean();
    include $BASE_PATH . '/views/layout.php';
}

function redirect($path)
{
    header("Location: $path");
    exit;
}

// ============================================================
// 🧭 Router
// ============================================================
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'listar': listarReportes($conn); break;
    case 'generar':
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? generarReportePost($conn) : generarReporteGet($conn);
        break;
    case 'detalle': verDetalle($conn); break;
    case 'eliminar': eliminarReporte($conn); break;
    default: listarReportes($conn); break;
}

// ============================================================
// 📋 LISTAR REPORTES
// ============================================================
function listarReportes($conn)
{
    $reportes = ReporteEficiencia::obtenerTodos($conn);
    view('reportes/listar.php', [
        'titulo' => 'Reportes de Eficiencia',
        'reportes' => $reportes
    ]);
}

// ============================================================
// ➕ GENERAR NUEVO REPORTE
// ============================================================
function generarReporteGet($conn)
{
    // Trae todos los viajes con sus datos asociados
    $viajes = Viaje::obtenerTodos($conn);
    view('reportes/generar.php', [
        'titulo' => 'Generar Reporte de Eficiencia',
        'viajes' => $viajes,
        'errores' => []
    ]);
}

function generarReportePost($conn)
{
    $id_viaje = $_POST['id_viaje'] ?? '';
    if (!$id_viaje) {
        $errores['id_viaje'] = 'Selecciona un viaje.';
        generarReporteGet($conn);
        return;
    }

    try {
        ReporteEficiencia::generar($conn, $id_viaje);
        redirect('/logistica_global/controllers/reporteEficienciaController.php?accion=listar');
    } catch (Throwable $e) {
        $errores['general'] = 'Error al generar: ' . $e->getMessage();
        view('reportes/generar.php', [
            'titulo' => 'Generar Reporte de Eficiencia',
            'errores' => $errores,
            'viajes' => Viaje::obtenerTodos($conn)
        ]);
    }
}

// ============================================================
// 🔍 DETALLE DEL REPORTE
// ============================================================
function verDetalle($conn)
{
    $id = $_GET['id'] ?? 0;
    $reporte = ReporteEficiencia::obtenerPorId($conn, $id);
    if (!$reporte) {
        redirect('/logistica_global/controllers/reporteEficienciaController.php?accion=listar');
    }

    view('reportes/detalle.php', [
        'titulo' => 'Detalle de Reporte de Eficiencia',
        'r' => $reporte
    ]);
}

// ============================================================
// 🗑️ ELIMINAR
// ============================================================
function eliminarReporte($conn)
{
    $id = $_GET['id'] ?? 0;
    if ($id) ReporteEficiencia::eliminar($conn, (int)$id);
    redirect('/logistica_global/controllers/reporteEficienciaController.php?accion=listar');
}
?>
