<?php
// ============================================================
// 📂 controllers/viajeController.php
// Gestión de Viajes + asociación múltiple de Órdenes por Ruta
// ============================================================

session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/models/Viaje.php';
require_once $BASE_PATH . '/models/Orden.php';
require_once $BASE_PATH . '/models/OrdenViaje.php';
require_once $BASE_PATH . '/models/Ruta.php';
require_once $BASE_PATH . '/models/Conductor.php';
require_once $BASE_PATH . '/models/Vehiculo.php';
require_once $BASE_PATH . '/models/Carga.php'; // 🔹 agregado para cálculo correcto de volumen
require_once $BASE_PATH . '/config/auth_guard.php';

// ------------------------------------------------------------
// Helpers
// ------------------------------------------------------------
function view($ruta, $data = [])
{
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

function redirect($url)
{
  header("Location: $url");
  exit;
}

// ------------------------------------------------------------
// Router principal
// ------------------------------------------------------------
$accion = $_GET['accion'] ?? 'listar';
switch ($accion) {
  case 'listar':   listarViajes($conn); break;
  case 'crear':    ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearViajePost($conn) : crearViajeGet($conn); break;
  case 'editar':   ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarViajePost($conn) : editarViajeGet($conn); break;
  case 'detallar': verDetalleViaje($conn); break;
  case 'eliminar': eliminarViaje($conn); break;
  default:         listarViajes($conn); break;
}

// ------------------------------------------------------------
// Listado general
// ------------------------------------------------------------
function listarViajes($conn)
{
  $viajes = Viaje::obtenerTodos($conn);

  // Adjuntar lista de órdenes
  foreach ($viajes as &$v) {
    $ids = OrdenViaje::obtenerIdsPorViaje($conn, (int)$v['id_viaje']);
    $v['ordenes_txt'] = empty($ids) ? '-' : ('#' . implode(', #', $ids));
  }

  view('viajes/listar.php', [
    'titulo' => 'Gestión de Viajes',
    'viajes' => $viajes
  ]);
}

// ------------------------------------------------------------
// Crear (GET)
// ------------------------------------------------------------
function crearViajeGet($conn)
{
  $id_ruta = isset($_GET['id_ruta']) ? (int)$_GET['id_ruta'] : 0;
  $rutas = Ruta::obtenerActivas($conn);
  $conductores = Conductor::obtenerActivos($conn);
  $vehiculos = Vehiculo::obtenerActivos($conn);

  $ordenes = [];
  if ($id_ruta > 0) {
    $ruta = Ruta::obtenerPorId($conn, $id_ruta);
    if ($ruta) {
      $ordenes = Orden::obtenerPorOrigenDestinoYEstado($conn, $ruta['origen'], $ruta['destino'], 'Programada');
    }
  }

  view('viajes/crear.php', [
    'titulo'      => 'Registrar Viaje',
    'rutas'       => $rutas,
    'conductores' => $conductores,
    'vehiculos'   => $vehiculos,
    'ordenes'     => $ordenes,
    'id_ruta'     => $id_ruta
  ]);
}

// ------------------------------------------------------------
// Crear (POST)
// ------------------------------------------------------------
function crearViajePost($conn)
{
  $data = [
    'id_conductor' => (int)($_POST['id_conductor'] ?? 0),
    'id_vehiculo'  => (int)($_POST['id_vehiculo'] ?? 0),
    'id_ruta'      => (int)($_POST['id_ruta'] ?? 0),
    'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
    'fecha_fin'    => $_POST['fecha_fin'] ?? null,
    'kilometros_recorridos'    => $_POST['kilometros_recorridos'] ?? null,
    'combustible_usado_litros' => $_POST['combustible_usado_litros'] ?? null,
    'observaciones' => $_POST['observaciones'] ?? null,
    'estado'        => $_POST['estado'] ?? 'Pendiente',
  ];

  $id_viaje = Viaje::crear($conn, $data);

  $ids_orden = isset($_POST['ordenes']) && is_array($_POST['ordenes']) ? array_map('intval', $_POST['ordenes']) : [];
  OrdenViaje::vincularOrdenes($conn, $id_viaje, $ids_orden);

  redirect('/logistica_global/controllers/viajeController.php?accion=listar');
}

// ------------------------------------------------------------
// Detalle del viaje
// ------------------------------------------------------------
function verDetalleViaje($conn)
{
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) redirect('/logistica_global/controllers/viajeController.php?accion=listar');

  $viaje = Viaje::obtenerPorId($conn, $id);
  if (!$viaje) redirect('/logistica_global/controllers/viajeController.php?accion=listar');

  $ordenes_ids = OrdenViaje::obtenerIdsPorViaje($conn, $id);

  // 🧮 Obtener datos reales desde Carga (ya suma volúmenes correctamente)
  $detalles = Carga::obtenerPorViaje($conn, $id);
  $resumen = [
    'total_ordenes' => count($detalles),
    'peso_total_kg' => array_sum(array_map(fn($d) => (float)$d['peso_estimado_kg'], $detalles)),
    'volumen_total_m3' => array_sum(array_map(fn($d) => (float)$d['volumen_total_m3'], $detalles)),
  ];

  view('viajes/detalle.php', [
    'titulo'  => 'Detalle de Viaje',
    'viaje'   => $viaje,
    'ordenes' => $ordenes_ids,
    'resumen' => $resumen
  ]);
}

