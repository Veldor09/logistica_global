<?php
// ============================================================
// 📂 controllers/usuarioController.php
// Controlador para la gestión de usuarios (CRUD + Auditoría)
// ============================================================

session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

$BASE_PATH = dirname(__DIR__);

// ============================================================
// 🔧 Dependencias
// ============================================================
require_once $BASE_PATH . '/config/db.php';
require_once $BASE_PATH . '/config/auth_guard.php';
require_once $BASE_PATH . '/models/Usuario.php';
require_once $BASE_PATH . '/models/Rol.php';
require_once $BASE_PATH . '/common/auditoria.php';

// ============================================================
// 🧩 Helpers generales
// ============================================================
function view($ruta, $data = []) {
  extract($data);
  $BASE_PATH = dirname(__DIR__);
  ob_start();
  include $BASE_PATH . "/views/$ruta";
  $contenido = ob_get_clean();
  include $BASE_PATH . '/views/layout.php';
}

function redirect($url) {
  header("Location: $url");
  exit;
}

// ============================================================
// 🚦 Enrutamiento de acciones
// ============================================================
$accion = $_GET['accion'] ?? 'listar';
switch ($accion) {
  case 'listar':   listarUsuarios($conn); break;
  case 'crear':    ($_SERVER['REQUEST_METHOD'] === 'POST') ? crearUsuarioPost($conn) : crearUsuarioGet($conn); break;
  case 'editar':   ($_SERVER['REQUEST_METHOD'] === 'POST') ? editarUsuarioPost($conn) : editarUsuarioGet($conn); break;
  case 'eliminar': eliminarUsuario($conn); break;
  default:         listarUsuarios($conn); break;
}

