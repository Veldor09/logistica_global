<?php
class TramoRuta {

    /* Obtener todos los tramos (con nombre de ruta y tipo de carretera) */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                tr.id_tramo,
                tr.id_ruta,
                tr.id_tipo_carretera,
                tr.orden_tramo,
                tr.punto_inicio,
                tr.punto_fin,
                tr.distancia_km,
                tr.tiempo_estimado_hr,
                tr.observaciones,
                r.nombre_ruta,
                tc.nombre AS tipo_carretera
            FROM Tramo_Ruta tr
            INNER JOIN Ruta r ON tr.id_ruta = r.id_ruta
            LEFT JOIN Tipo_Carretera tc ON tr.id_tipo_carretera = tc.id_tipo_carretera
            ORDER BY r.nombre_ruta, tr.orden_tramo
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tramos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tramos[] = $row;
        }
        return $tramos;
    }

    /* Obtener tramos de una ruta específica */
    public static function obtenerPorRuta($conn, $idRuta) {
        $query = "
            SELECT 
                tr.*, 
                tc.nombre AS tipo_carretera
            FROM Tramo_Ruta tr
            LEFT JOIN Tipo_Carretera tc ON tr.id_tipo_carretera = tc.id_tipo_carretera
            WHERE tr.id_ruta = ?
            ORDER BY tr.orden_tramo
        ";
        $stmt = sqlsrv_query($conn, $query, [$idRuta]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $tramos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tramos[] = $row;
        }
        return $tramos;
    }

    /* Crear nuevo tramo */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tramo_Ruta (
                id_ruta, id_tipo_carretera, orden_tramo, 
                punto_inicio, punto_fin, distancia_km, tiempo_estimado_hr, observaciones
            )
            OUTPUT INSERTED.id_tramo
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $params = [
            $data['id_ruta'],
            $data['id_tipo_carretera'] ?? null,
            $data['orden_tramo'],
            $data['punto_inicio'],
            $data['punto_fin'],
            $data['distancia_km'] ?? null,
            $data['tiempo_estimado_hr'] ?? null,
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar tramo existente */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tramo_Ruta
            SET id_ruta = ?, id_tipo_carretera = ?, orden_tramo = ?, 
                punto_inicio = ?, punto_fin = ?, distancia_km = ?, 
                tiempo_estimado_hr = ?, observaciones = ?
            WHERE id_tramo = ?
        ";
        $params = [
            $data['id_ruta'],
            $data['id_tipo_carretera'],
            $data['orden_tramo'],
            $data['punto_inicio'],
            $data['punto_fin'],
            $data['distancia_km'],
            $data['tiempo_estimado_hr'],
            $data['observaciones'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tramo */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tramo_Ruta WHERE id_tramo = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
