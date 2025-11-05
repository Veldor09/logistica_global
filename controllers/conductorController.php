<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Conductor.php';

$accion = $_GET['accion'] ?? 'listar';

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];

    Conductor::crear($conn, $nombre, $apellidos, $cedula, $telefono, $correo, $direccion);
    header("Location: conductorController.php");
    exit;
}

$conductores = Conductor::obtenerTodos($conn);
include __DIR__ . '/../views/conductores/listar.php';
?>
