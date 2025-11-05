<?php
class PlanificacionCarga {

    /* Obtener todas las planificaciones con detalles de carga y vehículo */
    public static function obtenerTodas($conn) {
        $query = "
            SELECT 
                p.id_planificacion,
                p.id_carga,
                p.id_vehiculo,
                p.distribucion_porcentaje,
                p.balance_eje,
                v.placa,
                c.peso_kg,
                c.volumen_m3,
                c.descripcion AS descripcion_carga
            FROM Planificacion_Carga p
            INNER JOIN Vehiculo v ON p.id_vehiculo = v.id_vehiculo
            INNER JOIN Carga c ON p.id_carga = c.id_carga
            ORDER BY p.id_planificacion DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $planificaciones = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $planificaciones[] = $row;
        }
        return $planificaciones;
    }

    /* Obtener planificación por ID */
    public static function obtenerPorId($conn, $idPlanificacion) {
        $query = "SELECT * FROM Planificacion_Carga WHERE id_planificacion = ?";
        $stmt = sqlsrv_query($conn, $query, [$idPlanificacion]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Obtener planificación por carga */
    public static function obtenerPorCarga($conn, $idCarga) {
        $query = "
            SELECT 
                p.id_planificacion,
                p.id_vehiculo,
                v.placa,
                p.distribucion_porcentaje,
                p.balance_eje
            FROM Planificacion_Carga p
            INNER JOIN Vehiculo v ON p.id_vehiculo = v.id_vehiculo
            WHERE p.id_carga = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$idCarga]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $result = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    /* Crear planificación */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Planificacion_Carga (id_carga, id_vehiculo, distribucion_porcentaje, balance_eje)
            OUTPUT INSERTED.id_planificacion
            VALUES (?, ?, ?, ?)
        ";

        $params = [
            $data['id_carga'],
            $data['id_vehiculo'],
            $data['distribucion_porcentaje'] ?? null,
            $data['balance_eje'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar planificación */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Planificacion_Carga
            SET id_carga = ?, id_vehiculo = ?, distribucion_porcentaje = ?, balance_eje = ?
            WHERE id_planificacion = ?
        ";

        $params = [
            $data['id_carga'],
            $data['id_vehiculo'],
            $data['distribucion_porcentaje'],
            $data['balance_eje'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar planificación */
    public static function eliminar($conn, $idPlanificacion) {
        $query = "DELETE FROM Planificacion_Carga WHERE id_planificacion = ?";
        $stmt = sqlsrv_query($conn, $query, [$idPlanificacion]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
