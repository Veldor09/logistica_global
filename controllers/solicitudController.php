<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {

    /* ============================================================
       🟢 CREAR SOLICITUD
    ============================================================ */
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Solicitud::crear($conn, $_POST);
                header("Location: /logistica_global/controllers/solicitudController.php?success=1");
                exit;
            } catch (Exception $e) {
                error_log("Error al crear solicitud: " . $e->getMessage());
                // Render con layout y mensaje si quieres
                header("Location: /logistica_global/controllers/solicitudController.php?error=1");
                exit;
            }
        } else {
            $clientes = Cliente::obtenerTodos($conn);

            ob_start();
            include __DIR__ . '/../views/solicitudes/crear.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Solicitud';
            include __DIR__ . '/../views/layout.php';
        }
        break;

    /* ============================================================
       ✏️ EDITAR SOLICITUD
    ============================================================ */
    case 'editar':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /logistica_global/controllers/solicitudController.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Solicitud::actualizar($conn, $id, $_POST);
                header("Location: /logistica_global/controllers/solicitudController.php?updated=1");
                exit;
            } catch (Exception $e) {
                error_log("Error al actualizar solicitud: " . $e->getMessage());
                header("Location: /logistica_global/controllers/solicitudController.php?error=1");
                exit;
            }
        } else {
            $solicitud = Solicitud::obtenerPorId($conn, $id);
            if (!$solicitud) {
                echo "Solicitud no encontrada.";
                exit;
            }

            $clientes = Cliente::obtenerTodos($conn);
            // (Opcional) excluir remitente del select de destinatario
            if (!empty($solicitud['correo_remitente'])) {
                $clientes = array_values(array_filter(
                    $clientes,
                    fn($c) => $c['correo'] !== $solicitud['correo_remitente']
                ));
            }

            ob_start();
            include __DIR__ . '/../views/solicitudes/editar.php';
            $contenido = ob_get_clean();
            $titulo = 'Editar Solicitud de Transporte';
            include __DIR__ . '/../views/layout.php';
        }
        break;

    /* ============================================================
       🔴 ELIMINAR SOLICITUD
    ============================================================ */
    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                Solicitud::eliminar($conn, $id);
                header("Location: /logistica_global/controllers/solicitudController.php?deleted=1");
                exit;
            } catch (Exception $e) {
                error_log("Error al eliminar solicitud: " . $e->getMessage());
                header("Location: /logistica_global/controllers/solicitudController.php?error=1");
                exit;
            }
        } else {
            header("Location: /logistica_global/controllers/solicitudController.php");
            exit;
        }
        break;

    /* ============================================================
       📋 LISTAR SOLICITUDES
    ============================================================ */
    default:
        try {
            $solicitudes = Solicitud::obtenerTodos($conn);
        } catch (Exception $e) {
            $solicitudes = [];
        }

        ob_start();
        include __DIR__ . '/../views/solicitudes/listar.php';
        $contenido = ob_get_clean();
        $titulo = 'Lista de Solicitudes de Transporte';
        include __DIR__ . '/../views/layout.php';
        break;
}
