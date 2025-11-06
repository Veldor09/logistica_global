<?php
// ============================================================
// 📁 models/Rol.php
// Modelo para la gestión de roles de usuario (SQL Server)
// ============================================================

if (!class_exists('Rol')) {
class Rol {

    /* ============================================================
       📋 Obtener todos los roles
    ============================================================ */
    public static function obtenerTodos($conn) {
        $sql = "SELECT id_rol, nombre, descripcion, estado 
                FROM Rol 
                ORDER BY id_rol ASC";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) {
            throw new Exception('❌ Error al obtener roles: ' . print_r(sqlsrv_errors(), true));
        }

        $roles = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $roles[] = $row;
        }

        return $roles;
    }

    /* ============================================================
       🔍 Obtener un rol por su ID
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $sql = "SELECT id_rol, nombre, descripcion, estado 
                FROM Rol 
                WHERE id_rol = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);

        if (!$stmt) {
            throw new Exception('❌ Error al buscar rol: ' . print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       ➕ Crear un nuevo rol
    ============================================================ */
    public static function crear($conn, $data) {
        $sql = "INSERT INTO Rol (nombre, descripcion, estado)
                OUTPUT INSERTED.id_rol
                VALUES (?, ?, ?)";

        $params = [
            trim($data['nombre'] ?? ''),
            trim($data['descripcion'] ?? ''),
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);

        if (!$stmt) {
            throw new Exception('❌ Error al crear rol: ' . print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row['id_rol'] ?? null;
    }

    /* ============================================================
       ✏️ Actualizar un rol existente
    ============================================================ */
    public static function actualizar($conn, $id, $data) {
        $sql = "UPDATE Rol
                   SET nombre = ?, 
                       descripcion = ?, 
                       estado = ?
                 WHERE id_rol = ?";

        $params = [
            trim($data['nombre'] ?? ''),
            trim($data['descripcion'] ?? ''),
            $data['estado'] ?? 'Activo',
            $id
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);

        if (!$stmt) {
            throw new Exception('❌ Error al actualizar rol: ' . print_r(sqlsrv_errors(), true));
        }
    }

    /* ============================================================
       🗑️ Eliminar un rol por ID
    ============================================================ */
    public static function eliminar($conn, $id) {
        $sql = "DELETE FROM Rol WHERE id_rol = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);

        if (!$stmt) {
            throw new Exception('❌ Error al eliminar rol: ' . print_r(sqlsrv_errors(), true));
        }
    }
}}
?>
