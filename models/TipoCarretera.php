<?php
class TipoCarretera {

    /* Obtener todos los tipos de carretera */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_tipo_carretera,
                nombre,
                descripcion
            FROM Tipo_Carretera
            ORDER BY id_tipo_carretera DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* Obtener tipo de carretera por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Carretera WHERE id_tipo_carretera = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo tipo de carretera */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Carretera (nombre, descripcion)
            OUTPUT INSERTED.id_tipo_carretera
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

    /* Actualizar tipo de carretera */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Carretera
            SET nombre = ?, descripcion = ?
            WHERE id_tipo_carretera = ?
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tipo de carretera */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Carretera WHERE id_tipo_carretera = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
