<?php
// ============================================================
// 🧾 CONTROLADOR DE FACTURAS
// ============================================================

// 🔹 Conexión y autenticación
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/auth_guard.php';

// 🔹 Funciones globales (view(), redirect(), etc.)
require_once dirname(__DIR__) . '/common/helpers.php';

// 🔹 Modelos necesarios
require_once dirname(__DIR__) . '/models/Factura.php';
require_once dirname(__DIR__) . '/models/DetalleFactura.php';
require_once dirname(__DIR__) . '/models/Orden.php';

// 🔹 Vista para PDF
require_once dirname(__DIR__) . '/views/facturas/pdf.php';

// ============================================================
// 🔀 ACCIÓN PRINCIPAL
// ============================================================
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {

    /* ============================================================
       📋 LISTAR TODAS LAS FACTURAS
    ============================================================ */
    case 'listar':
        try {
            $facturas = Factura::obtenerTodas($conn);
            view('facturas/listar.php', ['facturas' => $facturas]);
        } catch (Exception $e) {
            echo "❌ Error al listar facturas: " . htmlspecialchars($e->getMessage());
        }
        break;

    /* ============================================================
       🧾 CREAR FACTURA
    ============================================================ */
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // 1️⃣ Datos principales
                $data = [
                    'id_orden'     => $_POST['id_orden'],
                    'subtotal'     => (float) $_POST['subtotal'],
                    'impuesto'     => (float) $_POST['impuesto'],
                    'metodo_pago'  => $_POST['metodo_pago'] ?? 'Efectivo',
                    'estado'       => $_POST['estado'] ?? 'Emitida',
                ];

                // 2️⃣ Crear factura principal
                $id_factura = Factura::crear($conn, $data);

                // 3️⃣ Crear detalles asociados
                if (!empty($_POST['detalle']) && is_array($_POST['detalle'])) {
                    foreach ($_POST['detalle'] as $d) {
                        if (!empty(trim($d['concepto'] ?? ''))) {
                            DetalleFactura::crear(
                                $conn,
                                $id_factura,
                                $d['concepto'],
                                (int) $d['cantidad'],
                                (float) $d['precio_unitario']
                            );
                        }
                    }
                }

                // 4️⃣ Marcar la orden como facturada
                Orden::marcarComoFacturada($conn, $data['id_orden']);

                // 5️⃣ Redirigir al listado
                redirect('/logistica_global/controllers/facturaController.php?success=1');
                exit;

            } catch (Exception $e) {
                error_log("❌ Error al crear factura: " . $e->getMessage());
                redirect('/logistica_global/controllers/facturaController.php?error=1');
                exit;
            }

        } else {
            // 📦 Mostrar formulario de creación
            try {
                $ordenes = Orden::obtenerNoFacturadas($conn);
                view('facturas/crear.php', ['ordenes' => $ordenes]);
            } catch (Exception $e) {
                echo "❌ Error al cargar formulario: " . htmlspecialchars($e->getMessage());
            }
        }
        break;

    /* ============================================================
       🗑️ ELIMINAR FACTURA
    ============================================================ */
    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if (!$id) die('❌ ID de factura no especificado.');

        try {
            DetalleFactura::eliminarPorFactura($conn, $id);
            Factura::eliminar($conn, $id);
            redirect('/logistica_global/controllers/facturaController.php?deleted=1');
        } catch (Exception $e) {
            error_log("❌ Error al eliminar factura: " . $e->getMessage());
            redirect('/logistica_global/controllers/facturaController.php?error=1');
        }
        break;

    /* ============================================================
       🧾 GENERAR PDF
    ============================================================ */
    case 'pdf':
        $id = $_GET['id'] ?? null;
        if (!$id) die('❌ ID de factura no especificado.');

        try {
            $factura  = Factura::obtenerPorId($conn, $id);
            $detalles = DetalleFactura::obtenerPorFactura($conn, $id);

            if (!$factura) die('❌ Factura no encontrada.');
            if (!is_array($detalles)) $detalles = [];

            generarFacturaPDF($factura, $detalles);
        } catch (Exception $e) {
            echo "❌ Error al generar PDF: " . htmlspecialchars($e->getMessage());
        }
        break;

    /* ============================================================
       ⚠️ OPCIÓN POR DEFECTO
    ============================================================ */
    default:
        echo "⚠️ Acción no reconocida.";
        break;
}
?>