// ============================================================
// 🧠 Validación: correo único
// ============================================================
function validarUnicoCorreo($conn, $correo, $exceptId = null) {
  $sql = "SELECT id_usuario FROM Usuario WHERE correo = ?" . ($exceptId ? " AND id_usuario <> ?" : "");
  $params = $exceptId ? [$correo, $exceptId] : [$correo];
  $stmt = sqlsrv_query($conn, $sql, $params);
  if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
  return (bool) sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

// ============================================================
// 📋 Listar usuarios
// ============================================================
function listarUsuarios($conn) {
  try {
    $filtros = [
      'search' => $_GET['search'] ?? '',
      'estado' => $_GET['estado'] ?? '',
      'id_rol' => $_GET['id_rol'] ?? '',
    ];

    $usuarios = Usuario::obtenerTodos($conn, $filtros);
    $roles = Rol::obtenerTodos($conn);

    view('usuarios/listar.php', [
      'titulo'   => 'Gestión de Usuarios',
      'usuarios' => $usuarios,
      'roles'    => $roles,
      'filtros'  => $filtros
    ]);
  } catch (Throwable $e) {
    echo "❌ Error al listar usuarios: " . $e->getMessage();
  }
}

// ============================================================
// ➕ Crear usuario
// ============================================================
function crearUsuarioGet($conn) {
  $roles = Rol::obtenerTodos($conn);
  view('usuarios/crear.php', [
    'titulo'  => 'Registrar Usuario',
    'roles'   => $roles,
    'errores' => [],
    'old'     => []
  ]);
}

function crearUsuarioPost($conn) {
  $roles = Rol::obtenerTodos($conn);

  $data = [
    'id_rol'    => $_POST['id_rol'] ?? '',
    'nombre'    => trim($_POST['nombre'] ?? ''),
    'apellido1' => trim($_POST['apellido1'] ?? ''),
    'apellido2' => trim($_POST['apellido2'] ?? ''),
    'correo'    => trim($_POST['correo'] ?? ''),
    'telefono'  => trim($_POST['telefono'] ?? ''),
    'estado'    => $_POST['estado'] ?? 'Activo',
  ];

  $pass  = $_POST['password'] ?? '';
  $pass2 = $_POST['password_confirm'] ?? '';

  // 🧩 Validaciones
  $errores = [];
  if (empty($data['id_rol']))    $errores['id_rol'] = 'Seleccione un rol.';
  if ($data['nombre'] === '')    $errores['nombre'] = 'Ingrese el nombre.';
  if ($data['apellido1'] === '') $errores['apellido1'] = 'Ingrese el primer apellido.';
  if ($data['correo'] === '')    $errores['correo'] = 'Ingrese el correo.';
  if ($pass === '')              $errores['password'] = 'Ingrese una contraseña.';
  if ($pass !== $pass2)          $errores['password_confirm'] = 'Las contraseñas no coinciden.';
  if ($data['correo'] && validarUnicoCorreo($conn, $data['correo'])) {
    $errores['correo'] = 'El correo ya está registrado.';
  }

  // Si hay errores → volver a mostrar formulario
  if (!empty($errores)) {
    view('usuarios/crear.php', [
      'titulo'  => 'Registrar Usuario',
      'roles'   => $roles,
      'errores' => $errores,
      'old'     => $data
    ]);
    return;
  }

  // 🧩 Inserción
  try {
    $data['password_hash'] = password_hash($pass, PASSWORD_DEFAULT);
    $id = Usuario::crear($conn, $data);

    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Usuario',
      'INSERT',
      "Se creó el usuario #$id ({$data['correo']}).",
      $_SESSION['usuario']['id'] ?? null
    );

    redirect('/logistica_global/controllers/usuarioController.php?accion=listar&success=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al crear usuario: ' . $e->getMessage();
    view('usuarios/crear.php', [
      'titulo'  => 'Registrar Usuario',
      'roles'   => $roles,
      'errores' => $errores,
      'old'     => $data
    ]);
  }
}

// ============================================================
// ✏️ Editar usuario
// ============================================================
function editarUsuarioGet($conn) {
  $id = $_GET['id'] ?? 0;
  $usuario = Usuario::obtenerPorId($conn, $id);
  if (!$usuario) redirect('/logistica_global/controllers/usuarioController.php?accion=listar');

  $roles = Rol::obtenerTodos($conn);
  view('usuarios/editar.php', [
    'titulo'  => 'Editar Usuario',
    'usuario' => $usuario,
    'roles'   => $roles,
    'errores' => []
  ]);
}

function editarUsuarioPost($conn) {
  $id = $_GET['id'] ?? 0;
  $roles = Rol::obtenerTodos($conn);

  $data = [
    'id_rol'    => $_POST['id_rol'] ?? '',
    'nombre'    => trim($_POST['nombre'] ?? ''),
    'apellido1' => trim($_POST['apellido1'] ?? ''),
    'apellido2' => trim($_POST['apellido2'] ?? ''),
    'correo'    => trim($_POST['correo'] ?? ''),
    'telefono'  => trim($_POST['telefono'] ?? ''),
    'estado'    => $_POST['estado'] ?? 'Activo',
  ];

  $pass  = $_POST['password'] ?? '';
  $pass2 = $_POST['password_confirm'] ?? '';

  // 🧩 Validaciones
  $errores = [];
  if (empty($data['id_rol']))    $errores['id_rol'] = 'Seleccione un rol.';
  if ($data['nombre'] === '')    $errores['nombre'] = 'Ingrese el nombre.';
  if ($data['apellido1'] === '') $errores['apellido1'] = 'Ingrese el primer apellido.';
  if ($data['correo'] === '')    $errores['correo'] = 'Ingrese el correo.';
  if ($data['correo'] && validarUnicoCorreo($conn, $data['correo'], $id)) {
    $errores['correo'] = 'El correo ya está registrado por otro usuario.';
  }
  if ($pass !== '' || $pass2 !== '') {
    if ($pass !== $pass2) $errores['password_confirm'] = 'Las contraseñas no coinciden.';
  }

  // Si hay errores → volver al formulario
  if (!empty($errores)) {
    view('usuarios/editar.php', [
      'titulo'  => 'Editar Usuario',
      'usuario' => array_merge(['id_usuario' => $id], $data),
      'roles'   => $roles,
      'errores' => $errores
    ]);
    return;
  }

  // 🧩 Actualización
  try {
    if ($pass !== '') {
      $data['password_hash'] = password_hash($pass, PASSWORD_DEFAULT);
    }

    Usuario::actualizar($conn, $id, $data);

    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Usuario',
      'UPDATE',
      "Se actualizó el usuario #$id ({$data['correo']}).",
      $_SESSION['usuario']['id'] ?? null
    );

    redirect('/logistica_global/controllers/usuarioController.php?accion=listar&updated=1');
  } catch (Throwable $e) {
    $errores['general'] = 'Error al actualizar usuario: ' . $e->getMessage();
    view('usuarios/editar.php', [
      'titulo'  => 'Editar Usuario',
      'usuario' => array_merge(['id_usuario' => $id], $data),
      'roles'   => $roles,
      'errores' => $errores
    ]);
  }
}

// ============================================================
// 🗑️ Eliminar usuario
// ============================================================
function eliminarUsuario($conn) {
  $id = $_GET['id'] ?? 0;
  if (!$id) {
    redirect('/logistica_global/controllers/usuarioController.php?accion=listar&error=1');
  }

  try {
    Usuario::eliminar($conn, (int)$id);

    registrarAccion(
      $conn,
      $_SESSION['usuario']['correo'] ?? 'sistema',
      $_SESSION['usuario']['rol'] ?? 'sistema',
      'Usuario',
      'DELETE',
      "Se eliminó el usuario #$id.",
      $_SESSION['usuario']['id'] ?? null
    );

    redirect('/logistica_global/controllers/usuarioController.php?accion=listar&deleted=1');
  } catch (Throwable $e) {
    echo "❌ Error al eliminar usuario: " . $e->getMessage();
  }
}
?>
