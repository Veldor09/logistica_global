<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/models/Evento.php';
require_once $BASE_PATH . '/models/TipoEvento.php';

function view($ruta, $data = []) {
    extract($data);
    $BASE_PATH = dirname(__DIR__);
    include $BASE_PATH . '/includes/header.php';
    include $BASE_PATH . '/includes/sidebar.php';
    include $BASE_PATH . "/views/$ruta";
    include $BASE_PATH . '/includes/footer.php';
}

function redirect($path) {
    header("Location: $path");
    exit;
}

$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'listar': listarEventos($conn); break;
    case 'crear':
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearEventoPost($conn) : crearEventoGet($conn);
        break;
    case 'editar':
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarEventoPost($conn) : editarEventoGet($conn);
        break;
    case 'eliminar': eliminarEvento($conn); break;
    default: listarEventos($conn); break;
}

function listarEventos($conn) {
    $eventos = Evento::obtenerTodos($conn);
    view('eventos/listar.php', [
        'titulo' => 'Eventos en Ruta',
        'eventos' => $eventos,
    ]);
}

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
    $errores = [];
    $old = $_POST;
    $id_viaje = $_POST['id_viaje'] ?? '';
    $id_tipo_evento = $_POST['id_tipo_evento'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $estado = $_POST['estado'] ?? 'Registrado';

    if (!$id_viaje) $errores['id_viaje'] = 'Selecciona un viaje.';
    if (!$id_tipo_evento) $errores['id_tipo_evento'] = 'Selecciona un tipo de evento.';

    if (!empty($errores)) {
        $tipos = TipoEvento::obtenerTodos($conn);
        $viajes = obtenerViajes($conn);
        view('eventos/crear.php', compact('tipos','viajes','errores','old') + ['titulo'=>'Registrar Evento']);
        return;
    }

    try {
        Evento::crear($conn, compact('id_viaje','id_tipo_evento','observaciones','ubicacion','estado'));
        redirect('/logistica_global/controllers/eventoController.php?accion=listar');
    } catch (Throwable $e) {
        $tipos = TipoEvento::obtenerTodos($conn);
        $viajes = obtenerViajes($conn);
        $errores['general'] = 'Error al crear evento: '.$e->getMessage();
        view('eventos/crear.php', compact('tipos','viajes','errores','old') + ['titulo'=>'Registrar Evento']);
    }
}

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
        'errores' => [],
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
        redirect('/logistica_global/controllers/eventoController.php?accion=listar');
    } catch (Throwable $e) {
        $tipos = TipoEvento::obtenerTodos($conn);
        $viajes = obtenerViajes($conn);
        $errores['general'] = 'Error al actualizar: '.$e->getMessage();
        view('eventos/editar.php', compact('tipos','viajes','errores') + ['titulo'=>'Editar Evento','evento'=>$data]);
    }
}

function eliminarEvento($conn) {
    $id = $_GET['id'] ?? 0;
    if ($id) Evento::eliminar($conn, (int)$id);
    redirect('/logistica_global/controllers/eventoController.php?accion=listar');
}

function obtenerViajes($conn) {
    $viajes = [];
    $sql = "SELECT id_viaje FROM Viaje ORDER BY id_viaje DESC";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt) while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $viajes[] = $r;
    return $viajes;
}
