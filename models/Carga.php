<?php
class Carga {

    /* Obtener todas las cargas (con datos del viaje y vehículo) */
    public static function obtenerTodas($conn) {
        $query = "
            SELECT 
                c.id_carga,
                c.id_viaje,
                c.peso_kg,
                c.volumen_m3,
                c.descripcion,
                v.id_vehiculo,
                v.placa,
                cond.nombre + ' ' + cond.apellido1 + ' ' + ISNULL(cond.apellido2, '') AS conductor
            FROM Carga c
            INNER JOIN Viaje vj ON c.id_viaje = vj.id_viaje
            INNER JOIN Vehiculo v ON vj.id_vehiculo = v.id_vehiculo
            INNER JOIN Conductor cond ON vj.id_conductor = cond.id_conductor
            ORDER BY c.id_carga DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $cargas = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $cargas[] = $row;
        }
        return $cargas;
    }

    /* Obtener carga por ID */
    public static function obtenerPorId($conn, $idCarga) {
        $query = "SELECT * FROM Carga WHERE id_carga = ?";
        $stmt = sqlsrv_query($conn, $query, [$idCarga]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Obtener cargas de un viaje específico */
    public static function obtenerPorViaje($conn, $idViaje) {
        $query = "
            SELECT id_carga, peso_kg, volumen_m3, descripcion
            FROM Carga
            WHERE id_viaje = ?
            ORDER BY id_carga ASC
        ";
        $stmt = sqlsrv_query($conn, $query, [$idViaje]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $result = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    /* Crear una carga */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Carga (id_viaje, peso_kg, volumen_m3, descripcion)
            OUTPUT INSERTED.id_carga
            VALUES (?, ?, ?, ?)
        ";

        $params = [
            $data['id_viaje'],
            $data['peso_kg'],
            $data['volumen_m3'] ?? null,
            $data['descripcion'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar una carga */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Carga
            SET id_viaje = ?, peso_kg = ?, volumen_m3 = ?, descripcion = ?
            WHERE id_carga = ?
        ";

        $params = [
            $data['id_viaje'],
            $data['peso_kg'],
            $data['volumen_m3'],
            $data['descripcion'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar una carga */
    public static function eliminar($conn, $idCarga) {
        $query = "DELETE FROM Carga WHERE id_carga = ?";
        $stmt = sqlsrv_query($conn, $query, [$idCarga]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
