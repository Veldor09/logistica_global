<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Cliente.php';

$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {

    /* ============================================================
       🟢 CREAR CLIENTE
    ============================================================ */
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tipo = $_POST['tipo_identificacion'] ?? '';

                if ($tipo === 'FISICO') {
                    Cliente::crearFisico($conn, $_POST);
                } elseif ($tipo === 'JURIDICO') {
                    Cliente::crearJuridico($conn, $_POST);
                }

                header("Location: /logistica_global/controllers/clienteController.php?success=1");
                exit;
            } catch (Exception $e) {
                echo "❌ Error al crear cliente: " . htmlspecialchars($e->getMessage());
            }
        } else {
            ob_start();
            include __DIR__ . '/../views/clientes/crear.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Cliente';
            include __DIR__ . '/../views/layout.php';
        }
        break;

    /* ============================================================
       ✏️ EDITAR CLIENTE
    ============================================================ */
    case 'editar':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /logistica_global/controllers/clienteController.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Cliente::actualizar($conn, $id, $_POST);
                header("Location: /logistica_global/controllers/clienteController.php?updated=1");
                exit;
            } catch (Exception $e) {
                echo "❌ Error al actualizar cliente: " . htmlspecialchars($e->getMessage());
            }
        } else {
            // Obtener datos del cliente a editar
            $cliente = Cliente::obtenerPorId($conn, $id);
            if (!$cliente) {
                echo "Cliente no encontrado.";
                exit;
            }

            ob_start();
            include __DIR__ . '/../views/clientes/editar.php';
            $contenido = ob_get_clean();
            $titulo = 'Editar Cliente';
            include __DIR__ . '/../views/layout.php';
        }
        break;

    /* ============================================================
       🔴 ELIMINAR CLIENTE
    ============================================================ */
    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                Cliente::eliminar($conn, $id);
                header("Location: /logistica_global/controllers/clienteController.php?deleted=1");
                exit;
            } catch (Exception $e) {
                echo "❌ Error al eliminar cliente: " . htmlspecialchars($e->getMessage());
            }
        }
        break;

    /* ============================================================
       📋 LISTAR CLIENTES
    ============================================================ */
    default:
        try {
            $clientes = Cliente::obtenerTodos($conn);
        } catch (Exception $e) {
            $clientes = [];
        }

        ob_start();
        include __DIR__ . '/../views/clientes/listar.php';
        $contenido = ob_get_clean();
        $titulo = 'Lista de Clientes';
        include __DIR__ . '/../views/layout.php';
        break;
}
?>