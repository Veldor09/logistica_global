<?php
// ============================================================
// ⚠️ MODELO: Incidente.php
// Gestión de incidentes registrados durante un viaje
// ============================================================

if (!class_exists('Incidente')) {
class Incidente
{
    /* ============================================================
       📋 Obtener todos los incidentes (con viaje relacionado)
    ============================================================ */
    public static function obtenerTodos($conn): array
    {
        $sql = "
            SELECT 
                i.id_incidente,
                i.id_viaje,
                v.id_viaje AS viaje_rel,
                v.fecha_inicio,
                v.fecha_fin,
                i.tipo_incidente,
                i.descripcion,
                i.gravedad,
                i.fecha_reporte,
                i.estado
            FROM Incidente i
            LEFT JOIN Viaje v ON v.id_viaje = i.id_viaje
            ORDER BY i.id_incidente DESC
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) {
            throw new \Exception('Error al obtener incidentes: ' . print_r(sqlsrv_errors(), true));
        }

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $r;
        }
        return $rows;
    }

    /* ============================================================
       🔍 Obtener incidente por ID
    ============================================================ */
    public static function obtenerPorId($conn, int $id): ?array
    {
        $sql = "SELECT * FROM Incidente WHERE id_incidente = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) {
            throw new \Exception('Error al obtener incidente: ' . print_r(sqlsrv_errors(), true));
        }
        $r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $r ?: null;
    }

    /* ============================================================
       ➕ Crear nuevo incidente
    ============================================================ */
    public static function crear($conn, array $data): int
    {
        $sql = "
            INSERT INTO Incidente
              (id_viaje, tipo_incidente, descripcion, gravedad, fecha_reporte, estado)
            VALUES (?, ?, ?, ?, SYSDATETIME(), ?);
            SELECT SCOPE_IDENTITY() AS id_incidente;
        ";

        $params = [
            (int)($data['id_viaje'] ?? 0),
            trim($data['tipo_incidente'] ?? 'Desconocido'),
            $data['descripcion'] ?? null,
            $data['gravedad'] ?? 'Media',
            $data['estado'] ?? 'Abierto',
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new \Exception('Error al crear incidente: ' . print_r(sqlsrv_errors(), true));
        }

        // Avanzar al siguiente resultado para obtener el ID
        sqlsrv_next_result($stmt);
        sqlsrv_fetch($stmt);
        $id = sqlsrv_get_field($stmt, 0);

        if (!$id) {
            throw new \Exception('No se pudo obtener el ID del incidente creado.');
        }
        return (int)$id;
    }

    /* ============================================================
       ✏️ Actualizar incidente
    ============================================================ */
    public static function actualizar($conn, int $id, array $data): bool
    {
        $sql = "
            UPDATE Incidente
            SET tipo_incidente = ?,
                descripcion = ?,
                gravedad = ?,
                estado = ?
            WHERE id_incidente = ?
        ";
        $params = [
            $data['tipo_incidente'] ?? 'Desconocido',
            $data['descripcion'] ?? null,
            $data['gravedad'] ?? 'Media',
            $data['estado'] ?? 'Abierto',
            $id
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new \Exception('Error al actualizar incidente: ' . print_r(sqlsrv_errors(), true));
        }
        return true;
    }

    /* ============================================================
       🗑️ Eliminar incidente
    ============================================================ */
    public static function eliminar($conn, int $id): bool
    {
        $stmt = sqlsrv_query($conn, "DELETE FROM Incidente WHERE id_incidente = ?", [$id]);
        if (!$stmt) {
            throw new \Exception('Error al eliminar incidente: ' . print_r(sqlsrv_errors(), true));
        }
        return true;
    }
}}
