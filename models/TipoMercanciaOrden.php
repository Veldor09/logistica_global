<?php
class TipoMercanciaOrden {

    /* Obtener todas las mercancías asociadas a órdenes (con detalles) */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                tmo.id_tipo_mercancia_orden,
                tmo.id_orden,
                tmo.id_tipo_mercancia,
                tmo.cantidad,
                tmo.peso_total_kg,
                tmo.volumen_m3,
                tm.nombre AS tipo_mercancia,
                tm.descripcion,
                o.estado AS estado_orden
            FROM Tipo_Mercancia_Orden tmo
            INNER JOIN Tipo_Mercancia tm ON tmo.id_tipo_mercancia = tm.id_tipo_mercancia
            INNER JOIN Orden o ON tmo.id_orden = o.id_orden
            ORDER BY tmo.id_tipo_mercancia_orden DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $items = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $items[] = $row;
        }
        return $items;
    }

    /* Obtener mercancías por orden específica */
    public static function obtenerPorOrden($conn, $idOrden) {
        $query = "
            SELECT 
                tmo.*, 
                tm.nombre AS tipo_mercancia,
                tm.descripcion
            FROM Tipo_Mercancia_Orden tmo
            INNER JOIN Tipo_Mercancia tm ON tmo.id_tipo_mercancia = tm.id_tipo_mercancia
            WHERE tmo.id_orden = ?
        ";
        $stmt = sqlsrv_query($conn, $query, [$idOrden]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $items = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $items[] = $row;
        }
        return $items;
    }

    /* Crear relación entre mercancía y orden */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Mercancia_Orden (
                id_orden, id_tipo_mercancia, cantidad, peso_total_kg, volumen_m3
            )
            OUTPUT INSERTED.id_tipo_mercancia_orden
            VALUES (?, ?, ?, ?, ?)
        ";
        $params = [
            $data['id_orden'],
            $data['id_tipo_mercancia'],
            $data['cantidad'] ?? 1,
            $data['peso_total_kg'] ?? null,
            $data['volumen_m3'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar registro existente */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Mercancia_Orden
            SET id_orden = ?, id_tipo_mercancia = ?, cantidad = ?, 
                peso_total_kg = ?, volumen_m3 = ?
            WHERE id_tipo_mercancia_orden = ?
        ";
        $params = [
            $data['id_orden'],
            $data['id_tipo_mercancia'],
            $data['cantidad'],
            $data['peso_total_kg'],
            $data['volumen_m3'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar registro */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Mercancia_Orden WHERE id_tipo_mercancia_orden = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
