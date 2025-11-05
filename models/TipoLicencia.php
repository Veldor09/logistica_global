<?php
class TipoLicencia {

    /* Obtener todas las licencias */
    public static function obtenerTodos($conn) {
        $query = "SELECT * FROM Tipo_Licencia ORDER BY id_tipo_licencia DESC";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* Obtener licencia por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Licencia WHERE id_tipo_licencia = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nueva categoría de licencia */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Licencia (categoria, descripcion, vigencia_anios)
            OUTPUT INSERTED.id_tipo_licencia
            VALUES (?, ?, ?)
        ";
        $params = [
            $data['categoria'],
            $data['descripcion'] ?? null,
            $data['vigencia_anios'] ?? null
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar tipo de licencia */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Licencia
            SET categoria = ?, descripcion = ?, vigencia_anios = ?
            WHERE id_tipo_licencia = ?
        ";
        $params = [
            $data['categoria'],
            $data['descripcion'],
            $data['vigencia_anios'],
            $id
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tipo de licencia */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Licencia WHERE id_tipo_licencia = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
