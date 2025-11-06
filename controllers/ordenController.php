<?php
// ============================================================
// 🧭 CONTROLADOR: OrdenController.php
// Maneja CRUD de Órdenes de Transporte
// ============================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (function_exists('ob_start')) ob_start();

require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
require_once __DIR__ . '/../models/Orden.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/TipoMercancia.php';
require_once __DIR__ . '/../models/TipoMercanciaOrden.php';

/* ============================================================
   🔁 Redirección segura
============================================================ */
function go_to($path)
{
    if (!headers_sent()) {
        header("Location: $path");
        exit;
    } else {
        echo "<meta http-equiv='refresh' content='0;url=$path'>";
        echo "<script>location.href='$path';</script>";
        exit;
    }
}

/* ============================================================
   🔀 Router principal
============================================================ */
$accion = $_GET['accion'] ?? 'listar';

try {
    switch ($accion) {

        /* --------------------------------------------------------
           ➕ CREAR ORDEN
        -------------------------------------------------------- */
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    if (empty($_POST['id_solicitud'])) {
                        throw new Exception("Debe seleccionar una solicitud válida.");
                    }

                    $data = [
                        'id_solicitud'           => (int)$_POST['id_solicitud'],
                        'direccion_origen'       => trim($_POST['direccion_origen'] ?? ''),
                        'direccion_destino'      => trim($_POST['direccion_destino'] ?? ''),
                        'peso_estimado_kg'       => ($_POST['peso_estimado_kg'] ?? '') !== '' ? (float)$_POST['peso_estimado_kg'] : null,
                        'fecha_carga'            => $_POST['fecha_carga'] ?? null,
                        'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada'] ?? null,
                        'estado'                 => 'Programada',
                        'observaciones'          => trim($_POST['observaciones'] ?? '')
                    ];

                    $idOrden = Orden::crear($conn, $data);

                    // 🔹 Asignar tipo de mercancía automáticamente por peso
try {
    $peso = (float)$data['peso_estimado_kg'];
    $tipos = TipoMercancia::obtenerTodos($conn);
    $tipoId = null;

    foreach ($tipos as $t) {
        if ($peso <= (float)$t['peso_unitario_kg']) {
            $tipoId = $t['id_tipo_mercancia'];
            break;
        }
    }

    if ($tipoId) {
        TipoMercanciaOrden::crear($conn, [
            'id_orden'          => (int)$idOrden,
            'id_tipo_mercancia' => (int)$tipoId,
            'cantidad'          => 1,
            'peso_total_kg'     => $peso,
            'volumen_m3'        => null,
        ]);
    }
} catch (Exception $autoErr) {
    error_log('[ORDEN_AUTO_MERCANCIA] ' . $autoErr->getMessage());
}


                    // Línea de mercancía opcional
                    if (!empty($_POST['id_tipo_mercancia']) && !empty($_POST['cantidad'])) {
                        TipoMercanciaOrden::crear($conn, [
                            'id_orden'          => (int)$idOrden,
                            'id_tipo_mercancia' => (int)$_POST['id_tipo_mercancia'],
                            'cantidad'          => (int)$_POST['cantidad']
                        ]);
                    }

                    go_to('ordenController.php?accion=listar&created=1');
                } catch (Exception $e) {
                    $errores['general'] = $e->getMessage();
                    $tipos = TipoMercancia::obtenerTodos($conn);
                    $solicitudes = Solicitud::obtenerDisponibles($conn);
                    ob_start();
                    include __DIR__ . '/../views/ordenes/crear.php';
                    $contenido = ob_get_clean();
                    $titulo = 'Registrar Orden';
                    include __DIR__ . '/../views/layout.php';
                }
            } else {
                $tipos = TipoMercancia::obtenerTodos($conn);
                $solicitudes = Solicitud::obtenerDisponibles($conn);
                ob_start();
                include __DIR__ . '/../views/ordenes/crear.php';
                $contenido = ob_get_clean();
                $titulo = 'Registrar Orden';
                include __DIR__ . '/../views/layout.php';
            }
            break;

        /* --------------------------------------------------------
           ✏️ EDITAR ORDEN
        -------------------------------------------------------- */
        case 'editar':
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) go_to('ordenController.php?accion=listar');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'direccion_origen'       => trim($_POST['direccion_origen'] ?? ''),
                    'direccion_destino'      => trim($_POST['direccion_destino'] ?? ''),
                    'peso_estimado_kg'       => ($_POST['peso_estimado_kg'] ?? '') !== '' ? (float)$_POST['peso_estimado_kg'] : null,
                    'fecha_carga'            => $_POST['fecha_carga'] ?? null,
                    'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada'] ?? null,
                    'estado'                 => $_POST['estado'] ?? 'Programada',
                    'observaciones'          => trim($_POST['observaciones'] ?? '')
                ];
                Orden::actualizar($conn, $id, $data);
                go_to('ordenController.php?accion=listar&updated=1');
            } else {
                $orden = Orden::obtenerPorId($conn, $id);
                $tipos = TipoMercancia::obtenerTodos($conn);
                $lineas = TipoMercanciaOrden::obtenerPorOrden($conn, $id);
                ob_start();
                include __DIR__ . '/../views/ordenes/editar.php';
                $contenido = ob_get_clean();
                $titulo = 'Editar Orden';
                include __DIR__ . '/../views/layout.php';
            }
            break;

        /* --------------------------------------------------------
           🗑️ ELIMINAR ORDEN
        -------------------------------------------------------- */
        case 'eliminar':
            $id = (int)($_GET['id'] ?? 0);
            if ($id) Orden::eliminar($conn, $id);
            go_to('ordenController.php?accion=listar&deleted=1');
            break;

        /* --------------------------------------------------------
           📋 LISTAR ÓRDENES
        -------------------------------------------------------- */
        default:
            $ordenes = Orden::obtenerTodos($conn);
            ob_start();
            include __DIR__ . '/../views/ordenes/listar.php';
            $contenido = ob_get_clean();
            $titulo = 'Órdenes de Transporte';
            include __DIR__ . '/../views/layout.php';
            break;
    }
} catch (Throwable $t) {
    error_log('[ORDEN_FATAL] ' . $t->getMessage());
    echo "<pre>⚠️ Error fatal: " . htmlspecialchars($t->getMessage()) . "</pre>";
}

if (function_exists('ob_get_length') && ob_get_length()) @ob_end_flush();
?>
