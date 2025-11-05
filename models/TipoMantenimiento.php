<?php
class TipoMantenimiento {

    /* Obtener todos los tipos de mantenimiento */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_tipo_mantenimiento,
                nombre,
                descripcion,
                frecuencia_dias
            FROM Tipo_Mantenimiento
            ORDER BY id_tipo_mantenimiento DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* Obtener un tipo por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Mantenimiento WHERE id_tipo_mantenimiento = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo tipo de mantenimiento */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Mantenimiento (nombre, descripcion, frecuencia_dias)
            OUTPUT INSERTED.id_tipo_mantenimiento
            VALUES (?, ?, ?)
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['frecuencia_dias'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar tipo de mantenimiento */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Mantenimiento
            SET nombre = ?, descripcion = ?, frecuencia_dias = ?
            WHERE id_tipo_mantenimiento = ?
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'],
            $data['frecuencia_dias'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tipo de mantenimiento */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Mantenimiento WHERE id_tipo_mantenimiento = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
