<?php
class Factura {

    /* Obtener todas las facturas con información de la orden */
    public static function obtenerTodas($conn) {
        $query = "
            SELECT 
                f.id_factura,
                f.id_orden,
                f.fecha_emision,
                f.subtotal,
                f.impuesto,
                f.total,
                f.metodo_pago,
                f.estado,
                o.estado AS estado_orden
            FROM Factura f
            INNER JOIN Orden o ON f.id_orden = o.id_orden
            ORDER BY f.id_factura DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $facturas = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $facturas[] = $row;
        }
        return $facturas;
    }

    /* Obtener factura por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "
            SELECT 
                f.*, 
                o.estado AS estado_orden
            FROM Factura f
            INNER JOIN Orden o ON f.id_orden = o.id_orden
            WHERE f.id_factura = ?
        ";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nueva factura */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Factura (id_orden, fecha_emision, subtotal, impuesto, metodo_pago, estado)
            OUTPUT INSERTED.id_factura
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_orden'],
            $data['fecha_emision'] ?? date('Y-m-d'),
            $data['subtotal'] ?? 0,
            $data['impuesto'] ?? 0,
            $data['metodo_pago'] ?? null,
            $data['estado'] ?? 'Emitida'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar factura */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Factura
            SET id_orden = ?, fecha_emision = ?, subtotal = ?, impuesto = ?, 
                metodo_pago = ?, estado = ?
            WHERE id_factura = ?
        ";

        $params = [
            $data['id_orden'],
            $data['fecha_emision'],
            $data['subtotal'],
            $data['impuesto'],
            $data['metodo_pago'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar factura */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Factura WHERE id_factura = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
