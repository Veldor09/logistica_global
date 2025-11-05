<?php
class Ruta {

    /* Obtener todas las rutas */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_ruta,
                nombre_ruta,
                origen,
                destino,
                distancia_total_km,
                tiempo_estimado_hr,
                estado
            FROM Ruta
            ORDER BY id_ruta DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $rutas = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rutas[] = $row;
        }
        return $rutas;
    }

    /* Obtener ruta por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Ruta WHERE id_ruta = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nueva ruta */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Ruta (
                nombre_ruta, origen, destino, distancia_total_km, tiempo_estimado_hr, estado
            )
            OUTPUT INSERTED.id_ruta
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $params = [
            $data['nombre_ruta'],
            $data['origen'] ?? null,
            $data['destino'] ?? null,
            $data['distancia_total_km'] ?? null,
            $data['tiempo_estimado_hr'] ?? null,
            $data['estado'] ?? 'Activa'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar una ruta existente */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Ruta
            SET nombre_ruta = ?, origen = ?, destino = ?, 
                distancia_total_km = ?, tiempo_estimado_hr = ?, estado = ?
            WHERE id_ruta = ?
        ";
        $params = [
            $data['nombre_ruta'],
            $data['origen'],
            $data['destino'],
            $data['distancia_total_km'],
            $data['tiempo_estimado_hr'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar ruta */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Ruta WHERE id_ruta = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
