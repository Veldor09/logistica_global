<?php
class UsuarioSistema {

    /* Obtener todos los usuarios */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                u.id_usuario,
                u.nombre,
                u.correo,
                u.estado,
                u.fecha_creacion,
                r.nombre AS rol
            FROM Usuario_Sistema u
            INNER JOIN Rol r ON u.id_rol = r.id_rol
            ORDER BY u.id_usuario ASC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $usuarios = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    /* Obtener usuario por ID */
    public static function obtenerPorId($conn, $idUsuario) {
        $query = "
            SELECT 
                u.*,
                r.nombre AS rol
            FROM Usuario_Sistema u
            INNER JOIN Rol r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$idUsuario]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo usuario */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Usuario_Sistema (nombre, correo, contrasena, id_rol, estado)
            OUTPUT INSERTED.id_usuario
            VALUES (?, ?, ?, ?, ?)
        ";

        // ⚠️ La contraseña se debe pasar ya encriptada con password_hash() desde el controlador
        $params = [
            $data['nombre'],
            $data['correo'],
            $data['contrasena'],
            $data['id_rol'],
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar datos de usuario */
    public static function actualizar($conn, $idUsuario, $data) {
        $query = "
            UPDATE Usuario_Sistema
            SET nombre = ?, correo = ?, id_rol = ?, estado = ?
            WHERE id_usuario = ?
        ";

        $params = [
            $data['nombre'],
            $data['correo'],
            $data['id_rol'],
            $data['estado'],
            $idUsuario
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Actualizar contraseña */
    public static function actualizarContrasena($conn, $idUsuario, $nuevaContrasena) {
        $query = "UPDATE Usuario_Sistema SET contrasena = ? WHERE id_usuario = ?";
        $stmt = sqlsrv_query($conn, $query, [$nuevaContrasena, $idUsuario]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar usuario */
    public static function eliminar($conn, $idUsuario) {
        $query = "DELETE FROM Usuario_Sistema WHERE id_usuario = ?";
        $stmt = sqlsrv_query($conn, $query, [$idUsuario]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Buscar usuario por correo (para login o verificación) */
    public static function buscarPorCorreo($conn, $correo) {
        $query = "SELECT * FROM Usuario_Sistema WHERE correo = ?";
        $stmt = sqlsrv_query($conn, $query, [$correo]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Autenticar usuario (verificar correo y contraseña) */
    public static function autenticar($conn, $correo, $contrasena) {
        $usuario = self::buscarPorCorreo($conn, $correo);
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
        return null;
    }
}
?>
