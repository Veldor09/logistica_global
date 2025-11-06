<?php
// ============================================================
// 📊 MODELO: ReporteEficiencia.php
// ============================================================

if (!class_exists('ReporteEficiencia')) {
class ReporteEficiencia
{
    /* ============================================================
       📋 Obtener todos los reportes
    ============================================================ */
    public static function obtenerTodos($conn)
    {
        $sql = "
            SELECT 
                r.id_reporte,
                r.id_viaje,
                r.fecha_generacion,
                r.total_ordenes,
                r.total_km,
                r.eficiencia_porcentaje,
                r.observaciones,
                v.kilometros_recorridos,
                v.combustible_usado_litros,
                ve.placa AS vehiculo,
                (c.nombre + ' ' + c.apellido1) AS conductor
            FROM Reporte_Eficiencia r
            LEFT JOIN Viaje v ON r.id_viaje = v.id_viaje
            LEFT JOIN Vehiculo ve ON v.id_vehiculo = ve.id_vehiculo
            LEFT JOIN Conductor c ON v.id_conductor = c.id_conductor
            ORDER BY r.fecha_generacion DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($r['fecha_generacion'] instanceof DateTime) {
                $r['fecha_generacion'] = $r['fecha_generacion']->format('Y-m-d H:i');
            }
            $rows[] = $r;
        }
        return $rows;
    }

    /* ============================================================
       🔍 Obtener un reporte por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "
            SELECT 
                r.*, 
                v.kilometros_recorridos, 
                v.combustible_usado_litros,
                ve.placa AS vehiculo,
                (c.nombre + ' ' + c.apellido1) AS conductor
            FROM Reporte_Eficiencia r
            LEFT JOIN Viaje v ON r.id_viaje = v.id_viaje
            LEFT JOIN Vehiculo ve ON v.id_vehiculo = ve.id_vehiculo
            LEFT JOIN Conductor c ON v.id_conductor = c.id_conductor
            WHERE r.id_reporte = ?
        ";

        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($r && $r['fecha_generacion'] instanceof DateTime)
            $r['fecha_generacion'] = $r['fecha_generacion']->format('Y-m-d H:i');
        return $r ?: null;
    }

    /* ============================================================
       🧮 Generar nuevo reporte automático
    ============================================================ */
    public static function generar($conn, $id_viaje)
    {
        // 🔹 Datos del viaje
        $sqlV = "
            SELECT id_viaje, id_conductor, id_vehiculo, kilometros_recorridos, combustible_usado_litros
            FROM Viaje WHERE id_viaje = ?
        ";
        $stmtV = sqlsrv_query($conn, $sqlV, [$id_viaje]);
        if (!$stmtV) throw new Exception(print_r(sqlsrv_errors(), true));
        $viaje = sqlsrv_fetch_array($stmtV, SQLSRV_FETCH_ASSOC);
        if (!$viaje) throw new Exception("Viaje no encontrado.");

        $km = (float)($viaje['kilometros_recorridos'] ?? 0);
        $litros = (float)($viaje['combustible_usado_litros'] ?? 0);
        // 🔸 Eficiencia: km / litros (sin multiplicar por 100, ya que representa km por litro)
        $eficiencia = ($litros > 0) ? round(($km / $litros), 2) : 0.0;

        // 🔹 Contar órdenes asociadas desde Orden_Viaje
        $sqlO = "SELECT COUNT(*) AS total FROM Orden_Viaje WHERE id_viaje = ?";
        $stmtO = sqlsrv_query($conn, $sqlO, [$id_viaje]);
        if (!$stmtO) throw new Exception(print_r(sqlsrv_errors(), true));
        $rowO = sqlsrv_fetch_array($stmtO, SQLSRV_FETCH_ASSOC);
        $totalOrdenes = (int)($rowO['total'] ?? 0);

        // 🔹 Insertar reporte
        $sql = "
            INSERT INTO Reporte_Eficiencia 
                (id_viaje, id_vehiculo, id_conductor, total_ordenes, total_km, eficiencia_porcentaje, observaciones)
            OUTPUT INSERTED.id_reporte
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $id_viaje,
            $viaje['id_vehiculo'],
            $viaje['id_conductor'],
            $totalOrdenes,
            $km,
            $eficiencia,
            'Generado automáticamente'
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        return $row ? (int)$row['id_reporte'] : null;
    }

    /* ============================================================
       🗑️ Eliminar reporte
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $sql = "DELETE FROM Reporte_Eficiencia WHERE id_reporte = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }
}
}
?>
