<?php
// ============================================================
// 📦 MODELO: Carga.php
// Corrige el cálculo de volumen y muestra datos completos
// ============================================================

if (!class_exists('Carga')) {
class Carga
{
    /* =======================================================
       📊 Obtener resumen de cargas agrupado por viaje
       Incluye peso total y volumen calculado correctamente
    ======================================================= */
public static function obtenerTodas($conn)
{
    $sql = "
        WITH VolumenesPorOrden AS (
            SELECT 
                id_orden, 
                SUM(CAST(ISNULL(volumen_total_m3, 0) AS FLOAT)) AS volumen_total_m3
            FROM Tipo_Mercancia_Orden
            GROUP BY id_orden
        )
        SELECT 
            v.id_viaje,
            ISNULL(r.nombre_ruta, '-') AS nombre_ruta,
            ISNULL(ve.placa, '-')     AS placa,
            v.fecha_inicio,
            COUNT(DISTINCT ov.id_orden)                                AS total_ordenes,
            CAST(SUM(ISNULL(o.peso_estimado_kg, 0)) AS FLOAT)          AS peso_total_kg,
            CAST(SUM(ISNULL(vo.volumen_total_m3, 0)) AS FLOAT)         AS volumen_total_m3
        FROM Viaje v
        LEFT JOIN Ruta r                ON v.id_ruta = r.id_ruta
        LEFT JOIN Vehiculo ve           ON v.id_vehiculo = ve.id_vehiculo
        LEFT JOIN Orden_Viaje ov        ON v.id_viaje = ov.id_viaje
        LEFT JOIN Orden o               ON ov.id_orden = o.id_orden
        LEFT JOIN VolumenesPorOrden vo  ON o.id_orden = vo.id_orden
        GROUP BY v.id_viaje, r.nombre_ruta, ve.placa, v.fecha_inicio
        ORDER BY v.id_viaje DESC;
    ";

    $stmt = sqlsrv_query($conn, $sql);
    if (!$stmt) {
        throw new Exception("Error al obtener cargas: " . print_r(sqlsrv_errors(), true));
    }

    $rows = [];
    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = [
            'id_viaje'        => (int)$r['id_viaje'],
            'ruta'            => $r['nombre_ruta'],        // string
            'vehiculo'        => $r['placa'],              // string
            'fecha_inicio'    => $r['fecha_inicio'] instanceof DateTime
                                  ? $r['fecha_inicio']->format('Y-m-d H:i')
                                  : $r['fecha_inicio'],
            'total_ordenes'   => (int)$r['total_ordenes'],
            'peso_total_kg'   => (float)$r['peso_total_kg'],     // <- crudo (float)
            'volumen_total_m3'=> (float)$r['volumen_total_m3'],  // <- crudo (float)
        ];
    }
    return $rows;
}





    /* =======================================================
       🔍 Obtener detalle de carga por viaje
       Incluye peso y volumen total por orden
    ======================================================= */
    public static function obtenerPorViaje($conn, $idViaje)
    {
        $sql = "
            SELECT 
                o.id_orden,
                ISNULL(o.direccion_origen, '-') AS direccion_origen,
                ISNULL(o.direccion_destino, '-') AS direccion_destino,
                ISNULL(o.peso_estimado_kg, 0) AS peso_estimado_kg,
                ISNULL(tm.nombre, 'No asignado') AS tipo_mercancia,
                SUM(ISNULL(tmo.volumen_total_m3, 0)) AS volumen_total_m3
            FROM Orden_Viaje ov
            INNER JOIN Orden o ON ov.id_orden = o.id_orden
            LEFT JOIN Tipo_Mercancia_Orden tmo ON o.id_orden = tmo.id_orden
            LEFT JOIN Tipo_Mercancia tm ON tmo.id_tipo_mercancia = tm.id_tipo_mercancia
            WHERE ov.id_viaje = ?
            GROUP BY 
                o.id_orden,
                o.direccion_origen,
                o.direccion_destino,
                o.peso_estimado_kg,
                tm.nombre
            ORDER BY o.id_orden ASC
        ";

        $stmt = sqlsrv_query($conn, $sql, [$idViaje]);
        if (!$stmt) {
            throw new Exception("Error al obtener detalle de carga: " . print_r(sqlsrv_errors(), true));
        }

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = [
                'id_orden' => $r['id_orden'],
                'direccion_origen' => $r['direccion_origen'],
                'direccion_destino' => $r['direccion_destino'],
                'peso_estimado_kg' => number_format((float)$r['peso_estimado_kg'], 2),
                'tipo_mercancia' => $r['tipo_mercancia'],
                'volumen_total_m3' => number_format((float)$r['volumen_total_m3'], 2),
            ];
        }

        return $rows;
    }

    /* =======================================================
       🆕 Registrar carga manual
    ======================================================= */
    public static function crear($conn, $data)
    {
        $sql = "
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

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* =======================================================
       ✏️ Actualizar carga manual
    ======================================================= */
    public static function actualizar($conn, $id, $data)
    {
        $sql = "
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

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* =======================================================
       🗑️ Eliminar carga
    ======================================================= */
    public static function eliminar($conn, $id)
    {
        $sql = "DELETE FROM Carga WHERE id_carga = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
}
?>
