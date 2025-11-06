<?php
class DetalleFactura
{
    /* ============================================================
       📋 Obtener todos los detalles de una factura
    ============================================================ */
    public static function obtenerPorFactura($conn, $id_factura)
    {
        $sql = "
            SELECT 
                id_detalle,
                id_factura,
                concepto,
                cantidad,
                precio_unitario,
                (cantidad * precio_unitario) AS total_linea
            FROM Detalle_Factura
            WHERE id_factura = ?
            ORDER BY id_detalle ASC
        ";

        $stmt = sqlsrv_query($conn, $sql, [$id_factura]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $detalles = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $detalles[] = $row;
        }
        return $detalles;
    }

    /* ============================================================
       🧾 Crear un nuevo detalle de factura
    ============================================================ */
    public static function crear($conn, $id_factura, $concepto, $cantidad, $precio_unitario)
    {
        // Normalización de datos
        $concepto = trim((string) $concepto);
        $cantidad = (int) $cantidad;
        $precio_unitario = (float) $precio_unitario;

        if ($cantidad <= 0 || $precio_unitario < 0) {
            throw new Exception("Valores inválidos en el detalle (cantidad o precio).");
        }

        $sql = "
            INSERT INTO Detalle_Factura (id_factura, concepto, cantidad, precio_unitario)
            VALUES (?, ?, ?, ?)
        ";

        $params = [$id_factura, $concepto, $cantidad, $precio_unitario];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* ============================================================
       🗑️ Eliminar todos los detalles de una factura
    ============================================================ */
    public static function eliminarPorFactura($conn, $id_factura)
    {
        $sql = "DELETE FROM Detalle_Factura WHERE id_factura = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id_factura]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
