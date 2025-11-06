<?php
// ============================================================
// 🎯 CONTROLADOR: eventoController.php
// Gestión de eventos asociados a viajes y tipos de evento
// ============================================================

ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/Evento.php';
require_once $BASE_PATH . '/models/TipoEvento.php';
require_once $BASE_PATH . '/common/auditoria.php'; // 🧾 Auditoría global

/* ============================================================
   📄 FUNCIÓN VIEW CENTRALIZADA (usa layout.php)
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
        listarEventos($conn);
        break;

    case 'crear':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? crearEventoPost($conn)
            : crearEventoGet($conn);
        break;

    case 'editar':
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? editarEventoPost($conn)
            : editarEventoGet($conn);
        break;

    case 'eliminar':
        eliminarEvento($conn);
        break;

    default:
        listarEventos($conn);
        break;
}

/* ============================================================
   📋 LISTAR EVENTOS
============================================================ */
function listarEventos($conn) {
    $eventos = Evento::obtenerTodos($conn);
    view('eventos/listar.php', [
        'titulo' => 'Gestión de Eventos en Ruta',
        'eventos' => $eventos,
    ]);
}

/* ============================================================
   ➕ CREAR EVENTO
============================================================ */
function crearEventoGet($conn) {
    $tipos = TipoEvento::obtenerTodos($conn);
    $viajes = obtenerViajes($conn);

    view('eventos/crear.php', [
        'titulo' => 'Registrar Evento',
        'tipos' => $tipos,
        'viajes' => $viajes,
        'errores' => [],
        'old' => [],
    ]);
}

function crearEventoPost($conn) {
    $data = [
        'id_viaje' => $_POST['id_viaje'] ?? '',
        'id_tipo_evento' => $_POST['id_tipo_evento'] ?? '',
        'observaciones' => $_POST['observaciones'] ?? '',
        'ubicacion' => $_POST['ubicacion'] ?? '',
        'estado' => $_POST['estado'] ?? 'Registrado'
    ];

    $errores = [];
    if (!$data['id_viaje']) $errores['id_viaje'] = 'Selecciona un viaje.';
    if (!$data['id_tipo_evento']) $errores['id_tipo_evento'] = 'Selecciona un tipo de evento.';

    if ($errores) {
        $tipos = TipoEvento::obtenerTodos($conn);
        $viajes = obtenerViajes($conn);
        view('eventos/crear.php', [
            'titulo' => 'Registrar Evento',
            'tipos' => $tipos,
            'viajes' => $viajes,
            'errores' => $errores,
            'old' => $data
        ]);
        return;
    }

    try {
        $id = Evento::crear($conn, $data);

        // 🧾 Registrar auditoría (agregando fecha actual)
        registrarAccion(
            $conn,
            'admin@logistica.com',
            'Evento',
            'INSERT',
            "Se creó el evento #$id en el viaje {$data['id_viaje']}.",
            date('Y-m-d H:i:s')
        );

        redirect('/logistica_global/controllers/eventoController.php?accion=listar&success=1');
    } catch (Throwable $e) {
        $tipos = TipoEvento::obtenerTodos($conn);
        $viajes = obtenerViajes($conn);
        $errores['general'] = 'Error al crear evento: ' . $e->getMessage();
        view('eventos/crear.php', [
            'titulo' => 'Registrar Evento',
            'tipos' => $tipos,
            'viajes' => $viajes,
            'errores' => $errores,
            'old' => $data
        ]);
    }
}

/* ============================================================
   ✏️ EDITAR EVENTO
============================================================ */
function editarEventoGet($conn) {
    $id = $_GET['id'] ?? null;
    if (!$id) redirect('/logistica_global/controllers/eventoController.php?accion=listar');

    $evento = Evento::obtenerPorId($conn, $id);
    if (!$evento) redirect('/logistica_global/controllers/eventoController.php?accion=listar');

    $tipos = TipoEvento::obtenerTodos($conn);
    $viajes = obtenerViajes($conn);

    view('eventos/editar.php', [
        'titulo' => 'Editar Evento',
        'evento' => $evento,
        'tipos' => $tipos,
        'viajes' => $viajes,
        'errores' => []
    ]);
}

function editarEventoPost($conn) {
    $id = $_GET['id'] ?? null;
    if (!$id) redirect('/logistica_global/controllers/eventoController.php?accion=listar');

    $data = [
        'id_viaje' => $_POST['id_viaje'] ?? '',
        'id_tipo_evento' => $_POST['id_tipo_evento'] ?? '',
        'fecha' => $_POST['fecha'] ?? date('Y-m-d H:i:s'),
        'observaciones' => $_POST['observaciones'] ?? '',
        'ubicacion' => $_POST['ubicacion'] ?? '',
        'estado' => $_POST['estado'] ?? 'Registrado'
    ];

    try {
        Evento::actualizar($conn, $id, $data);

        registrarAccion(
            $conn,
            'admin@logistica.com',
            'Evento',
            'UPDATE',
            "Se actualizó el evento #$id.",
            date('Y-m-d H:i:s')
        );

        redirect('/logistica_global/controllers/eventoController.php?accion=listar&updated=1');
    } catch (Throwable $e) {
        $tipos = TipoEvento::obtenerTodos($conn);
        $viajes = obtenerViajes($conn);
        $errores['general'] = 'Error al actualizar: ' . $e->getMessage();
        view('eventos/editar.php', [
            'titulo' => 'Editar Evento',
            'evento' => $data,
            'tipos' => $tipos,
            'viajes' => $viajes,
            'errores' => $errores
        ]);
    }
}

/* ============================================================
   🗑️ ELIMINAR EVENTO
============================================================ */
function eliminarEvento($conn) {
    $id = $_GET['id'] ?? 0;
    if ($id) {
        Evento::eliminar($conn, (int)$id);

        registrarAccion(
            $conn,
            'admin@logistica.com',
            'Evento',
            'DELETE',
            "Se eliminó el evento #$id.",
            date('Y-m-d H:i:s')
        );

        redirect('/logistica_global/controllers/eventoController.php?accion=listar&deleted=1');
    } else {
        redirect('/logistica_global/controllers/eventoController.php?accion=listar&error=1');
    }
}

/* ============================================================
   🔗 OBTENER VIAJES DISPONIBLES
============================================================ */
function obtenerViajes($conn) {
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
