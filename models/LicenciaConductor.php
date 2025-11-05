<?php
class LicenciaConductor {

    /* Obtener todas las licencias de conductores (con JOIN para detalle) */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                lc.id_licencia_conductor,
                lc.numero_licencia,
                lc.fecha_emision,
                lc.fecha_vencimiento,
                c.id_conductor,
                c.nombre,
                c.apellido1,
                c.apellido2,
                tl.id_tipo_licencia,
                tl.categoria,
                tl.descripcion AS tipo_licencia
            FROM Licencia_Conductor lc
            INNER JOIN Conductor c ON lc.id_conductor = c.id_conductor
            INNER JOIN Tipo_Licencia tl ON lc.id_tipo_licencia = tl.id_tipo_licencia
            ORDER BY lc.id_licencia_conductor DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $licencias = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $licencias[] = $row;
        }
        return $licencias;
    }

    /* Obtener licencia por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "
            SELECT * FROM Licencia_Conductor WHERE id_licencia_conductor = ?
        ";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear una nueva licencia para un conductor */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Licencia_Conductor (
                id_conductor, id_tipo_licencia, numero_licencia, fecha_emision, fecha_vencimiento
            )
            OUTPUT INSERTED.id_licencia_conductor
            VALUES (?, ?, ?, ?, ?)
        ";
        $params = [
            $data['id_conductor'],
            $data['id_tipo_licencia'],
            $data['numero_licencia'],
            $data['fecha_emision'] ?? null,
            $data['fecha_vencimiento'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar una licencia */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Licencia_Conductor
            SET id_conductor = ?, id_tipo_licencia = ?, numero_licencia = ?, 
                fecha_emision = ?, fecha_vencimiento = ?
            WHERE id_licencia_conductor = ?
        ";
        $params = [
            $data['id_conductor'],
            $data['id_tipo_licencia'],
            $data['numero_licencia'],
            $data['fecha_emision'],
            $data['fecha_vencimiento'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar una licencia */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Licencia_Conductor WHERE id_licencia_conductor = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
