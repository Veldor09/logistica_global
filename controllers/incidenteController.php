<?php
// ============================================================
// 🚨 CONTROLADOR: incidenteController.php
// Gestión de incidentes registrados durante los viajes
// ============================================================

ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/Incidente.php';
require_once $BASE_PATH . '/common/auditoria.php'; // 🧾 Auditoría global

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
   🚦 ENRUTADOR PRINCIPAL
============================================================ */
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'listar':
        listarIncidentes($conn);
        break;
    case 'crear':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? crearIncidentePost($conn)
            : crearIncidenteGet($conn);
        break;
    case 'editar':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? editarIncidentePost($conn)
            : editarIncidenteGet($conn);
        break;
    case 'eliminar':
        eliminarIncidente($conn);
        break;
    default:
        listarIncidentes($conn);
        break;
}

/* ============================================================
   📋 LISTAR INCIDENTES
============================================================ */
function listarIncidentes($conn) {
    try {
        $incidentes = Incidente::obtenerTodos($conn);
        view('incidentes/listar.php', [
            'titulo' => 'Gestión de Incidentes en Ruta',
            'incidentes' => $incidentes
        ]);
    } catch (Throwable $e) {
        die("<pre>Error al listar incidentes:\n{$e->getMessage()}</pre>");
    }
}

/* ============================================================
   ➕ CREAR INCIDENTE
============================================================ */
function crearIncidenteGet($conn) {
    $viajes = obtenerViajes($conn);
    view('incidentes/crear.php', [
        'titulo' => 'Registrar Incidente',
        'viajes' => $viajes,
        'errores' => [],
        'old' => []
    ]);
}

function crearIncidentePost($conn) {
    $data = [
        'id_viaje' => $_POST['id_viaje'] ?? '',
        'tipo_incidente' => trim($_POST['tipo_incidente'] ?? ''),
        'gravedad' => $_POST['gravedad'] ?? '',
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'estado' => $_POST['estado'] ?? 'Abierto'
    ];

    $errores = [];
    if (!$data['id_viaje']) $errores['id_viaje'] = 'Selecciona un viaje.';
    if ($data['tipo_incidente'] === '') $errores['tipo_incidente'] = 'Indica el tipo de incidente.';
    if ($data['gravedad'] === '') $errores['gravedad'] = 'Selecciona la gravedad.';

    if ($errores) {
        $viajes = obtenerViajes($conn);
        view('incidentes/crear.php', [
            'titulo' => 'Registrar Incidente',
            'viajes' => $viajes,
            'errores' => $errores,
            'old' => $data
        ]);
        return;
    }

    try {
        $id = Incidente::crear($conn, $data);
        registrarAccion(
            $conn,
            'admin@logistica.com',
            'Incidente',
            'INSERT',
            "Se registró el incidente #$id en el viaje {$data['id_viaje']}.",
            date('Y-m-d H:i:s') // 👈 sexto parámetro agregado
        );
        redirect('/logistica_global/controllers/incidenteController.php?accion=listar&success=1');
    } catch (Throwable $e) {
        $viajes = obtenerViajes($conn);
        $errores['general'] = 'Error al crear incidente: ' . $e->getMessage();
        view('incidentes/crear.php', [
            'titulo' => 'Registrar Incidente',
            'viajes' => $viajes,
            'errores' => $errores,
            'old' => $data
        ]);
    }
}

/* ============================================================
   ✏️ EDITAR INCIDENTE
============================================================ */
function editarIncidenteGet($conn) {
    $id = (int)($_GET['id'] ?? 0);
    $incidente = Incidente::obtenerPorId($conn, $id);
    if (!$incidente) {
        redirect('/logistica_global/controllers/incidenteController.php?accion=listar');
        return;
    }

    $viajes = obtenerViajes($conn);
    view('incidentes/editar.php', [
        'titulo' => 'Editar Incidente',
        'incidente' => $incidente,
        'viajes' => $viajes,
        'errores' => []
    ]);
}

function editarIncidentePost($conn) {
    $id = (int)($_GET['id'] ?? 0);
    $data = [
        'id_viaje' => $_POST['id_viaje'] ?? '',
        'tipo_incidente' => trim($_POST['tipo_incidente'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'gravedad' => $_POST['gravedad'] ?? '',
        'estado' => $_POST['estado'] ?? 'Abierto'
    ];

    try {
        Incidente::actualizar($conn, $id, $data);
        registrarAccion(
            $conn,
            'admin@logistica.com',
            'Incidente',
            'UPDATE',
            "Se actualizó el incidente #$id.",
            date('Y-m-d H:i:s') // 👈 sexto parámetro agregado
        );
        redirect('/logistica_global/controllers/incidenteController.php?accion=listar&updated=1');
    } catch (Throwable $e) {
        $viajes = obtenerViajes($conn);
        $errores['general'] = 'Error al actualizar: ' . $e->getMessage();
        view('incidentes/editar.php', [
            'titulo' => 'Editar Incidente',
            'incidente' => $data,
            'viajes' => $viajes,
            'errores' => $errores
        ]);
    }
}

/* ============================================================
   🗑️ ELIMINAR INCIDENTE
============================================================ */
function eliminarIncidente($conn) {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        try {
            Incidente::eliminar($conn, $id);
            registrarAccion(
                $conn,
                'admin@logistica.com',
                'Incidente',
                'DELETE',
                "Se eliminó el incidente #$id.",
                date('Y-m-d H:i:s') // 👈 sexto parámetro agregado
            );
            redirect('/logistica_global/controllers/incidenteController.php?accion=listar&deleted=1');
        } catch (Throwable $e) {
            die("<pre>Error al eliminar incidente:\n{$e->getMessage()}</pre>");
        }
    } else {
        redirect('/logistica_global/controllers/incidenteController.php?accion=listar&error=1');
    }
}

/* ============================================================
   🔗 UTILIDAD: OBTENER VIAJES DISPONIBLES
============================================================ */
function obtenerViajes($conn): array {
    $viajes = [];
    $sql = "SELECT id_viaje FROM Viaje ORDER BY id_viaje DESC";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt) {
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $viajes[] = $r;
        }
    }
    return $viajes;
}
