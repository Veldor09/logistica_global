<?php
class TipoCamion {

    /* Obtener todos los tipos de camión */
    public static function obtenerTodos($conn) {
        $query = "SELECT * FROM Tipo_Camion ORDER BY id_tipo_camion DESC";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* Obtener un tipo de camión por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Camion WHERE id_tipo_camion = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo tipo de camión */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Camion (nombre_tipo, descripcion)
            OUTPUT INSERTED.id_tipo_camion
            VALUES (?, ?)
        ";
        $params = [
            $data['nombre_tipo'],
            $data['descripcion'] ?? null
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar tipo de camión */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Camion
            SET nombre_tipo = ?, descripcion = ?
            WHERE id_tipo_camion = ?
        ";
        $params = [
            $data['nombre_tipo'],
            $data['descripcion'],
            $id
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tipo de camión */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Camion WHERE id_tipo_camion = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
