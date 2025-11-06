<?php
// ============================================================
// 📁 models/Viaje.php
// Gestión de viajes (compatible con múltiples órdenes)
// ============================================================

if (!class_exists('Viaje')) {
class Viaje
{
    /* ============================================================
       📋 Obtener todos los viajes (con ruta, conductor y vehículo)
    ============================================================ */
    public static function obtenerTodos($conn)
    {
        $sql = "
            SELECT 
                v.id_viaje,
                v.id_conductor,
                v.id_vehiculo,
                v.id_ruta,
                r.nombre_ruta,
                v.fecha_inicio,
                v.fecha_fin,
                v.kilometros_recorridos,
                v.combustible_usado_litros,
                v.estado,
                (c.nombre + ' ' + c.apellido1) AS conductor,
                ve.placa AS vehiculo
            FROM Viaje v
            LEFT JOIN Conductor c ON v.id_conductor = c.id_conductor
            LEFT JOIN Vehiculo ve ON v.id_vehiculo = ve.id_vehiculo
            LEFT JOIN Ruta r ON v.id_ruta = r.id_ruta
            ORDER BY v.id_viaje DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $viajes = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($r['fecha_inicio'] instanceof DateTime)
                $r['fecha_inicio'] = $r['fecha_inicio']->format('Y-m-d H:i');
            if ($r['fecha_fin'] instanceof DateTime)
                $r['fecha_fin'] = $r['fecha_fin']->format('Y-m-d H:i');
            $viajes[] = $r;
        }
        return $viajes;
    }

    /* ============================================================
       🔍 Obtener un viaje por ID (detalle completo)
       → Usado tanto para “editar” como para “ver detalle”
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "
            SELECT 
                v.id_viaje,
                v.id_conductor,
                v.id_vehiculo,
                v.id_ruta,
                v.fecha_inicio,
                v.fecha_fin,
                v.kilometros_recorridos,
                v.combustible_usado_litros,
                v.observaciones,
                v.estado,

                -- 👷 Conductor
                (c.nombre + ' ' + c.apellido1 + ISNULL(' ' + c.apellido2, '')) AS conductor,

                -- 🚗 Vehículo
                ve.placa AS vehiculo,

                -- 🗺️ Ruta
                r.nombre_ruta,
                r.origen,
                r.destino,
                r.distancia_total_km,
                r.tiempo_estimado_hr
            FROM Viaje v
            LEFT JOIN Conductor c ON v.id_conductor = c.id_conductor
            LEFT JOIN Vehiculo ve ON v.id_vehiculo = ve.id_vehiculo
            LEFT JOIN Ruta r ON v.id_ruta = r.id_ruta
            WHERE v.id_viaje = ?
        ";

        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($r && $r['fecha_inicio'] instanceof DateTime)
            $r['fecha_inicio'] = $r['fecha_inicio']->format('Y-m-d\TH:i');
        if ($r && $r['fecha_fin'] instanceof DateTime)
            $r['fecha_fin'] = $r['fecha_fin']->format('Y-m-d\TH:i');
        return $r ?: null;
    }

    /* ============================================================
       🆕 Crear nuevo viaje
    ============================================================ */
    public static function crear($conn, $data)
    {
        $fechaInicio = !empty($data['fecha_inicio']) ? str_replace('T', ' ', $data['fecha_inicio']) : null;
        $fechaFin = !empty($data['fecha_fin']) ? str_replace('T', ' ', $data['fecha_fin']) : null;

        $sql = "
            INSERT INTO Viaje (
                id_conductor, id_vehiculo, id_ruta, 
                fecha_inicio, fecha_fin, kilometros_recorridos, 
                combustible_usado_litros, observaciones, estado
            )
            OUTPUT INSERTED.id_viaje
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_conductor'],
            $data['id_vehiculo'],
            $data['id_ruta'] ?? null,
            $fechaInicio,
            $fechaFin,
            $data['kilometros_recorridos'] ?? null,
            $data['combustible_usado_litros'] ?? null,
            $data['observaciones'] ?? null,
            $data['estado'] ?? 'Pendiente'
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ Actualizar viaje
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $fechaInicio = !empty($data['fecha_inicio']) ? str_replace('T', ' ', $data['fecha_inicio']) : null;
        $fechaFin = !empty($data['fecha_fin']) ? str_replace('T', ' ', $data['fecha_fin']) : null;

        $sql = "
            UPDATE Viaje
            SET 
                id_conductor = ?, 
                id_vehiculo = ?, 
                id_ruta = ?, 
                fecha_inicio = ?, 
                fecha_fin = ?, 
                kilometros_recorridos = ?, 
                combustible_usado_litros = ?, 
                observaciones = ?, 
                estado = ?
            WHERE id_viaje = ?
        ";

        $params = [
            $data['id_conductor'],
            $data['id_vehiculo'],
            $data['id_ruta'],
            $fechaInicio,
            $fechaFin,
            $data['kilometros_recorridos'],
            $data['combustible_usado_litros'],
            $data['observaciones'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ============================================================
       🗑️ Eliminar viaje
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $stmt = sqlsrv_query($conn, "DELETE FROM Viaje WHERE id_viaje = ?", [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }
}}
?>