// ------------------------------------------------------------
// Editar (GET)
// ------------------------------------------------------------
function editarViajeGet($conn)
{
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) redirect('/logistica_global/controllers/viajeController.php?accion=listar');

  $viaje = Viaje::obtenerPorId($conn, $id);
  if (!$viaje) redirect('/logistica_global/controllers/viajeController.php?accion=listar');

  $rutas       = Ruta::obtenerActivas($conn);
  $conductores = Conductor::obtenerActivos($conn);
  $vehiculos   = Vehiculo::obtenerActivos($conn);

  $ordenes = [];
  if (!empty($viaje['id_ruta'])) {
    $ruta = Ruta::obtenerPorId($conn, $viaje['id_ruta']);
    if ($ruta) {
      $ordenes = Orden::obtenerParaEdicionDeViaje($conn, $ruta['origen'], $ruta['destino'], $id);
    }
  }

  $ordenesAsociadas = OrdenViaje::obtenerIdsPorViaje($conn, $id);
  $viaje['ordenes_asociadas'] = $ordenesAsociadas;

  view('viajes/editar.php', [
    'titulo'      => 'Editar Viaje',
    'viaje'       => $viaje,
    'rutas'       => $rutas,
    'conductores' => $conductores,
    'vehiculos'   => $vehiculos,
    'ordenes'     => $ordenes
  ]);
}

// ------------------------------------------------------------
// Editar (POST)
// ------------------------------------------------------------
function editarViajePost($conn)
{
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) redirect('/logistica_global/controllers/viajeController.php?accion=listar');

  $data = [
    'id_conductor' => (int)($_POST['id_conductor'] ?? 0),
    'id_vehiculo'  => (int)($_POST['id_vehiculo'] ?? 0),
    'id_ruta'      => (int)($_POST['id_ruta'] ?? 0),
    'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
    'fecha_fin'    => $_POST['fecha_fin'] ?? null,
    'kilometros_recorridos'   => $_POST['kilometros_recorridos'] ?? null,
    'combustible_usado_litros'=> $_POST['combustible_usado_litros'] ?? null,
    'observaciones'=> $_POST['observaciones'] ?? null,
    'estado'       => $_POST['estado'] ?? 'Pendiente',
  ];

  Viaje::actualizar($conn, $id, $data);

  $ids_orden = isset($_POST['ordenes']) && is_array($_POST['ordenes'])
    ? array_map('intval', $_POST['ordenes'])
    : [];
  OrdenViaje::actualizarVinculos($conn, $id, $ids_orden);

  redirect('/logistica_global/controllers/viajeController.php?accion=listar');
}

// ------------------------------------------------------------
// Eliminar
// ------------------------------------------------------------
function eliminarViaje($conn)
{
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id > 0) {
    Viaje::eliminar($conn, $id);
  }
  redirect('/logistica_global/controllers/viajeController.php?accion=listar');
}
?>
