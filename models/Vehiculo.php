<?php
class Vehiculo {

    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                v.id_vehiculo,
                v.placa,
                v.capacidad_kg,
                v.marca,
                v.modelo,
                v.anio,
                v.estado,
                v.fecha_adquisicion,
                tc.nombre_tipo AS tipo_camion
            FROM Vehiculo v
            LEFT JOIN Tipo_Camion tc ON v.id_tipo_camion = tc.id_tipo_camion
            ORDER BY v.id_vehiculo DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $vehiculos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $vehiculos[] = $row;
        }
        return $vehiculos;
    }

    public static function obtenerPorId($conn, $id) {
        $query = "
            SELECT 
                v.*, 
                tc.nombre_tipo AS tipo_camion
            FROM Vehiculo v
            LEFT JOIN Tipo_Camion tc ON v.id_tipo_camion = tc.id_tipo_camion
            WHERE v.id_vehiculo = ?
        ";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Vehiculo (
                id_tipo_camion, placa, capacidad_kg, marca, modelo, anio, estado, fecha_adquisicion
            )
            OUTPUT INSERTED.id_vehiculo
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $params = [
            $data['id_tipo_camion'] ?? null,
            $data['placa'],
            $data['capacidad_kg'] ?? null,
            $data['marca'] ?? null,
            $data['modelo'] ?? null,
            $data['anio'] ?? null,
            $data['estado'] ?? 'Activo',
            $data['fecha_adquisicion'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Vehiculo
            SET id_tipo_camion = ?, placa = ?, capacidad_kg = ?, marca = ?, modelo = ?, 
                anio = ?, estado = ?, fecha_adquisicion = ?
            WHERE id_vehiculo = ?
        ";
        $params = [
            $data['id_tipo_camion'],
            $data['placa'],
            $data['capacidad_kg'],
            $data['marca'],
            $data['modelo'],
            $data['anio'],
            $data['estado'],
            $data['fecha_adquisicion'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Vehiculo WHERE id_vehiculo = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
