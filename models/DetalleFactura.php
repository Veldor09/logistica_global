<?php
class DetalleFactura {

    /* Obtener todos los detalles de una factura */
    public static function obtenerPorFactura($conn, $idFactura) {
        $query = "
            SELECT 
                id_detalle,
                id_factura,
                concepto,
                cantidad,
                precio_unitario,
                total_linea
            FROM Detalle_Factura
            WHERE id_factura = ?
            ORDER BY id_detalle ASC
        ";

        $stmt = sqlsrv_query($conn, $query, [$idFactura]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $detalles = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $detalles[] = $row;
        }
        return $detalles;
    }

    /* Obtener detalle específico */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Detalle_Factura WHERE id_detalle = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo detalle */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Detalle_Factura (id_factura, concepto, cantidad, precio_unitario)
            OUTPUT INSERTED.id_detalle
            VALUES (?, ?, ?, ?)
        ";

        $params = [
            $data['id_factura'],
            $data['concepto'],
            $data['cantidad'],
            $data['precio_unitario']
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar detalle */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Detalle_Factura
            SET concepto = ?, cantidad = ?, precio_unitario = ?
            WHERE id_detalle = ?
        ";
        $params = [
            $data['concepto'],
            $data['cantidad'],
            $data['precio_unitario'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar detalle */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Detalle_Factura WHERE id_detalle = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
