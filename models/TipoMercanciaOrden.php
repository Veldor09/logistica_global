<?php
class TipoMercanciaOrden {

public static function obtenerTodos($conn)
{
    $sql = "
        SELECT 
            o.id_orden,
            o.id_solicitud,
            o.direccion_origen,
            o.direccion_destino,
            o.peso_estimado_kg,
            o.fecha_carga,
            o.fecha_entrega_estimada,
            o.estado,
            o.observaciones,
            s.origen AS solicitud_origen,
            s.destino_general AS solicitud_destino,
            -- Tipo de mercancía (último registrado)
            ISNULL(tm.nombre, 'No asignado') AS tipo_mercancia,
            -- Volumen total calculado
            ISNULL(SUM(tmo.volumen_total_m3), 0) AS volumen_total_m3
        FROM Orden o
        INNER JOIN Solicitud s ON o.id_solicitud = s.id_solicitud
        LEFT JOIN Tipo_Mercancia_Orden tmo ON o.id_orden = tmo.id_orden
        LEFT JOIN Tipo_Mercancia tm ON tmo.id_tipo_mercancia = tm.id_tipo_mercancia
        GROUP BY 
            o.id_orden, o.id_solicitud, o.direccion_origen, o.direccion_destino, 
            o.peso_estimado_kg, o.fecha_carga, o.fecha_entrega_estimada, 
            o.estado, o.observaciones, s.origen, s.destino_general, tm.nombre
        ORDER BY o.id_orden DESC
    ";

    $stmt = sqlsrv_query($conn, $sql);
    if (!$stmt) throw new Exception("Error al obtener las órdenes: " . print_r(sqlsrv_errors(), true));

    $rows = [];
    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $r;
    }
    return $rows;
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

/* ============================================================
   ➕ Crear relación entre mercancía y orden
   - Calcula automáticamente el volumen total si no se pasa.
   - Corrige el nombre del campo: volumen_total_m3
============================================================ */
public static function crear($conn, $data) {
    // 🧮 Calcular volumen automáticamente si no se proporciona
    if (empty($data['volumen_total_m3']) && !empty($data['id_tipo_mercancia'])) {
        $sqlVol = "SELECT volumen_unitario_m3 FROM Tipo_Mercancia WHERE id_tipo_mercancia = ?";
        $stmtVol = sqlsrv_query($conn, $sqlVol, [$data['id_tipo_mercancia']]);
        if ($stmtVol && ($r = sqlsrv_fetch_array($stmtVol, SQLSRV_FETCH_ASSOC))) {
            $volUnit = (float)$r['volumen_unitario_m3'];
            $data['volumen_total_m3'] = $volUnit * ($data['cantidad'] ?? 1);
        } else {
            $data['volumen_total_m3'] = 0;
        }
    }

    $query = "
        INSERT INTO Tipo_Mercancia_Orden (
            id_orden, id_tipo_mercancia, cantidad, peso_total_kg, volumen_total_m3
        )
        OUTPUT INSERTED.id_tipo_mercancia_orden
        VALUES (?, ?, ?, ?, ?)
    ";

    $params = [
        (int)$data['id_orden'],
        (int)$data['id_tipo_mercancia'],
        $data['cantidad'] ?? 1,
        $data['peso_total_kg'] ?? null,
        $data['volumen_total_m3'] ?? 0
    ];

    $stmt = sqlsrv_query($conn, $query, $params);
    if (!$stmt) throw new Exception("Error al crear Tipo_Mercancia_Orden: " . print_r(sqlsrv_errors(), true));

    sqlsrv_fetch($stmt);
    return sqlsrv_get_field($stmt, 0);
}

/* ============================================================
   ✏️ Actualizar registro existente
   - También recalcula el volumen automáticamente si no se pasa.
============================================================ */
public static function actualizar($conn, $id, $data) {
    // 🧮 Calcular volumen automáticamente si no se proporciona
    if (empty($data['volumen_total_m3']) && !empty($data['id_tipo_mercancia'])) {
        $sqlVol = "SELECT volumen_unitario_m3 FROM Tipo_Mercancia WHERE id_tipo_mercancia = ?";
        $stmtVol = sqlsrv_query($conn, $sqlVol, [$data['id_tipo_mercancia']]);
        if ($stmtVol && ($r = sqlsrv_fetch_array($stmtVol, SQLSRV_FETCH_ASSOC))) {
            $volUnit = (float)$r['volumen_unitario_m3'];
            $data['volumen_total_m3'] = $volUnit * ($data['cantidad'] ?? 1);
        } else {
            $data['volumen_total_m3'] = 0;
        }
    }

    $query = "
        UPDATE Tipo_Mercancia_Orden
        SET 
            id_orden = ?, 
            id_tipo_mercancia = ?, 
            cantidad = ?, 
            peso_total_kg = ?, 
            volumen_total_m3 = ?
        WHERE id_tipo_mercancia_orden = ?
    ";

    $params = [
        (int)$data['id_orden'],
        (int)$data['id_tipo_mercancia'],
        $data['cantidad'] ?? 1,
        $data['peso_total_kg'] ?? null,
        $data['volumen_total_m3'] ?? 0,
        (int)$id
    ];

    $stmt = sqlsrv_query($conn, $query, $params);
    if (!$stmt) throw new Exception("Error al actualizar Tipo_Mercancia_Orden: " . print_r(sqlsrv_errors(), true));
}


    /* Eliminar registro */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Mercancia_Orden WHERE id_tipo_mercancia_orden = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
