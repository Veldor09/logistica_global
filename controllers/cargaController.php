<?php
// ============================================================
// 📦 controllers/cargaController.php
// Muestra resumen de carga total (peso y volumen) por viaje
// ============================================================

session_start();
ini_set('display_errors', '1'); error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/models/Carga.php';
require_once $BASE_PATH . '/config/auth_guard.php';

function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

function redirect($u) { header("Location: $u"); exit; }

$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
  case 'listar':
  default:
    listar($conn);
    break;
}

function listar($conn) {
  try {
    // ✅ Usa directamente el modelo Carga que calcula peso y volumen
    $viajes = Carga::obtenerTodas($conn);
  } catch (Exception $e) {
    die('Error al obtener cargas: ' . $e->getMessage());
  }

  view('cargas/listar.php', [
    'titulo' => 'Gestión de Cargas',
    'viajes' => $viajes
  ]);
}
