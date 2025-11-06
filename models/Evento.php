<?php
// models/Evento.php

if (!class_exists('Evento')) {
class Evento
{
    /* =======================================================
       📋 Obtener todos los eventos (con tipo y viaje)
    ======================================================= */
    public static function obtenerTodos($conn)
    {
        $sql = "
            SELECT 
                e.id_evento,
                e.id_viaje,
                e.id_tipo_evento,
                te.nombre AS tipo_evento,
                e.fecha,
                e.observaciones,
                e.ubicacion,
                e.estado,
                v.id_vehiculo,
                v.id_conductor
            FROM Evento e
            INNER JOIN Tipo_Evento te ON e.id_tipo_evento = te.id_tipo_evento
            INNER JOIN Viaje v ON e.id_viaje = v.id_viaje
            ORDER BY e.fecha DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $eventos = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (!empty($r['fecha']) && $r['fecha'] instanceof DateTime) {
                $r['fecha'] = $r['fecha']->format('Y-m-d H:i:s');
            }
            $eventos[] = $r;
        }
        return $eventos;
    }

    /* =======================================================
       🔍 Obtener eventos por viaje
       (para usar en Detalle del Viaje)
    ======================================================= */
    public static function obtenerPorViaje($conn, $idViaje)
    {
        $sql = "
            SELECT 
                e.id_evento,
                e.id_tipo_evento,
                te.nombre AS tipo_evento,
                e.fecha,
                e.observaciones,
                e.ubicacion,
                e.estado
            FROM Evento e
            INNER JOIN Tipo_Evento te ON e.id_tipo_evento = te.id_tipo_evento
            WHERE e.id_viaje = ?
            ORDER BY e.fecha DESC
        ";

        $stmt = sqlsrv_query($conn, $sql, [$idViaje]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $eventos = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (!empty($r['fecha']) && $r['fecha'] instanceof DateTime) {
                $r['fecha'] = $r['fecha']->format('Y-m-d H:i:s');
            }
            $eventos[] = $r;
        }
        return $eventos;
    }

    /* =======================================================
       🔍 Obtener evento por ID
    ======================================================= */
    public static function obtenerPorId($conn, $idEvento)
    {
        $sql = "
            SELECT 
                e.id_evento,
                e.id_viaje,
                e.id_tipo_evento,
                te.nombre AS tipo_evento,
                e.fecha,
                e.observaciones,
                e.ubicacion,
                e.estado
            FROM Evento e
            INNER JOIN Tipo_Evento te ON e.id_tipo_evento = te.id_tipo_evento
            WHERE e.id_evento = ?
        ";

        $stmt = sqlsrv_query($conn, $sql, [$idEvento]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!empty($r['fecha']) && $r['fecha'] instanceof DateTime) {
            $r['fecha'] = $r['fecha']->format('Y-m-d H:i:s');
        }
        return $r ?: null;
    }

    /* =======================================================
       🆕 Crear evento
    ======================================================= */
    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Evento (id_viaje, id_tipo_evento, fecha, observaciones, ubicacion, estado)
            OUTPUT INSERTED.id_evento
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $fecha = $data['fecha'] ?? date('Y-m-d H:i:s');

        $params = [
            $data['id_viaje'],
            $data['id_tipo_evento'],
            $fecha,
            $data['observaciones'] ?? null,
            $data['ubicacion'] ?? null,
            $data['estado'] ?? 'Registrado'
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* =======================================================
       ✏️ Actualizar evento
    ======================================================= */
    public static function actualizar($conn, $idEvento, $data)
    {
        $sql = "
            UPDATE Evento
            SET id_viaje = ?, id_tipo_evento = ?, fecha = ?, observaciones = ?, ubicacion = ?, estado = ?
            WHERE id_evento = ?
        ";

        $params = [
            $data['id_viaje'],
            $data['id_tipo_evento'],
            $data['fecha'] ?? date('Y-m-d H:i:s'),
            $data['observaciones'] ?? null,
            $data['ubicacion'] ?? null,
            $data['estado'] ?? 'Registrado',
            $idEvento
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* =======================================================
       🗑️ Eliminar evento
    ======================================================= */
    public static function eliminar($conn, $idEvento)
    {
        $sql = "DELETE FROM Evento WHERE id_evento = ?";
        $stmt = sqlsrv_query($conn, $sql, [$idEvento]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }
}
}
?>
