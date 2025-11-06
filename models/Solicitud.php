<?php
require_once __DIR__ . '/ParticipanteSolicitud.php';

class Solicitud
{
    /* ============================================================
       📋 Disponibles para crear orden
    ============================================================ */
    public static function obtenerDisponibles($conn)
    {
        $sql = "
            SELECT 
                s.id_solicitud,
                s.origen,
                s.destino_general,
                s.estado
            FROM Solicitud s
            WHERE s.estado = 'Pendiente'
            ORDER BY s.id_solicitud DESC
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) { throw new Exception(print_r(sqlsrv_errors(), true)); }

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $r;
        }
        return $rows;
    }

    /* ============================================================
       📋 Listar todas (con remitente/destinatario)
    ============================================================ */
public static function obtenerTodos($conn)
{
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

            -- 🔹 Datos del remitente (cliente principal)
            cRem.id_cliente AS id_remitente,
            ISNULL(cfRem.nombre, cjRem.nombre_empresa) AS nombre_remitente,
            ISNULL(cfRem.cedula, cjRem.cedula_juridica) AS cedula_remitente,
            cRem.correo AS correo_remitente,
            cRem.tipo_identificacion AS tipo_remitente,

            -- 🔹 Datos del destinatario (desde Participante_Solicitud)
            cDest.id_cliente AS id_destinatario,
            ISNULL(cfDest.nombre, cjDest.nombre_empresa) AS nombre_destinatario,
            ISNULL(cfDest.cedula, cjDest.cedula_juridica) AS cedula_destinatario,
            cDest.correo AS correo_destinatario,
            cDest.tipo_identificacion AS tipo_destinatario

        FROM Solicitud s
        INNER JOIN Cliente cRem ON cRem.id_cliente = s.id_cliente
        LEFT JOIN Cliente_Fisico cfRem ON cfRem.id_cliente = cRem.id_cliente
        LEFT JOIN Cliente_Juridico cjRem ON cjRem.id_cliente = cRem.id_cliente

        LEFT JOIN Participante_Solicitud psDest 
            ON psDest.id_solicitud = s.id_solicitud AND psDest.rol = 'Destinatario'
        LEFT JOIN Cliente cDest ON cDest.id_cliente = psDest.id_cliente
        LEFT JOIN Cliente_Fisico cfDest ON cfDest.id_cliente = cDest.id_cliente
        LEFT JOIN Cliente_Juridico cjDest ON cjDest.id_cliente = cDest.id_cliente

        ORDER BY s.id_solicitud DESC
    ";

    $stmt = sqlsrv_query($conn, $query);
    if (!$stmt) { throw new Exception(print_r(sqlsrv_errors(), true)); }

    $solicitudes = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $solicitudes[] = $row;
    }
    return $solicitudes;
}


    /* ============================================================
       🔍 Obtener por ID (con remitente/destinatario)
    ============================================================ */
public static function obtenerPorId($conn, $id)
{
    $query = "
        SELECT 
            s.*,

            cRem.id_cliente AS id_remitente,
            ISNULL(cfRem.nombre, cjRem.nombre_empresa) AS nombre_remitente,
            ISNULL(cfRem.cedula, cjRem.cedula_juridica) AS cedula_remitente,
            cRem.correo AS correo_remitente,

            cDest.id_cliente AS id_destinatario,
            ISNULL(cfDest.nombre, cjDest.nombre_empresa) AS nombre_destinatario,
            ISNULL(cfDest.cedula, cjDest.cedula_juridica) AS cedula_destinatario,
            cDest.correo AS correo_destinatario

        FROM Solicitud s
        INNER JOIN Cliente cRem ON cRem.id_cliente = s.id_cliente
        LEFT JOIN Cliente_Fisico cfRem ON cfRem.id_cliente = cRem.id_cliente
        LEFT JOIN Cliente_Juridico cjRem ON cjRem.id_cliente = cRem.id_cliente

        LEFT JOIN Participante_Solicitud psDest 
            ON psDest.id_solicitud = s.id_solicitud AND psDest.rol = 'Destinatario'
        LEFT JOIN Cliente cDest ON cDest.id_cliente = psDest.id_cliente
        LEFT JOIN Cliente_Fisico cfDest ON cfDest.id_cliente = cDest.id_cliente
        LEFT JOIN Cliente_Juridico cjDest ON cjDest.id_cliente = cDest.id_cliente

        WHERE s.id_solicitud = ?
    ";
    $stmt = sqlsrv_query($conn, $query, [$id]);
    if (!$stmt) { throw new Exception(print_r(sqlsrv_errors(), true)); }

    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}


    /* ============================================================
       🟢 Crear
    ============================================================ */
    public static function crear($conn, $data)
    {
        $query = "
            INSERT INTO Solicitud (
                id_cliente, tipo_servicio, descripcion, origen, destino_general, 
                estado, prioridad, observaciones, fecha_solicitud
            )
            OUTPUT INSERTED.id_solicitud
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, SYSDATETIME())
        ";
        $params = [
            $data['id_cliente'],
            $data['tipo_servicio'],
            $data['descripcion'] ?? null,
            $data['origen'] ?? null,
            $data['destino_general'] ?? null,
            $data['estado'] ?? 'Pendiente',
            $data['prioridad'] ?? 'Normal',
            $data['observaciones'] ?? null
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) { throw new Exception(print_r(sqlsrv_errors(), true)); }

        sqlsrv_fetch($stmt);
        $idSolicitud = sqlsrv_get_field($stmt, 0);

        if (!empty($data['id_destinatario']) && $data['id_destinatario'] != $data['id_cliente']) {
            ParticipanteSolicitud::crear($conn, [
                'id_solicitud'  => $idSolicitud,
                'id_cliente'    => $data['id_destinatario'],
                'rol'           => 'Destinatario',
                'observaciones' => 'Destinatario principal de la solicitud'
            ]);
        }
        return $idSolicitud;
    }

    /* ============================================================
       ✏️ Actualizar
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
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
        if (!$stmt) { throw new Exception(print_r(sqlsrv_errors(), true)); }

        if (!empty($data['id_destinatario'])) {
            sqlsrv_query($conn, "DELETE FROM Participante_Solicitud WHERE id_solicitud = ? AND rol = 'Destinatario'", [$id]);
            if ($data['id_destinatario'] != $data['id_cliente']) {
                ParticipanteSolicitud::crear($conn, [
                    'id_solicitud'  => $id,
                    'id_cliente'    => $data['id_destinatario'],
                    'rol'           => 'Destinatario',
                    'observaciones' => 'Destinatario actualizado'
                ]);
            }
        }
    }

    /* ============================================================
       🔴 Eliminar
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        sqlsrv_query($conn, "DELETE FROM Participante_Solicitud WHERE id_solicitud = ?", [$id]);
        $stmt = sqlsrv_query($conn, "DELETE FROM Solicitud WHERE id_solicitud = ?", [$id]);
        if (!$stmt) { throw new Exception(print_r(sqlsrv_errors(), true)); }
    }
}
