<?php
class Orden {

    /* ============================================================
       📋 Obtener todas las órdenes con datos de solicitud y clientes
    ============================================================ */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                o.id_orden,
                o.id_solicitud,
                s.tipo_servicio,
                s.origen,
                s.destino_general,
                s.prioridad,
                o.direccion_origen,
                o.direccion_destino,
                o.peso_estimado_kg,
                o.fecha_carga,
                o.fecha_entrega_estimada,
                o.fecha_entrega_real,
                o.estado,
                o.observaciones,
                remitente.correo AS correo_remitente,
                destinatario.correo AS correo_destinatario
            FROM Orden o
            INNER JOIN Solicitud s ON s.id_solicitud = o.id_solicitud
            INNER JOIN Cliente remitente ON remitente.id_cliente = s.id_cliente
            LEFT JOIN Participante_Solicitud psDest 
                ON psDest.id_solicitud = s.id_solicitud AND psDest.rol = 'Destinatario'
            LEFT JOIN Cliente destinatario ON destinatario.id_cliente = psDest.id_cliente
            ORDER BY o.id_orden DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) {
            $error = sqlsrv_errors();
            throw new Exception($error ? json_encode($error) : "Error desconocido al obtener órdenes");
        }

        $ordenes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ordenes[] = $row;
        }
        return $ordenes;
    }

    /* ============================================================
       🔍 Obtener una orden específica por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $query = "
            SELECT 
                o.*, 
                s.tipo_servicio, 
                s.origen, 
                s.destino_general, 
                s.prioridad,
                remitente.correo AS correo_remitente,
                destinatario.correo AS correo_destinatario
            FROM Orden o
            INNER JOIN Solicitud s ON s.id_solicitud = o.id_solicitud
            INNER JOIN Cliente remitente ON remitente.id_cliente = s.id_cliente
            LEFT JOIN Participante_Solicitud psDest 
                ON psDest.id_solicitud = s.id_solicitud AND psDest.rol = 'Destinatario'
            LEFT JOIN Cliente destinatario ON destinatario.id_cliente = psDest.id_cliente
            WHERE o.id_orden = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) {
            $error = sqlsrv_errors();
            throw new Exception($error ? json_encode($error) : "Error desconocido al obtener la orden por ID");
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       🟢 Crear nueva orden
    ============================================================ */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Orden (
                id_solicitud, direccion_origen, direccion_destino, 
                peso_estimado_kg, fecha_carga, fecha_entrega_estimada, 
                estado, observaciones
            )
            OUTPUT INSERTED.id_orden
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['id_solicitud'],
            $data['direccion_origen'] ?? null,
            $data['direccion_destino'] ?? null,
            $data['peso_estimado_kg'] ?? 0,
            $data['fecha_carga'] ?? null,
            $data['fecha_entrega_estimada'] ?? null,
            $data['estado'] ?? 'Programada',
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) {
            $error = sqlsrv_errors();
            throw new Exception($error ? json_encode($error) : "Error desconocido al crear la orden");
        }

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ Actualizar una orden
    ============================================================ */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Orden
            SET 
                direccion_origen = ?, 
                direccion_destino = ?, 
                peso_estimado_kg = ?, 
                fecha_carga = ?, 
                fecha_entrega_estimada = ?, 
                fecha_entrega_real = ?, 
                estado = ?, 
                observaciones = ?
            WHERE id_orden = ?
        ";

        $params = [
            $data['direccion_origen'] ?? null,
            $data['direccion_destino'] ?? null,
            $data['peso_estimado_kg'] ?? 0,
            $data['fecha_carga'] ?? null,
            $data['fecha_entrega_estimada'] ?? null,
            $data['fecha_entrega_real'] ?? null,
            $data['estado'] ?? 'Programada',
            $data['observaciones'] ?? null,
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) {
            $error = sqlsrv_errors();
            throw new Exception($error ? json_encode($error) : "Error desconocido al actualizar la orden");
        }
    }

    /* ============================================================
       🔴 Eliminar una orden
    ============================================================ */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Orden WHERE id_orden = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) {
            $error = sqlsrv_errors();
            throw new Exception($error ? json_encode($error) : "Error desconocido al eliminar la orden");
        }
    }
}
?>
