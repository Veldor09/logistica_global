<?php
class Incidente {

    /* Obtener todos los incidentes (con datos del viaje y conductor) */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                i.id_incidente,
                i.id_viaje,
                i.tipo_incidente,
                i.descripcion,
                i.gravedad,
                i.fecha_reporte,
                i.estado,
                c.nombre + ' ' + c.apellido1 + ' ' + ISNULL(c.apellido2, '') AS conductor,
                v.placa,
                i.descripcion
            FROM Incidente i
            INNER JOIN Viaje j ON i.id_viaje = j.id_viaje
            INNER JOIN Conductor c ON j.id_conductor = c.id_conductor
            INNER JOIN Vehiculo v ON j.id_vehiculo = v.id_vehiculo
            ORDER BY i.fecha_reporte DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $incidentes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $incidentes[] = $row;
        }
        return $incidentes;
    }

    /* Obtener incidentes por viaje */
    public static function obtenerPorViaje($conn, $idViaje) {
        $query = "
            SELECT 
                id_incidente,
                tipo_incidente,
                descripcion,
                gravedad,
                fecha_reporte,
                estado
            FROM Incidente
            WHERE id_viaje = ?
            ORDER BY fecha_reporte DESC
        ";

        $stmt = sqlsrv_query($conn, $query, [$idViaje]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $result = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    /* Obtener un incidente por ID */
    public static function obtenerPorId($conn, $idIncidente) {
        $query = "SELECT * FROM Incidente WHERE id_incidente = ?";
        $stmt = sqlsrv_query($conn, $query, [$idIncidente]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear un nuevo incidente */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Incidente 
                (id_viaje, tipo_incidente, descripcion, gravedad, fecha_reporte, estado)
            OUTPUT INSERTED.id_incidente
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_viaje'],
            $data['tipo_incidente'],
            $data['descripcion'] ?? null,
            $data['gravedad'],
            $data['fecha_reporte'] ?? date('Y-m-d H:i:s'),
            $data['estado'] ?? 'Abierto'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar un incidente */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Incidente
            SET tipo_incidente = ?, descripcion = ?, gravedad = ?, fecha_reporte = ?, estado = ?
            WHERE id_incidente = ?
        ";

        $params = [
            $data['tipo_incidente'],
            $data['descripcion'],
            $data['gravedad'],
            $data['fecha_reporte'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar incidente */
    public static function eliminar($conn, $idIncidente) {
        $query = "DELETE FROM Incidente WHERE id_incidente = ?";
        $stmt = sqlsrv_query($conn, $query, [$idIncidente]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
