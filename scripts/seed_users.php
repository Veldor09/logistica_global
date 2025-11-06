<?php
// scripts/seed_users.php
// Ejecutar desde la raíz del proyecto: php scripts/seed_users.php
// Inserta roles y usuarios "quemados" (seed).

ini_set('display_errors', 1);
error_reporting(E_ALL);

$BASE_PATH = __DIR__ . '/../'; // ajusta si colocas el script en otro lugar
require_once $BASE_PATH . 'config/db.php';

// Helper: ejecutar query y devolver true/false
function runQuery($conn, $sql, $params = []) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) {
        echo "ERROR SQL: " . print_r(sqlsrv_errors(), true) . PHP_EOL;
        return false;
    }
    return $stmt;
}

// 1) Crear roles (si no existen)
$rolesToCreate = [
    ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema', 'estado' => 'Activo'],
    ['nombre' => 'Conductor',     'descripcion' => 'Operaciones de transporte', 'estado' => 'Activo'],
    ['nombre' => 'Soporte',       'descripcion' => 'Mantenimiento y gestión de flota', 'estado' => 'Activo'],
    ['nombre' => 'Facturacion',   'descripcion' => 'Control financiero y facturación', 'estado' => 'Activo'],
];

echo "==> Creando roles si no existen..." . PHP_EOL;
foreach ($rolesToCreate as $r) {
    // ¿existe?
    $check = runQuery($conn, "SELECT id_rol FROM Rol WHERE nombre = ?", [$r['nombre']]);
    $exists = $check && sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
    if ($exists) {
        echo " - Rol '{$r['nombre']}' ya existe. OK." . PHP_EOL;
        continue;
    }

    // Insertar
    $insert = "INSERT INTO Rol (nombre, descripcion, estado) OUTPUT INSERTED.id_rol VALUES (?, ?, ?)";
    $st = runQuery($conn, $insert, [$r['nombre'], $r['descripcion'], $r['estado']]);
    if ($st) {
        // consumir resultado OUTPUT
        $row = sqlsrv_fetch_array($st, SQLSRV_FETCH_ASSOC);
        $id = $row ? ($row['id_rol'] ?? null) : null;
        echo " + Rol '{$r['nombre']}' creado (id: {$id})." . PHP_EOL;
    }
}

echo PHP_EOL . "==> Obteniendo IDs de roles..." . PHP_EOL;
$getRoles = runQuery($conn, "SELECT id_rol, nombre FROM Rol");
$roleMap = [];
while ($rr = sqlsrv_fetch_array($getRoles, SQLSRV_FETCH_ASSOC)) {
    $roleMap[$rr['nombre']] = $rr['id_rol'];
}
print_r($roleMap);

// 2) Usuarios a insertar (correo y contraseña en claro)
$usersToCreate = [
    [
        'nombre' => 'Administrador',
        'apellido1' => 'Principal',
        'apellido2' => '',
        'correo' => 'admin@local',
        'telefono' => '8888-0000',
        'rol' => 'Administrador',
        'password' => 'Admin123!',
        'estado' => 'Activo'
    ],
    [
        'nombre' => 'Luis',
        'apellido1' => 'Conductor',
        'apellido2' => '',
        'correo' => 'conductor@local',
        'telefono' => '8888-1111',
        'rol' => 'Conductor',
        'password' => 'Conductor123!',
        'estado' => 'Activo'
    ],
    [
        'nombre' => 'María',
        'apellido1' => 'Soporte',
        'apellido2' => '',
        'correo' => 'soporte@local',
        'telefono' => '8888-2222',
        'rol' => 'Soporte',
        'password' => 'Soporte123!',
        'estado' => 'Activo'
    ],
    [
        'nombre' => 'Carlos',
        'apellido1' => 'Facturacion',
        'apellido2' => '',
        'correo' => 'factura@local',
        'telefono' => '8888-3333',
        'rol' => 'Facturacion',
        'password' => 'Factura123!',
        'estado' => 'Activo'
    ],
];

// 3) Insertar usuarios si no existen (usa password_hash)
echo PHP_EOL . "==> Creando usuarios si no existen..." . PHP_EOL;
foreach ($usersToCreate as $u) {
    // ¿existe el correo?
    $chk = runQuery($conn, "SELECT id_usuario FROM Usuario WHERE correo = ?", [$u['correo']]);
    $exist = $chk && sqlsrv_fetch_array($chk, SQLSRV_FETCH_ASSOC);
    if ($exist) {
        echo " - Usuario '{$u['correo']}' ya existe. OK." . PHP_EOL;
        continue;
    }

    // obtener id_rol
    $id_rol = $roleMap[$u['rol']] ?? null;
    if (!$id_rol) {
        echo " ! Rol '{$u['rol']}' no encontrado. Saltando usuario {$u['correo']}." . PHP_EOL;
        continue;
    }

    $hash = password_hash($u['password'], PASSWORD_DEFAULT);

    // Insert con OUTPUT para obtener id
    $sql = "INSERT INTO Usuario (id_rol, nombre, apellido1, apellido2, correo, telefono, password_hash, estado, fecha_registro)
            OUTPUT INSERTED.id_usuario
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, SYSDATETIME())";
    $params = [
        $id_rol,
        $u['nombre'],
        $u['apellido1'],
        $u['apellido2'] ?: null,
        $u['correo'],
        $u['telefono'],
        $hash,
        $u['estado']
    ];

    $st = runQuery($conn, $sql, $params);
    if ($st) {
        $row = sqlsrv_fetch_array($st, SQLSRV_FETCH_ASSOC);
        $newId = $row ? ($row['id_usuario'] ?? null) : null;
        echo " + Usuario '{$u['correo']}' creado (id: {$newId}). Credencial: {$u['correo']} / {$u['password']}" . PHP_EOL;

        // registrar en auditoría si existe la tabla
        // Intentamos insertar en Auditoria (si existe)
        $aud = runQuery($conn, "
            INSERT INTO Auditoria (usuario_correo, rol, tabla_afectada, accion, descripcion, id_usuario_afectado, fecha)
            VALUES (?, ?, ?, ?, ?, ?, SYSDATETIME())",
            [$u['correo'], $u['rol'], 'Usuario', 'INSERT', "Seed: usuario creado (id {$newId})", $newId]);
        // no hacemos nada si falla
    } else {
        echo " ! Error al crear usuario '{$u['correo']}'." . PHP_EOL;
    }
}

echo PHP_EOL . "==> Fin del seed. Ahora podés iniciar sesión con las credenciales mostradas arriba." . PHP_EOL;
echo "   Ejemplo: correo = admin@local  contraseña = Admin123!" . PHP_EOL;
echo PHP_EOL;
