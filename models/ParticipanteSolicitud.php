<?php
class ParticipanteSolicitud {

    /* Obtener todos los participantes */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT ps.*, 
                   s.tipo_servicio, 
                   s.estado AS estado_solicitud, 
                   c.correo AS correo_cliente, 
                   c.telefono AS telefono_cliente
            FROM Participante_Solicitud ps
            INNER JOIN Solicitud s ON ps.id_solicitud = s.id_solicitud
            INNER JOIN Cliente c ON ps.id_cliente = c.id_cliente
            ORDER BY ps.id_participante DESC
        ";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $participantes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $participantes[] = $row;
        }
        return $participantes;
    }

    /* Obtener participantes por solicitud */
    public static function obtenerPorSolicitud($conn, $idSolicitud) {
        $query = "
            SELECT ps.*, c.tipo_identificacion, c.correo, c.telefono
            FROM Participante_Solicitud ps
            INNER JOIN Cliente c ON ps.id_cliente = c.id_cliente
            WHERE ps.id_solicitud = ?
        ";
        $stmt = sqlsrv_query($conn, $query, [$idSolicitud]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $participantes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $participantes[] = $row;
        }
        return $participantes;
    }

    /* Crear participante */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Participante_Solicitud (id_solicitud, id_cliente, rol, observaciones)
            OUTPUT INSERTED.id_participante
            VALUES (?, ?, ?, ?)
        ";
        $params = [
            $data['id_solicitud'],
            $data['id_cliente'],
            $data['rol'],
            $data['observaciones'] ?? null
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar participante */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Participante_Solicitud
            SET rol = ?, observaciones = ?
            WHERE id_participante = ?
        ";
        $params = [
            $data['rol'],
            $data['observaciones'],
            $id
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar participante */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Participante_Solicitud WHERE id_participante = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
