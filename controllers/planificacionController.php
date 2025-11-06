<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/PlanificacionCarga.php';

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
  case 'listar': listarPlanificaciones($conn); break;
  case 'crear':
    ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearPlanificacionPost($conn) : crearPlanificacionGet($conn);
    break;
  case 'editar':
    ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarPlanificacionPost($conn) : editarPlanificacionGet($conn);
    break;
  case 'eliminar': eliminarPlanificacion($conn); break;
  default: listarPlanificaciones($conn); break;
}

/* ==========================================================
   📋 Listar todas las planificaciones
========================================================== */
function listarPlanificaciones($conn) {
  $sql = "
    SELECT 
      p.id_planificacion,
      p.id_carga,
      p.id_vehiculo,
      v.placa,
      p.distribucion_porcentaje,
      p.balance_eje
    FROM Planificacion_Carga p
    INNER JOIN Vehiculo v ON p.id_vehiculo = v.id_vehiculo
    ORDER BY p.id_planificacion DESC
  ";
  $stmt = sqlsrv_query($conn, $sql);
  $planificaciones = [];
  if ($stmt) while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $planificaciones[] = $r;

  view('planificaciones/listar.php', [
    'titulo' => 'Planificación de Carga',
    'planificaciones' => $planificaciones
  ]);
}

/* ==========================================================
   ➕ Crear nueva planificación
========================================================== */
function crearPlanificacionGet($conn) {
  $vehiculos = [];
  $stmt = sqlsrv_query($conn, "SELECT id_vehiculo, placa FROM Vehiculo ORDER BY id_vehiculo DESC");
  if ($stmt) while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $vehiculos[] = $r;

  $cargas = [];
  $stmt = sqlsrv_query($conn, "SELECT id_carga FROM Carga ORDER BY id_carga DESC");
  if ($stmt) while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $cargas[] = $r;

  view('planificaciones/crear.php', [
    'titulo' => 'Registrar Planificación',
    'vehiculos' => $vehiculos,
    'cargas' => $cargas,
    'errores' => []
  ]);
}

function crearPlanificacionPost($conn) {
  $data = $_POST;
  $errores = [];

  if (empty($data['id_carga'])) $errores['id_carga'] = 'Selecciona una carga.';
  if (empty($data['id_vehiculo'])) $errores['id_vehiculo'] = 'Selecciona un vehículo.';
  if (empty($data['distribucion_porcentaje'])) $errores['distribucion_porcentaje'] = 'Indica el porcentaje.';

  if (!empty($errores)) {
    crearPlanificacionGet($conn);
    return;
  }

  try {
    PlanificacionCarga::crear($conn, $data);
    redirect('/logistica_global/controllers/planificacionController.php?accion=listar');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear: ' . $e->getMessage();
    crearPlanificacionGet($conn);
  }
}

/* ==========================================================
   ✏️ Editar planificación existente
========================================================== */
function editarPlanificacionGet($conn) {
  $id = $_GET['id'] ?? 0;
  if (!$id) redirect('/logistica_global/controllers/planificacionController.php?accion=listar');

  $sql = "SELECT * FROM Planificacion_Carga WHERE id_planificacion = ?";
  $stmt = sqlsrv_query($conn, $sql, [$id]);
  $plan = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

  $vehiculos = [];
  $stmt = sqlsrv_query($conn, "SELECT id_vehiculo, placa FROM Vehiculo ORDER BY id_vehiculo DESC");
  if ($stmt) while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $vehiculos[] = $r;

  $cargas = [];
  $stmt = sqlsrv_query($conn, "SELECT id_carga FROM Carga ORDER BY id_carga DESC");
  if ($stmt) while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $cargas[] = $r;

  view('planificaciones/editar.php', [
    'titulo' => 'Editar Planificación',
    'plan' => $plan,
    'vehiculos' => $vehiculos,
    'cargas' => $cargas,
    'errores' => []
  ]);
}

function editarPlanificacionPost($conn) {
  $id = $_GET['id'] ?? 0;
  $data = $_POST;

  $sql = "
    UPDATE Planificacion_Carga
    SET id_carga = ?, id_vehiculo = ?, distribucion_porcentaje = ?, balance_eje = ?
    WHERE id_planificacion = ?
  ";
  $params = [
    $data['id_carga'], $data['id_vehiculo'], $data['distribucion_porcentaje'], $data['balance_eje'], $id
  ];

  sqlsrv_query($conn, $sql, $params);
  redirect('/logistica_global/controllers/planificacionController.php?accion=listar');
}

/* ==========================================================
   🗑️ Eliminar planificación
========================================================== */
function eliminarPlanificacion($conn) {
  $id = $_GET['id'] ?? 0;
  if ($id) PlanificacionCarga::eliminar($conn, $id);
  redirect('/logistica_global/controllers/planificacionController.php?accion=listar');
}
