<?php
require_once __DIR__ . '/ParticipanteSolicitud.php';

class Solicitud {

    /* ============================================================
       📋 Obtener todas las solicitudes (con remitente y destinatario)
    ============================================================ */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                s.id_solicitud,
                s.tipo_servicio,
                s.descripcion,
                s.origen,
                s.destino_general,
                s.estado,
                s.prioridad,
                s.fecha_solicitud,
                remitente.correo AS correo_remitente,
                remitente.tipo_identificacion AS tipo_remitente,
                destinatario.correo AS correo_destinatario,
                destinatario.tipo_identificacion AS tipo_destinatario
            FROM Solicitud s
            INNER JOIN Cliente remitente ON remitente.id_cliente = s.id_cliente
            LEFT JOIN Participante_Solicitud psDest 
                ON psDest.id_solicitud = s.id_solicitud AND psDest.rol = 'Destinatario'
            LEFT JOIN Cliente destinatario 
                ON destinatario.id_cliente = psDest.id_cliente
            ORDER BY s.id_solicitud DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $solicitudes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }

    /* ============================================================
       🔍 Obtener una solicitud por ID (con remitente y destinatario)
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $query = "
            SELECT 
                s.*,
                remitente.correo AS correo_remitente,
                remitente.tipo_identificacion AS tipo_remitente,
                destinatario.id_cliente AS id_destinatario,
                destinatario.correo AS correo_destinatario,
                destinatario.tipo_identificacion AS tipo_destinatario
            FROM Solicitud s
            INNER JOIN Cliente remitente ON remitente.id_cliente = s.id_cliente
            LEFT JOIN Participante_Solicitud psDest 
                ON psDest.id_solicitud = s.id_solicitud AND psDest.rol = 'Destinatario'
            LEFT JOIN Cliente destinatario 
                ON destinatario.id_cliente = psDest.id_cliente
            WHERE s.id_solicitud = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       🟢 Crear solicitud con remitente y destinatario
    ============================================================ */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Solicitud (
                id_cliente, tipo_servicio, descripcion, origen, destino_general, 
                estado, prioridad, observaciones, fecha_solicitud
            )
            OUTPUT INSERTED.id_solicitud
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, SYSDATETIME())
        ";

        $params = [
            $data['id_cliente'], // Remitente
            $data['tipo_servicio'],
            $data['descripcion'] ?? null,
            $data['origen'] ?? null,
            $data['destino_general'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['prioridad'] ?? 'Normal',
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        $idSolicitud = sqlsrv_get_field($stmt, 0);

        // ✅ Asociar destinatario si fue seleccionado
        if (!empty($data['id_destinatario']) && $data['id_destinatario'] != $data['id_cliente']) {
            ParticipanteSolicitud::crear($conn, [
                'id_solicitud' => $idSolicitud,
                'id_cliente' => $data['id_destinatario'],
                'rol' => 'Destinatario',
                'observaciones' => 'Destinatario principal de la solicitud'
            ]);
        }

        return $idSolicitud;
    }

    /* ============================================================
       ✏️ Actualizar solicitud y destinatario
    ============================================================ */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Solicitud
            SET 
                tipo_servicio = ?, 
                descripcion = ?, 
                origen = ?, 
                destino_general = ?, 
                estado = ?, 
                prioridad = ?, 
                observaciones = ?
            WHERE id_solicitud = ?
        ";

        $params = [
            $data['tipo_servicio'],
            $data['descripcion'] ?? null,
            $data['origen'] ?? null,
            $data['destino_general'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['prioridad'] ?? 'Normal',
            $data['observaciones'] ?? null,
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        // ✅ Actualizar destinatario si corresponde
        if (!empty($data['id_destinatario'])) {
            // Eliminar destinatario previo
            $del = "DELETE FROM Participante_Solicitud WHERE id_solicitud = ? AND rol = 'Destinatario'";
            sqlsrv_query($conn, $del, [$id]);

            // Evitar duplicar remitente como destinatario
            if ($data['id_destinatario'] != $data['id_cliente']) {
                ParticipanteSolicitud::crear($conn, [
                    'id_solicitud' => $id,
                    'id_cliente' => $data['id_destinatario'],
                    'rol' => 'Destinatario',
                    'observaciones' => 'Destinatario actualizado'
                ]);
            }
        }
    }

    /* ============================================================
       🔴 Eliminar solicitud y sus participantes
    ============================================================ */
    public static function eliminar($conn, $id) {
        // Borrar participantes asociados primero
        $delPs = "DELETE FROM Participante_Solicitud WHERE id_solicitud = ?";
        sqlsrv_query($conn, $delPs, [$id]);

        // Luego borrar la solicitud
        $query = "DELETE FROM Solicitud WHERE id_solicitud = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);

        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
