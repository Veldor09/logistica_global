<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Orden.php';
require_once __DIR__ . '/../models/Solicitud.php';

$accion = $_GET['accion'] ?? 'listar';
$contenido = '';
$titulo = 'Órdenes';

try {
    switch ($accion) {
        /* ============================================================
           🟢 CREAR ORDEN
        ============================================================ */
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Orden::crear($conn, $_POST);
                header("Location: /logistica_global/controllers/ordenController.php?success=1");
                exit;
            }

            // Solicitudes sin orden
            $stmt = sqlsrv_query(
                $conn,
                "SELECT id_solicitud, tipo_servicio, origen, destino_general
                 FROM Solicitud
                 WHERE id_solicitud NOT IN (SELECT id_solicitud FROM Orden)"
            );
            $solicitudes = [];
            if ($stmt) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $solicitudes[] = $row;
                }
            }

            ob_start();
            include __DIR__ . '/../views/ordenes/crear.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Orden de Transporte';
            break;

        /* ============================================================
           ✏️ EDITAR ORDEN
        ============================================================ */
        case 'editar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header("Location: /logistica_global/controllers/ordenController.php");
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Orden::actualizar($conn, $id, $_POST);
                header("Location: /logistica_global/controllers/ordenController.php?updated=1");
                exit;
            }

            $orden = Orden::obtenerPorId($conn, $id);
            if (!$orden) {
                header("Location: /logistica_global/controllers/ordenController.php?error=1");
                exit;
            }

            // Limpieza de buffer por si hubo “eco” anterior que rompa el layout
            if (ob_get_length()) { ob_clean(); }

            ob_start();
            include __DIR__ . '/../views/ordenes/editar.php';
            $contenido = ob_get_clean();
            $titulo = 'Editar Orden de Transporte';
            break;

        /* ============================================================
           🔴 ELIMINAR ORDEN
        ============================================================ */
        case 'eliminar':
            $id = $_GET['id'] ?? null;
            if ($id) {
                Orden::eliminar($conn, $id);
                header("Location: /logistica_global/controllers/ordenController.php?deleted=1");
                exit;
            }
            // Si no hay id, vuelve al listado
            header("Location: /logistica_global/controllers/ordenController.php");
            exit;

        /* ============================================================
           📋 LISTAR ÓRDENES
        ============================================================ */
        default:
            $ordenes = Orden::obtenerTodos($conn);
            ob_start();
            include __DIR__ . '/../views/ordenes/listar.php';
            $contenido = ob_get_clean();
            $titulo = 'Lista de Órdenes de Transporte';
            break;
    }

} catch (Throwable $e) {
    error_log("Error controlador Órdenes: " . $e->getMessage());
    // Mensaje mínimo sin romper todo
    $contenido = "<div class='alert danger' style='margin:1rem;'>❌ Error interno al procesar la solicitud.</div>";
    $titulo = 'Error';
}

/* ============================================================
   📄 INCLUIR LAYOUT UNA SOLA VEZ
============================================================ */
include __DIR__ . '/../views/layout.php';
