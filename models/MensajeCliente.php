<?php
class MensajeCliente {

    /* Obtener todos los mensajes */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                m.id_mensaje,
                m.asunto,
                m.contenido,
                m.medio,
                m.estado,
                m.fecha_envio,
                c.id_cliente,
                c.correo AS correo_cliente,
                u.nombre AS usuario_emisor
            FROM Mensaje_Cliente m
            INNER JOIN Cliente c ON m.id_cliente = c.id_cliente
            LEFT JOIN Usuario_Sistema u ON m.id_usuario = u.id_usuario
            ORDER BY m.fecha_envio DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $mensajes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $mensajes[] = $row;
        }
        return $mensajes;
    }

    /* Obtener un mensaje por ID */
    public static function obtenerPorId($conn, $idMensaje) {
        $query = "
            SELECT *
            FROM Mensaje_Cliente
            WHERE id_mensaje = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$idMensaje]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear un nuevo mensaje */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Mensaje_Cliente (
                id_cliente, id_usuario, asunto, contenido, medio, estado, fecha_envio
            )
            OUTPUT INSERTED.id_mensaje
            VALUES (?, ?, ?, ?, ?, ?, SYSDATETIME())
        ";

        $params = [
            $data['id_cliente'],
            $data['id_usuario'] ?? null,
            $data['asunto'],
            $data['contenido'],
            $data['medio'] ?? 'Interno',
            $data['estado'] ?? 'Enviado'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar un mensaje */
    public static function actualizar($conn, $idMensaje, $data) {
        $query = "
            UPDATE Mensaje_Cliente
            SET asunto = ?, contenido = ?, medio = ?, estado = ?
            WHERE id_mensaje = ?
        ";

        $params = [
            $data['asunto'],
            $data['contenido'],
            $data['medio'],
            $data['estado'],
            $idMensaje
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Cambiar estado (Enviado, Leído, Eliminado, etc.) */
    public static function cambiarEstado($conn, $idMensaje, $nuevoEstado) {
        $query = "UPDATE Mensaje_Cliente SET estado = ? WHERE id_mensaje = ?";
        $stmt = sqlsrv_query($conn, $query, [$nuevoEstado, $idMensaje]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar mensaje */
    public static function eliminar($conn, $idMensaje) {
        $query = "DELETE FROM Mensaje_Cliente WHERE id_mensaje = ?";
        $stmt = sqlsrv_query($conn, $query, [$idMensaje]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
