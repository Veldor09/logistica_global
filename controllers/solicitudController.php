<?php
// ============================================================
// 📄 controllers/solicitudController.php
// Controlador central de gestión de solicitudes (CRUD + acceso público)
// ============================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ============================================================
// 🧩 Dependencias y conexión
// ============================================================
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/models/Solicitud.php';
require_once dirname(__DIR__) . '/models/Cliente.php';

// ============================================================
// ⚙️ Iniciar sesión si no existe
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// 🔐 Determinar si hay sesión activa
// ============================================================
$usuarioActivo = $_SESSION['usuario'] ?? null;
if ($usuarioActivo) {
    require_once dirname(__DIR__) . '/config/auth_guard.php';
}

$accion = $_GET['accion'] ?? 'listar';

// ============================================================
// 🚦 Ruteo principal de acciones
// ============================================================
switch ($accion) {

    /* ============================================================
       🟢 CREAR SOLICITUD (modo interno - requiere sesión)
    ============================================================ */
    case 'crear':
        if (empty($usuarioActivo)) {
            header("Location: /logistica_global/views/error/unauthorized.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Solicitud::crear($conn, $_POST);
                header("Location: /logistica_global/controllers/solicitudController.php?success=1");
                exit;
            } catch (Exception $e) {
                error_log("❌ Error al crear solicitud: " . $e->getMessage());
                header("Location: /logistica_global/controllers/solicitudController.php?error=1");
                exit;
            }
        } else {
            $clientes = Cliente::obtenerTodos($conn);
            ob_start();
            include dirname(__DIR__) . '/views/solicitudes/crear.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Solicitud';
            include dirname(__DIR__) . '/views/layout.php';
        }
        break;

    /* ============================================================
       🌐 CREAR SOLICITUD PÚBLICA (visitantes sin sesión)
    ============================================================ */
    case 'crear_publica':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = Solicitud::crearPublica($conn, $_POST);
                header("Location: /logistica_global/controllers/solicitudController.php?accion=confirmacion&id=$id");
                exit;
            } catch (Exception $e) {
                error_log("❌ Error en solicitud pública: " . $e->getMessage());
                header("Location: /logistica_global/controllers/solicitudController.php?accion=crear_publica&error=1");
                exit;
            }
        } else {
            ob_start();
            include dirname(__DIR__) . '/views/solicitudes/crear_publica.php';
            $contenido = ob_get_clean();
            $titulo = 'Registrar Solicitud de Transporte';
            include dirname(__DIR__) . '/views/layout.php';
        }
        break;

    /* ============================================================
       ✅ CONFIRMACIÓN DE SOLICITUD PÚBLICA
    ============================================================ */
    case 'confirmacion':
        $id = $_GET['id'] ?? null;
        ob_start();
        ?>
        <div class="container" style="padding:30px; text-align:center;">
          <h2>✅ Solicitud enviada correctamente</h2>
          <p>Su número de solicitud es <strong>#<?= htmlspecialchars($id) ?></strong>.</p>
          <p>Pronto será atendida por nuestro equipo de Logística Global S.A.</p>
          <a href="/logistica_global/index.php" class="btn-primary"
             style="display:inline-block; margin-top:15px; background:#134074; color:white; padding:10px 16px; border-radius:6px; text-decoration:none;">
             Volver al inicio
          </a>
        </div>
        <?php
        $contenido = ob_get_clean();
        $titulo = 'Solicitud registrada';
        include dirname(__DIR__) . '/views/layout.php';
        break;

    /* ============================================================
       ✏️ EDITAR SOLICITUD (solo usuarios autenticados)
    ============================================================ */
    case 'editar':
        if (empty($usuarioActivo)) {
            header("Location: /logistica_global/views/error/unauthorized.php");
            exit;
        }

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
                error_log("❌ Error al actualizar solicitud: " . $e->getMessage());
                header("Location: /logistica_global/controllers/solicitudController.php?error=1");
                exit;
            }
        } else {
            $solicitud = Solicitud::obtenerPorId($conn, $id);
            if (!$solicitud) {
                echo "<p>Solicitud no encontrada.</p>";
                exit;
            }

            $clientes = Cliente::obtenerTodos($conn);
            // Excluir remitente del select de destinatario
            if (!empty($solicitud['correo_remitente'])) {
                $clientes = array_values(array_filter(
                    $clientes,
                    fn($c) => $c['correo'] !== $solicitud['correo_remitente']
                ));
            }

            ob_start();
            include dirname(__DIR__) . '/views/solicitudes/editar.php';
            $contenido = ob_get_clean();
            $titulo = 'Editar Solicitud de Transporte';
            include dirname(__DIR__) . '/views/layout.php';
        }
        break;

    /* ============================================================
       🔴 ELIMINAR SOLICITUD
    ============================================================ */
    case 'eliminar':
        if (empty($usuarioActivo)) {
            header("Location: /logistica_global/views/error/unauthorized.php");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                Solicitud::eliminar($conn, $id);
                header("Location: /logistica_global/controllers/solicitudController.php?deleted=1");
                exit;
            } catch (Exception $e) {
                error_log("❌ Error al eliminar solicitud: " . $e->getMessage());
                header("Location: /logistica_global/controllers/solicitudController.php?error=1");
                exit;
            }
        } else {
            header("Location: /logistica_global/controllers/solicitudController.php");
            exit;
        }
        break;

    /* ============================================================
       📋 LISTAR SOLICITUDES (vista pública o privada)
    ============================================================ */
    default:
        try {
            $solicitudes = Solicitud::obtenerTodos($conn);
        } catch (Exception $e) {
            $solicitudes = [];
        }

        // 🌐 Vista pública (sin login)
        if (empty($usuarioActivo)) {
            ob_start();
            include dirname(__DIR__) . '/views/solicitudes/publicas.php';
            $contenido = ob_get_clean();
            $titulo = 'Solicitudes de Transporte (Público)';
            include dirname(__DIR__) . '/views/layout.php';
            exit;
        }

        // 🔐 Vista privada (con login)
        ob_start();
        include dirname(__DIR__) . '/views/solicitudes/listar.php';
        $contenido = ob_get_clean();
        $titulo = 'Lista de Solicitudes de Transporte';
        include dirname(__DIR__) . '/views/layout.php';
        break;
}
?>
