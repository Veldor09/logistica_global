<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';

require_once $BASE_PATH . '/models/RegistroAccion.php';

function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

function redirect($path) {
  header("Location: $path");
  exit;
}

$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
  case 'listar':
    listarAuditoria($conn);
    break;
  default:
    listarAuditoria($conn);
    break;
}

/* ============================================================
   📜 LISTAR REGISTROS DE AUDITORÍA
============================================================ */
function listarAuditoria($conn) {
  $filtros = [
    'usuario' => $_GET['usuario'] ?? '',
    'rol' => $_GET['rol'] ?? '',
    'modulo' => $_GET['modulo'] ?? '',
    'accion' => $_GET['accionFiltro'] ?? ''
  ];

  $where = [];
  $params = [];

  if ($filtros['usuario']) {
    $where[] = "usuario LIKE ?";
    $params[] = '%' . $filtros['usuario'] . '%';
  }
  if ($filtros['rol']) {
    $where[] = "rol LIKE ?";
    $params[] = '%' . $filtros['rol'] . '%';
  }
  if ($filtros['modulo']) {
    $where[] = "modulo LIKE ?";
    $params[] = '%' . $filtros['modulo'] . '%';
  }
  if ($filtros['accion']) {
    $where[] = "accion = ?";
    $params[] = strtoupper($filtros['accion']);
  }

  $whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";
  $sql = "SELECT TOP 200 * FROM Registro_Accion $whereSql ORDER BY fecha DESC";

  $stmt = sqlsrv_query($conn, $sql, $params);
  if (!$stmt) die(print_r(sqlsrv_errors(), true));

  $registros = [];
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['fecha'] instanceof DateTime) {
      $row['fecha'] = $row['fecha']->format('Y-m-d H:i:s');
    }
    $registros[] = $row;
  }

  view('auditoria/listar.php', [
    'titulo' => 'Auditoría del Sistema',
    'registros' => $registros,
    'filtros' => $filtros
  ]);
}
?>
