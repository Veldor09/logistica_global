<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Vehiculo.php';

$accion = $_GET['accion'] ?? 'listar';
$contenido = '';
$titulo = 'Vehículos';

try {
    switch ($accion) {

        /* ============================================================
           🟢 CREAR VEHÍCULO
        ============================================================ */
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Vehiculo::crear($conn, $_POST);
                header("Location: /logistica_global/controllers/vehiculoController.php?success=1");
                exit;
            }

            // Tipos de camión para el select
            $stmt = sqlsrv_query($conn, "SELECT id_tipo_camion, nombre_tipo FROM Tipo_Camion ORDER BY nombre_tipo");
            $tipos = [];
            if ($stmt) while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $tipos[] = $row;

            ob_start();
            include __DIR__ . '/../views/vehiculos/crear.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Vehículo';
            break;

        /* ============================================================
           ✏️ EDITAR VEHÍCULO
        ============================================================ */
        case 'editar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header("Location: /logistica_global/controllers/vehiculoController.php");
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Vehiculo::actualizar($conn, $id, $_POST);
                header("Location: /logistica_global/controllers/vehiculoController.php?updated=1");
                exit;
            }

            $vehiculo = Vehiculo::obtenerPorId($conn, $id);
            $stmt = sqlsrv_query($conn, "SELECT id_tipo_camion, nombre_tipo FROM Tipo_Camion ORDER BY nombre_tipo");
            $tipos = [];
            if ($stmt) while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $tipos[] = $row;

            ob_start();
            include __DIR__ . '/../views/vehiculos/editar.php';
            $contenido = ob_get_clean();
            $titulo = 'Editar Vehículo';
            break;

        /* ============================================================
           🔴 ELIMINAR VEHÍCULO
        ============================================================ */
        case 'eliminar':
            $id = $_GET['id'] ?? null;
            if ($id) {
                Vehiculo::eliminar($conn, $id);
                header("Location: /logistica_global/controllers/vehiculoController.php?deleted=1");
                exit;
            }
            header("Location: /logistica_global/controllers/vehiculoController.php");
            exit;

        /* ============================================================
           📋 LISTAR VEHÍCULOS
        ============================================================ */
        default:
            $vehiculos = Vehiculo::obtenerTodos($conn);
            ob_start();
            include __DIR__ . '/../views/vehiculos/listar.php';
            $contenido = ob_get_clean();
            $titulo = 'Lista de Vehículos';
            break;
    }

} catch (Throwable $e) {
    error_log("Error controlador Vehículos: " . $e->getMessage());
    $contenido = "<div class='alert danger'>❌ Error al procesar el vehículo.</div>";
    $titulo = 'Error';
}

include __DIR__ . '/../views/layout.php';
?>
