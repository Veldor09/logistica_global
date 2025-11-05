<?php
class TipoEvento {

    /* Obtener todos los tipos de evento */
    public static function obtenerTodos($conn) {
        $query = "SELECT id_tipo_evento, nombre FROM Tipo_Evento ORDER BY id_tipo_evento ASC";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* Obtener tipo de evento por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Evento WHERE id_tipo_evento = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear tipo de evento */
    public static function crear($conn, $nombre) {
        $query = "
            INSERT INTO Tipo_Evento (nombre)
            OUTPUT INSERTED.id_tipo_evento
            VALUES (?)
        ";
        $stmt = sqlsrv_query($conn, $query, [$nombre]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar tipo de evento */
    public static function actualizar($conn, $id, $nombre) {
        $query = "UPDATE Tipo_Evento SET nombre = ? WHERE id_tipo_evento = ?";
        $stmt = sqlsrv_query($conn, $query, [$nombre, $id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tipo de evento */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Evento WHERE id_tipo_evento = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
