<?php
// ============================================================
// 📁 models/Usuario.php
// Modelo de datos para la gestión de usuarios del sistema
// ============================================================

if (!class_exists('Usuario')) {
class Usuario
{
    /* ============================================================
       📋 Listar usuarios con filtros y rol asociado
    ============================================================ */
    public static function obtenerTodos($conn, $filtros = [])
    {
        $where = [];
        $params = [];

        if (!empty($filtros['search'])) {
            $where[] = "(u.nombre LIKE ? OR u.correo LIKE ?)";
            $like = '%' . $filtros['search'] . '%';
            array_push($params, $like, $like);
        }

        if (!empty($filtros['estado'])) {
            $where[] = "u.estado = ?";
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['id_rol'])) {
            $where[] = "u.id_rol = ?";
            $params[] = $filtros['id_rol'];
        }

        $whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

        $sql = "
            SELECT 
                u.id_usuario,
                u.id_rol,
                r.nombre AS rol,
                u.nombre,
                u.correo,
                u.estado,
                u.fecha_creacion
            FROM Usuario u
            INNER JOIN Rol r ON u.id_rol = r.id_rol
            $whereSql
            ORDER BY u.id_usuario DESC
        ";

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new Exception('❌ Error al listar usuarios: ' . print_r(sqlsrv_errors(), true));
        }

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (isset($r['fecha_creacion']) && $r['fecha_creacion'] instanceof DateTime) {
                $r['fecha_creacion'] = $r['fecha_creacion']->format('Y-m-d H:i:s');
            }
            $rows[] = $r;
        }

        return $rows;
    }

    /* ============================================================
       🔍 Obtener usuario por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "
            SELECT 
                u.*,
                r.nombre AS rol
            FROM Usuario u
            INNER JOIN Rol r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = ?
        ";

        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) {
            throw new Exception('❌ Error al obtener usuario: ' . print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null;
    }

    /* ============================================================
       🔎 Obtener usuario por correo
    ============================================================ */
    public static function obtenerPorCorreo($conn, $correo)
    {
        $sql = "SELECT * FROM Usuario WHERE correo = ?";
        $stmt = sqlsrv_query($conn, $sql, [$correo]);
        if (!$stmt) {
            throw new Exception('❌ Error al obtener usuario por correo: ' . print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null;
    }

    /* ============================================================
       🆕 Crear usuario (contraseña ya hasheada)
    ============================================================ */
    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Usuario
                (id_rol, nombre, correo, contrasena, estado, fecha_creacion)
            OUTPUT INSERTED.id_usuario
            VALUES (?, ?, ?, ?, ?, SYSDATETIME())
        ";

        $params = [
            $data['id_rol'],
            $data['nombre'],
            $data['correo'],
            $data['password_hash'] ?? null,
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new Exception('❌ Error al crear usuario: ' . print_r(sqlsrv_errors(), true));
        }

        sqlsrv_fetch($stmt);
        return (int) sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ Actualizar usuario
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $sets = [
            "id_rol = ?",
            "nombre = ?",
            "correo = ?",
            "estado = ?"
        ];

        $params = [
            $data['id_rol'],
            $data['nombre'],
            $data['correo'],
            $data['estado'] ?? 'Activo'
        ];

        if (!empty($data['password_hash'])) {
            $sets[] = "contrasena = ?";
            $params[] = $data['password_hash'];
        }

        $params[] = $id;

        $sql = "UPDATE Usuario SET " . implode(", ", $sets) . " WHERE id_usuario = ?";

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new Exception('❌ Error al actualizar usuario: ' . print_r(sqlsrv_errors(), true));
        }

        return true;
    }

    /* ============================================================
       🗑️ Eliminar usuario
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $sql = "DELETE FROM Usuario WHERE id_usuario = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) {
            throw new Exception('❌ Error al eliminar usuario: ' . print_r(sqlsrv_errors(), true));
        }

        return true;
    }

    /* ============================================================
       🔐 LOGIN — Validar credenciales
    ============================================================ */
    public static function login($conn, $correo, $clave)
    {
        $sql = "
            SELECT 
                u.id_usuario,
                u.id_rol,
                r.nombre AS rol,
                u.nombre,
                u.correo,
                u.contrasena,
                u.estado
            FROM Usuario u
            INNER JOIN Rol r ON u.id_rol = r.id_rol
            WHERE u.correo = ?
        ";

        $stmt = sqlsrv_query($conn, $sql, [$correo]);
        if (!$stmt) {
            throw new Exception('❌ Error al buscar usuario: ' . print_r(sqlsrv_errors(), true));
        }

        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!$user || $user['estado'] !== 'Activo') {
            return null; // Usuario inactivo o inexistente
        }

        // ✅ Verificar contraseña encriptada (bcrypt)
        if (!empty($user['contrasena']) && password_verify($clave, $user['contrasena'])) {
            unset($user['contrasena']); // no devolver el hash
            return $user;
        }

        return null; // Contraseña incorrecta
    }
}}
?>
