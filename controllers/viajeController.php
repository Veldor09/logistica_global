<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Viaje.php';
require_once __DIR__ . '/../models/Orden.php';
require_once __DIR__ . '/../models/Conductor.php';
require_once __DIR__ . '/../models/Vehiculo.php';
require_once __DIR__ . '/../models/Ruta.php';

$accion = $_GET['accion'] ?? 'listar';

try {
    switch ($accion) {
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Viaje::crear($conn, $_POST);
                header("Location: /logistica_global/controllers/viajeController.php?success=1");
                exit;
            }

            $ordenes = array_filter(Orden::obtenerTodos($conn), fn($o) => $o['estado'] === 'Programada');
            $conductores = array_filter(Conductor::obtenerTodos($conn), fn($c) => $c['estado'] === 'Activo');
            $vehiculos = array_filter(Vehiculo::obtenerTodos($conn), fn($v) => $v['estado'] === 'Activo');
            $rutas = array_filter(Ruta::obtenerTodos($conn), fn($r) => $r['estado'] === 'Activa');

            ob_start();
            include __DIR__ . '/../views/viajes/crear.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Viaje';
            break;

        case 'editar':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header("Location: /logistica_global/controllers/viajeController.php");
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Viaje::actualizar($conn, $id, $_POST);
                header("Location: /logistica_global/controllers/viajeController.php?updated=1");
                exit;
            }

            $viaje = Viaje::obtenerPorId($conn, $id);
            $ordenes = Orden::obtenerTodos($conn);
            $conductores = Conductor::obtenerTodos($conn);
            $vehiculos = Vehiculo::obtenerTodos($conn);
            $rutas = Ruta::obtenerTodos($conn);

            ob_start();
            include __DIR__ . '/../views/viajes/editar.php';
            $contenido = ob_get_clean();
            $titulo = 'Editar Viaje';
            break;

        case 'eliminar':
            $id = $_GET['id'] ?? null;
            if ($id) {
                Viaje::eliminar($conn, $id);
                header("Location: /logistica_global/controllers/viajeController.php?deleted=1");
                exit;
            }
            header("Location: /logistica_global/controllers/viajeController.php");
            exit;

        default:
            $viajes = Viaje::obtenerTodos($conn);
            ob_start();
            include __DIR__ . '/../views/viajes/listar.php';
            $contenido = ob_get_clean();
            $titulo = 'Lista de Viajes';
            break;
    }

} catch (Throwable $e) {
    error_log("Error en viajeController: " . $e->getMessage());
    $contenido = "<div class='alert danger'>❌ Error al procesar el viaje.</div>";
    $titulo = 'Error';
}

include __DIR__ . '/../views/layout.php';
?>
