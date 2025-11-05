<?php
class Documento {

    /* Obtener todos los documentos */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                d.id_documento,
                d.nombre,
                d.tipo,
                d.ruta_archivo,
                d.fecha_subida,
                d.estado,
                u.nombre AS usuario,
                o.id_orden,
                c.id_cliente,
                v.id_viaje
            FROM Documento d
            LEFT JOIN Usuario_Sistema u ON d.id_usuario = u.id_usuario
            LEFT JOIN Orden o ON d.id_orden = o.id_orden
            LEFT JOIN Cliente c ON d.id_cliente = c.id_cliente
            LEFT JOIN Viaje v ON d.id_viaje = v.id_viaje
            ORDER BY d.fecha_subida DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $documentos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $documentos[] = $row;
        }
        return $documentos;
    }

    /* Obtener un documento específico */
    public static function obtenerPorId($conn, $idDocumento) {
        $query = "SELECT * FROM Documento WHERE id_documento = ?";
        $stmt = sqlsrv_query($conn, $query, [$idDocumento]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear un nuevo documento */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Documento (
                nombre, tipo, ruta_archivo, id_usuario, id_orden, 
                id_cliente, id_viaje, estado, fecha_subida
            )
            OUTPUT INSERTED.id_documento
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, SYSDATETIME())
        ";

        $params = [
            $data['nombre'],
            $data['tipo'],
            $data['ruta_archivo'],
            $data['id_usuario'] ?? null,
            $data['id_orden'] ?? null,
            $data['id_cliente'] ?? null,
            $data['id_viaje'] ?? null,
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar documento */
    public static function actualizar($conn, $idDocumento, $data) {
        $query = "
            UPDATE Documento
            SET nombre = ?, tipo = ?, ruta_archivo = ?, estado = ?, observaciones = NULL
            WHERE id_documento = ?
        ";

        $params = [
            $data['nombre'],
            $data['tipo'],
            $data['ruta_archivo'],
            $data['estado'],
            $idDocumento
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Cambiar estado del documento (Activo / Inactivo / Eliminado) */
    public static function cambiarEstado($conn, $idDocumento, $nuevoEstado) {
        $query = "UPDATE Documento SET estado = ? WHERE id_documento = ?";
        $stmt = sqlsrv_query($conn, $query, [$nuevoEstado, $idDocumento]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar documento físicamente */
    public static function eliminar($conn, $idDocumento) {
        $query = "DELETE FROM Documento WHERE id_documento = ?";
        $stmt = sqlsrv_query($conn, $query, [$idDocumento]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
