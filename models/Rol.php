<?php
class Rol {

    /* Obtener todos los roles */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_rol,
                nombre,
                descripcion
            FROM Rol
            ORDER BY id_rol ASC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $roles = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $roles[] = $row;
        }
        return $roles;
    }

    /* Obtener rol por ID */
    public static function obtenerPorId($conn, $idRol) {
        $query = "SELECT * FROM Rol WHERE id_rol = ?";
        $stmt = sqlsrv_query($conn, $query, [$idRol]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo rol */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Rol (nombre, descripcion)
            OUTPUT INSERTED.id_rol
            VALUES (?, ?)
        ";

        $params = [
            $data['nombre'],
            $data['descripcion'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar rol */
    public static function actualizar($conn, $idRol, $data) {
        $query = "
            UPDATE Rol
            SET nombre = ?, descripcion = ?
            WHERE id_rol = ?
        ";

        $params = [
            $data['nombre'],
            $data['descripcion'],
            $idRol
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar rol */
    public static function eliminar($conn, $idRol) {
        $query = "DELETE FROM Rol WHERE id_rol = ?";
        $stmt = sqlsrv_query($conn, $query, [$idRol]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
