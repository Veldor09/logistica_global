<?php
// models/Ruta.php
if (!class_exists('Ruta')) {
class Ruta
{
    /* ============================================================
       Listar todas las rutas (incluye conteo de tramos si existen)
    ============================================================ */
    public static function obtenerTodas($conn): array
    {
        $sql = "
            SELECT 
                r.id_ruta,
                r.nombre_ruta,
                r.origen,
                r.destino,
                r.distancia_total_km,
                r.tiempo_estimado_hr,
                r.estado,
                COUNT(t.id_tramo) AS total_tramos
            FROM Ruta r
            LEFT JOIN Tramo_Ruta t ON t.id_ruta = r.id_ruta
            GROUP BY 
                r.id_ruta, r.nombre_ruta, r.origen, r.destino,
                r.distancia_total_km, r.tiempo_estimado_hr, r.estado
            ORDER BY r.id_ruta DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) {
            throw new \Exception('SQL Error (Ruta::obtenerTodas): '.print_r(sqlsrv_errors(), true));
        }

        $rows = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }

    /* ============================================================
       Listar solo rutas ACTIVAS (para selects en Viajes)
    ============================================================ */
    public static function obtenerActivas($conn): array
    {
        $sql = "
            SELECT 
                id_ruta,
                nombre_ruta,
                origen,
                destino,
                distancia_total_km,
                tiempo_estimado_hr,
                estado
            FROM Ruta
            WHERE estado = 'Activa'
            ORDER BY nombre_ruta ASC, id_ruta DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) {
            throw new \Exception('SQL Error (Ruta::obtenerActivas): ' . print_r(sqlsrv_errors(), true));
        }

        $rows = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }

    /* ============================================================
       Obtener una ruta por ID
    ============================================================ */
    public static function obtenerPorId($conn, int $id_ruta): ?array
    {
        $sql = "
            SELECT TOP 1 
                id_ruta, nombre_ruta, origen, destino,
                distancia_total_km, tiempo_estimado_hr, estado
            FROM Ruta
            WHERE id_ruta = ?
        ";
        $stmt = sqlsrv_query($conn, $sql, [$id_ruta]);
        if (!$stmt) {
            throw new \Exception('SQL Error (Ruta::obtenerPorId): '.print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row ?: null;
    }

    /* ============================================================
       Crear ruta (retorna id insertado)
    ============================================================ */
    public static function crear($conn, array $data): int
    {
        $sql = "
            INSERT INTO Ruta
              (nombre_ruta, origen, destino, distancia_total_km, tiempo_estimado_hr, estado)
            OUTPUT Inserted.id_ruta
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $params = [
            trim((string)($data['nombre_ruta'] ?? '')),
            $data['origen']   !== '' ? $data['origen']   : null,
            $data['destino']  !== '' ? $data['destino']  : null,
            ($data['distancia_total_km'] ?? '') !== '' ? (float)$data['distancia_total_km'] : null,
            ($data['tiempo_estimado_hr'] ?? '') !== '' ? (float)$data['tiempo_estimado_hr'] : null,
            $data['estado'] ?? 'Activa',
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new \Exception('SQL Error (Ruta::crear): '.print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return (int)($row['id_ruta'] ?? 0);
    }

    /* ============================================================
       Actualizar ruta
    ============================================================ */
    public static function actualizar($conn, int $id_ruta, array $data): bool
    {
        $sql = "
            UPDATE Ruta
            SET
              nombre_ruta = ?,
              origen = ?,
              destino = ?,
              distancia_total_km = ?,
              tiempo_estimado_hr = ?,
              estado = ?
            WHERE id_ruta = ?
        ";

        $params = [
            trim((string)($data['nombre_ruta'] ?? '')),
            $data['origen']   !== '' ? $data['origen']   : null,
            $data['destino']  !== '' ? $data['destino']  : null,
            ($data['distancia_total_km'] ?? '') !== '' ? (float)$data['distancia_total_km'] : null,
            ($data['tiempo_estimado_hr'] ?? '') !== '' ? (float)$data['tiempo_estimado_hr'] : null,
            $data['estado'] ?? 'Activa',
            $id_ruta,
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new \Exception('SQL Error (Ruta::actualizar): '.print_r(sqlsrv_errors(), true));
        }
        return sqlsrv_rows_affected($stmt) !== false;
    }

    /* ============================================================
       Eliminar ruta (hard delete; con FK CASCADE en Tramo_Ruta)
    ============================================================ */
    public static function eliminar($conn, int $id_ruta): bool
    {
        $sql = "DELETE FROM Ruta WHERE id_ruta = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id_ruta]);
        if (!$stmt) {
            throw new \Exception('SQL Error (Ruta::eliminar): '.print_r(sqlsrv_errors(), true));
        }
        return sqlsrv_rows_affected($stmt) !== false;
    }
}}
