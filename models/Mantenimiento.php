<?php
class Mantenimiento {

    /* Obtener todos los mantenimientos (con vehículo y tipo de mantenimiento) */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                m.id_mantenimiento,
                m.id_vehiculo,
                m.id_tipo_mantenimiento,
                v.placa,
                tm.nombre AS tipo_mantenimiento,
                m.fecha,
                m.descripcion,
                m.costo,
                m.estado,
                m.observaciones
            FROM Mantenimiento m
            INNER JOIN Vehiculo v ON m.id_vehiculo = v.id_vehiculo
            INNER JOIN Tipo_Mantenimiento tm ON m.id_tipo_mantenimiento = tm.id_tipo_mantenimiento
            ORDER BY m.id_mantenimiento DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $mantenimientos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $mantenimientos[] = $row;
        }
        return $mantenimientos;
    }

    /* Obtener mantenimientos por vehículo */
    public static function obtenerPorVehiculo($conn, $idVehiculo) {
        $query = "
            SELECT 
                m.*, 
                tm.nombre AS tipo_mantenimiento
            FROM Mantenimiento m
            INNER JOIN Tipo_Mantenimiento tm ON m.id_tipo_mantenimiento = tm.id_tipo_mantenimiento
            WHERE m.id_vehiculo = ?
            ORDER BY m.fecha DESC
        ";

        $stmt = sqlsrv_query($conn, $query, [$idVehiculo]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $result = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    /* Obtener un mantenimiento por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Mantenimiento WHERE id_mantenimiento = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear mantenimiento */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Mantenimiento 
                (id_vehiculo, id_tipo_mantenimiento, fecha, descripcion, costo, estado, observaciones)
            OUTPUT INSERTED.id_mantenimiento
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_vehiculo'],
            $data['id_tipo_mantenimiento'],
            $data['fecha'] ?? date('Y-m-d'),
            $data['descripcion'] ?? null,
            $data['costo'] ?? null,
            $data['estado'] ?? 'Activo',
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar mantenimiento */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Mantenimiento
            SET id_vehiculo = ?, id_tipo_mantenimiento = ?, fecha = ?, 
                descripcion = ?, costo = ?, estado = ?, observaciones = ?
            WHERE id_mantenimiento = ?
        ";

        $params = [
            $data['id_vehiculo'],
            $data['id_tipo_mantenimiento'],
            $data['fecha'],
            $data['descripcion'],
            $data['costo'],
            $data['estado'],
            $data['observaciones'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar mantenimiento */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Mantenimiento WHERE id_mantenimiento = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
