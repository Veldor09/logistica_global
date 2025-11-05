<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Licencia.php';
require_once __DIR__ . '/../models/Conductor.php';

$accion = $_GET['accion'] ?? 'listar';

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_conductor = $_POST['id_conductor'];
    $id_tipo_licencia = $_POST['id_tipo_licencia'];
    $numero_licencia = $_POST['numero_licencia'];
    $fecha_emision = $_POST['fecha_emision'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    Licencia::crear($conn, $id_conductor, $id_tipo_licencia, $numero_licencia, $fecha_emision, $fecha_vencimiento);
    header("Location: licenciaController.php");
    exit;
}

$licencias = Licencia::obtenerTodas($conn);
$conductores = Conductor::obtenerTodos($conn);
$tipos = Licencia::obtenerTipos($conn);

include __DIR__ . '/../views/licencias/listar.php';
?>
