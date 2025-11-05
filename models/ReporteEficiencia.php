<?php
class ReporteEficiencia {

    /* Obtener todos los reportes con información de vehículo, conductor y viaje */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                r.id_reporte,
                r.id_viaje,
                r.id_vehiculo,
                r.id_conductor,
                r.fecha_generacion,
                r.total_ordenes,
                r.total_km,
                r.eficiencia_porcentaje,
                r.observaciones,
                v.placa,
                c.nombre + ' ' + c.apellido1 + ' ' + ISNULL(c.apellido2, '') AS conductor,
                j.id_orden
            FROM Reporte_Eficiencia r
            LEFT JOIN Vehiculo v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN Conductor c ON r.id_conductor = c.id_conductor
            LEFT JOIN Viaje j ON r.id_viaje = j.id_viaje
            ORDER BY r.fecha_generacion DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $reportes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $reportes[] = $row;
        }
        return $reportes;
    }

    /* Obtener reporte por ID */
    public static function obtenerPorId($conn, $idReporte) {
        $query = "SELECT * FROM Reporte_Eficiencia WHERE id_reporte = ?";
        $stmt = sqlsrv_query($conn, $query, [$idReporte]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear reporte */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Reporte_Eficiencia 
                (id_viaje, id_vehiculo, id_conductor, fecha_generacion, total_ordenes, total_km, eficiencia_porcentaje, observaciones)
            OUTPUT INSERTED.id_reporte
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_viaje'] ?? null,
            $data['id_vehiculo'] ?? null,
            $data['id_conductor'] ?? null,
            $data['fecha_generacion'] ?? date('Y-m-d'),
            $data['total_ordenes'] ?? 0,
            $data['total_km'] ?? 0,
            $data['eficiencia_porcentaje'] ?? null,
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar reporte */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Reporte_Eficiencia
            SET id_viaje = ?, id_vehiculo = ?, id_conductor = ?, 
                total_ordenes = ?, total_km = ?, eficiencia_porcentaje = ?, observaciones = ?
            WHERE id_reporte = ?
        ";

        $params = [
            $data['id_viaje'],
            $data['id_vehiculo'],
            $data['id_conductor'],
            $data['total_ordenes'],
            $data['total_km'],
            $data['eficiencia_porcentaje'],
            $data['observaciones'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar reporte */
    public static function eliminar($conn, $idReporte) {
        $query = "DELETE FROM Reporte_Eficiencia WHERE id_reporte = ?";
        $stmt = sqlsrv_query($conn, $query, [$idReporte]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
