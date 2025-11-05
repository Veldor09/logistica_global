<?php
class IntegracionSistema {

    /* Obtener todas las integraciones registradas */
    public static function obtenerTodas($conn) {
        $query = "
            SELECT 
                id_integracion,
                nombre_sistema,
                tipo,
                endpoint,
                estado,
                ultima_sincronizacion,
                observaciones
            FROM Integracion_Sistema
            ORDER BY id_integracion DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $integraciones = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $integraciones[] = $row;
        }
        return $integraciones;
    }

    /* Obtener una integración por ID */
    public static function obtenerPorId($conn, $idIntegracion) {
        $query = "SELECT * FROM Integracion_Sistema WHERE id_integracion = ?";
        $stmt = sqlsrv_query($conn, $query, [$idIntegracion]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear una nueva integración */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Integracion_Sistema 
                (nombre_sistema, tipo, endpoint, estado, ultima_sincronizacion, observaciones)
            OUTPUT INSERTED.id_integracion
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $params = [
            $data['nombre_sistema'],
            $data['tipo'],
            $data['endpoint'] ?? null,
            $data['estado'] ?? 'Activo',
            $data['ultima_sincronizacion'] ?? null,
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar una integración */
    public static function actualizar($conn, $idIntegracion, $data) {
        $query = "
            UPDATE Integracion_Sistema
            SET nombre_sistema = ?, tipo = ?, endpoint = ?, estado = ?, 
                ultima_sincronizacion = ?, observaciones = ?
            WHERE id_integracion = ?
        ";

        $params = [
            $data['nombre_sistema'],
            $data['tipo'],
            $data['endpoint'],
            $data['estado'],
            $data['ultima_sincronizacion'],
            $data['observaciones'],
            $idIntegracion
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Marcar integración como sincronizada */
    public static function actualizarSincronizacion($conn, $idIntegracion) {
        $query = "
            UPDATE Integracion_Sistema
            SET ultima_sincronizacion = SYSDATETIME()
            WHERE id_integracion = ?
        ";

        $stmt = sqlsrv_query($conn, $query, [$idIntegracion]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar integración */
    public static function eliminar($conn, $idIntegracion) {
        $query = "DELETE FROM Integracion_Sistema WHERE id_integracion = ?";
        $stmt = sqlsrv_query($conn, $query, [$idIntegracion]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
