<?php
class Mantenimiento {

    /* ===========================================================
       📋 Obtener todos los mantenimientos (con nombre de vehículo y tipo)
    ============================================================ */
    public static function obtenerTodos($conn) {
        $sql = "
            SELECT 
                m.id_mantenimiento,
                v.placa AS vehiculo,
                tm.nombre AS tipo_mantenimiento,
                m.fecha,
                m.descripcion,
                m.costo,
                m.estado
            FROM Mantenimiento m
            INNER JOIN Vehiculo v ON m.id_vehiculo = v.id_vehiculo
            INNER JOIN Tipo_Mantenimiento tm ON m.id_tipo_mantenimiento = tm.id_tipo_mantenimiento
            ORDER BY m.fecha DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    /* ===========================================================
       ➕ Insertar mantenimiento
    ============================================================ */
    public static function insertar($conn, $data) {
        $sql = "
            INSERT INTO Mantenimiento 
            (id_vehiculo, id_tipo_mantenimiento, fecha, descripcion, costo, estado, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_vehiculo'],
            $data['id_tipo_mantenimiento'],
            $data['fecha'] ?? date('Y-m-d'),
            $data['descripcion'] ?? null,
            $data['costo'] ?? 0,
            $data['estado'] ?? 'Activo',
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ===========================================================
       🔍 Obtener mantenimiento por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $sql = "
            SELECT * FROM Mantenimiento WHERE id_mantenimiento = ?
        ";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ===========================================================
       ✏️ Actualizar mantenimiento
    ============================================================ */
    public static function actualizar($conn, $data) {
        $sql = "
            UPDATE Mantenimiento
            SET id_vehiculo = ?, id_tipo_mantenimiento = ?, fecha = ?, descripcion = ?, 
                costo = ?, estado = ?, observaciones = ?
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
            $data['id_mantenimiento']
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ===========================================================
       🗑️ Eliminar mantenimiento
    ============================================================ */
    public static function eliminar($conn, $id) {
        $sql = "DELETE FROM Mantenimiento WHERE id_mantenimiento = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }
}
?>
