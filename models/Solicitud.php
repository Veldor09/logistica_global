<?php
// ============================================================
// 📄 models/Solicitud.php
// Módulo central de gestión de solicitudes de transporte
// ============================================================

require_once __DIR__ . '/ParticipanteSolicitud.php';

class Solicitud
{
    /* ============================================================
       📋 1. Obtener solicitudes disponibles (para generar órdenes)
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
        if (!$stmt) {
            throw new Exception('Error al obtener solicitudes disponibles: ' . print_r(sqlsrv_errors(), true));
        }

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $r;
        }

        return $rows;
    }

    /* ============================================================
       📋 2. Listar todas las solicitudes (con remitente/destinatario)
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

                -- 🔹 Remitente
                cRem.id_cliente AS id_remitente,
                ISNULL(cfRem.nombre, cjRem.nombre_empresa) AS nombre_remitente,
                ISNULL(cfRem.cedula, cjRem.cedula_juridica) AS cedula_remitente,
                cRem.correo AS correo_remitente,
                cRem.tipo_identificacion AS tipo_remitente,

                -- 🔹 Destinatario
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
        if (!$stmt) {
            throw new Exception('Error al obtener solicitudes: ' . print_r(sqlsrv_errors(), true));
        }

        $solicitudes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (isset($row['fecha_solicitud']) && $row['fecha_solicitud'] instanceof DateTime) {
                $row['fecha_solicitud'] = $row['fecha_solicitud']->format('Y-m-d H:i:s');
            }
            $solicitudes[] = $row;
        }

        return $solicitudes;
    }

    /* ============================================================
       🔍 3. Obtener solicitud por ID
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
        if (!$stmt) {
            throw new Exception('Error al obtener solicitud: ' . print_r(sqlsrv_errors(), true));
        }

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       🆕 4. Crear solicitud (modo interno - valida existencia)
    ============================================================ */
    public static function crear($conn, $data)
    {
        // 🧩 Validar remitente
        $checkRem = sqlsrv_query($conn, "SELECT id_cliente FROM Cliente WHERE id_cliente = ?", [$data['id_cliente']]);
        if (!$checkRem || !sqlsrv_fetch_array($checkRem, SQLSRV_FETCH_ASSOC)) {
            throw new Exception('El cliente remitente seleccionado no existe.');
        }

        // 🧩 Validar destinatario (si existe)
        if (!empty($data['id_destinatario'])) {
            $checkDest = sqlsrv_query($conn, "SELECT id_cliente FROM Cliente WHERE id_cliente = ?", [$data['id_destinatario']]);
            if (!$checkDest || !sqlsrv_fetch_array($checkDest, SQLSRV_FETCH_ASSOC)) {
                throw new Exception('El cliente destinatario seleccionado no existe.');
            }
        }

        // 🧾 Insertar solicitud
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
        if (!$stmt) {
            throw new Exception('Error al crear solicitud: ' . print_r(sqlsrv_errors(), true));
        }

        sqlsrv_fetch($stmt);
        $idSolicitud = sqlsrv_get_field($stmt, 0);

        // 👥 Registrar destinatario (si aplica)
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
       🌐 5. Crear solicitud pública (visitantes sin sesión)
    ============================================================ */
    public static function crearPublica($conn, $data)
    {
        $correo = trim($data['correo'] ?? '');
        if (empty($correo)) {
            throw new Exception('Debe ingresar un correo válido.');
        }

        // 🔎 Buscar cliente existente
        $queryCheck = "SELECT id_cliente FROM Cliente WHERE correo = ?";
        $stmtCheck = sqlsrv_query($conn, $queryCheck, [$correo]);
        if (!$stmtCheck) {
            throw new Exception('Error al buscar cliente: ' . print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
        if ($row) {
            $idCliente = $row['id_cliente'];
        } else {
            // ➕ Crear nuevo cliente básico
            $sqlInsertCliente = "
                INSERT INTO Cliente (tipo_identificacion, correo, telefono, estado)
                OUTPUT INSERTED.id_cliente
                VALUES ('Física', ?, ?, 'Activo')
            ";
            $stmtInsert = sqlsrv_query($conn, $sqlInsertCliente, [$correo, $data['telefono'] ?? null]);
            if (!$stmtInsert) {
                throw new Exception('Error al crear cliente público: ' . print_r(sqlsrv_errors(), true));
            }
            sqlsrv_fetch($stmtInsert);
            $idCliente = sqlsrv_get_field($stmtInsert, 0);

            // 🧾 Crear registro mínimo en Cliente_Fisico
            $sqlCF = "
                INSERT INTO Cliente_Fisico (id_cliente, nombre, primer_apellido, cedula)
                VALUES (?, ?, ?, ?)
            ";
            $paramsCF = [
                $idCliente,
                $data['nombre'] ?? 'Cliente',
                $data['primer_apellido'] ?? '(Sin Apellido)',
                $data['cedula'] ?? ('CF' . rand(1000, 9999))
            ];
            sqlsrv_query($conn, $sqlCF, $paramsCF);
        }

        // 🧾 Crear solicitud
        $sqlSolicitud = "
            INSERT INTO Solicitud (
                id_cliente, tipo_servicio, descripcion, origen, destino_general, 
                estado, prioridad, observaciones, fecha_solicitud
            )
            OUTPUT INSERTED.id_solicitud
            VALUES (?, ?, ?, ?, ?, 'Pendiente', 'Normal', ?, SYSDATETIME())
        ";
        $paramsSolicitud = [
            $idCliente,
            $data['tipo_servicio'],
            $data['descripcion'] ?? null,
            $data['origen'] ?? null,
            $data['destino_general'] ?? null,
            $data['observaciones'] ?? null
        ];

        $stmtSol = sqlsrv_query($conn, $sqlSolicitud, $paramsSolicitud);
        if (!$stmtSol) {
            throw new Exception('Error al crear solicitud pública: ' . print_r(sqlsrv_errors(), true));
        }

        sqlsrv_fetch($stmtSol);
        return sqlsrv_get_field($stmtSol, 0);
    }

    /* ============================================================
       ✏️ 6. Actualizar solicitud
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        // 🔍 Verificar destinatario
        if (!empty($data['id_destinatario'])) {
            $checkDest = sqlsrv_query($conn, "SELECT id_cliente FROM Cliente WHERE id_cliente = ?", [$data['id_destinatario']]);
            if (!$checkDest || !sqlsrv_fetch_array($checkDest, SQLSRV_FETCH_ASSOC)) {
                throw new Exception('El cliente destinatario no existe.');
            }
        }

        // 🧾 Actualizar solicitud
        $query = "
            UPDATE Solicitud
            SET tipo_servicio = ?, descripcion = ?, origen = ?, destino_general = ?, 
                estado = ?, prioridad = ?, observaciones = ?
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
        if (!$stmt) {
            throw new Exception('Error al actualizar solicitud: ' . print_r(sqlsrv_errors(), true));
        }

        // 👥 Reasignar destinatario
        sqlsrv_query($conn, "DELETE FROM Participante_Solicitud WHERE id_solicitud = ? AND rol = 'Destinatario'", [$id]);
        if (!empty($data['id_destinatario']) && $data['id_destinatario'] != $data['id_cliente']) {
            ParticipanteSolicitud::crear($conn, [
                'id_solicitud'  => $id,
                'id_cliente'    => $data['id_destinatario'],
                'rol'           => 'Destinatario',
                'observaciones' => 'Destinatario actualizado'
            ]);
        }

        return true;
    }

    /* ============================================================
       🔴 7. Eliminar solicitud
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        sqlsrv_query($conn, "DELETE FROM Participante_Solicitud WHERE id_solicitud = ?", [$id]);
        $stmt = sqlsrv_query($conn, "DELETE FROM Solicitud WHERE id_solicitud = ?", [$id]);
        if (!$stmt) {
            throw new Exception('Error al eliminar solicitud: ' . print_r(sqlsrv_errors(), true));
        }
        return true;
    }
}
?>
