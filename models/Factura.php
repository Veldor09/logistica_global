<?php
class Factura
{
    /* ============================================================
       📋 Obtener todas las facturas (con datos de orden y cliente)
    ============================================================ */
    public static function obtenerTodas($conn)
    {
        $query = "
            SELECT 
                f.id_factura,
                f.id_orden,
                f.fecha_emision,
                f.subtotal,
                f.impuesto,
                (f.subtotal + f.impuesto) AS total,     -- <- SOLO SE LEE, NO SE MODIFICA
                f.metodo_pago,
                f.estado,
                COALESCE(
                    NULLIF(LTRIM(RTRIM(CONCAT(cf.nombre, ' ', cf.primer_apellido, ' ', ISNULL(cf.segundo_apellido, '')))), ''),
                    cj.nombre_empresa,
                    c.correo
                ) AS cliente
            FROM Factura f
            INNER JOIN Orden o      ON f.id_orden = o.id_orden
            INNER JOIN Solicitud s  ON o.id_solicitud = s.id_solicitud
            INNER JOIN Cliente c    ON s.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Fisico   cf ON cf.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
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

    /* ============================================================
       🔍 Obtener una factura por su ID (con cliente y total)
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $query = "
            SELECT 
                f.*,
                (f.subtotal + f.impuesto) AS total,     -- <- SOLO SE LEE, NO SE MODIFICA
                COALESCE(
                    NULLIF(LTRIM(RTRIM(CONCAT(cf.nombre, ' ', cf.primer_apellido, ' ', ISNULL(cf.segundo_apellido, '')))), ''),
                    cj.nombre_empresa,
                    c.correo
                ) AS cliente
            FROM Factura f
            INNER JOIN Orden o      ON f.id_orden = o.id_orden
            INNER JOIN Solicitud s  ON o.id_solicitud = s.id_solicitud
            INNER JOIN Cliente c    ON s.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Fisico   cf ON cf.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
            WHERE f.id_factura = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       🧾 Crear nueva factura
       (NO insertar la columna calculada `total`)
    ============================================================ */
    public static function crear($conn, $data)
    {
        $query = "
            INSERT INTO Factura (id_orden, fecha_emision, subtotal, impuesto, metodo_pago, estado)
            OUTPUT INSERTED.id_factura
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_orden'],
            $data['fecha_emision'] ?? date('Y-m-d'),
            (float)($data['subtotal'] ?? 0),
            (float)($data['impuesto'] ?? 0),
            $data['metodo_pago'] ?? null,
            $data['estado'] ?? 'Emitida'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ Actualizar factura
       (NO actualizar la columna calculada `total`)
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $query = "
            UPDATE Factura
            SET id_orden = ?, 
                fecha_emision = ?, 
                subtotal = ?, 
                impuesto = ?, 
                metodo_pago = ?, 
                estado = ?
            WHERE id_factura = ?
        ";

        $params = [
            $data['id_orden'],
            $data['fecha_emision'] ?? date('Y-m-d'),
            (float)($data['subtotal'] ?? 0),
            (float)($data['impuesto'] ?? 0),
            $data['metodo_pago'] ?? null,
            $data['estado'] ?? 'Emitida',
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* ============================================================
       🗑️ Eliminar factura
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $query = "DELETE FROM Factura WHERE id_factura = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
