<?php
// models/Evento.php

if (!class_exists('Evento')) {
class Evento
{
    public static function obtenerTodos($conn)
    {
        $query = "
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

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $eventos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Convertir fechas a string legible
            if (isset($row['fecha']) && $row['fecha'] instanceof DateTime) {
                $row['fecha'] = $row['fecha']->format('Y-m-d H:i:s');
            }
            $eventos[] = $row;
        }

        return $eventos;
    }

    public static function obtenerPorViaje($conn, $idViaje)
    {
        $query = "
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

        $stmt = sqlsrv_query($conn, $query, [$idViaje]);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $eventos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (isset($row['fecha']) && $row['fecha'] instanceof DateTime) {
                $row['fecha'] = $row['fecha']->format('Y-m-d H:i:s');
            }
            $eventos[] = $row;
        }

        return $eventos;
    }

    public static function obtenerPorId($conn, $idEvento)
    {
        $query = "
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

        $stmt = sqlsrv_query($conn, $query, [$idEvento]);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row && $row['fecha'] instanceof DateTime) {
            $row['fecha'] = $row['fecha']->format('Y-m-d H:i:s');
        }
        return $row ?: null;
    }

    public static function crear($conn, $data)
    {
        $query = "
            INSERT INTO Evento (id_viaje, id_tipo_evento, fecha, observaciones, ubicacion, estado)
            OUTPUT INSERTED.id_evento
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        // Si no se especifica fecha, usar la del sistema SQL
        $fecha = $data['fecha'] ?? null;
        if ($fecha === null || $fecha === '') {
            $fecha = date('Y-m-d H:i:s');
        }

        $params = [
            $data['id_viaje'],
            $data['id_tipo_evento'],
            $fecha,
            $data['observaciones'] ?? null,
            $data['ubicacion'] ?? null,
            $data['estado'] ?? 'Registrado'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row ? (int)$row['id_evento'] : null;
    }

    public static function actualizar($conn, $idEvento, $data)
    {
        $query = "
            UPDATE Evento
            SET id_viaje = ?, id_tipo_evento = ?, fecha = ?, observaciones = ?, ubicacion = ?, estado = ?
            WHERE id_evento = ?
        ";

        $params = [
            $data['id_viaje'],
            $data['id_tipo_evento'],
            $data['fecha'],
            $data['observaciones'],
            $data['ubicacion'],
            $data['estado'],
            $idEvento
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        return true;
    }

    public static function eliminar($conn, $idEvento)
    {
        $query = "DELETE FROM Evento WHERE id_evento = ?";
        $stmt = sqlsrv_query($conn, $query, [$idEvento]);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        return true;
    }
}
}
?>
