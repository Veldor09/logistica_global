<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Mercancia.php';

$accion = $_GET['accion'] ?? 'listar';

if ($accion === 'crear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $costo_unitario = $_POST['costo_unitario'];
    $restricciones = $_POST['restricciones'];

    Mercancia::crearTipo($conn, $nombre, $descripcion, $costo_unitario, $restricciones);
    header("Location: mercanciaController.php");
    exit;
}

$tipos = Mercancia::obtenerTipos($conn);
include __DIR__ . '/../views/mercancias/listar.php';
?>
