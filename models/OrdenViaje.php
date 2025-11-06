<?php
// ============================================================
// 📁 models/OrdenViaje.php
// Relación N–N entre Orden y Viaje + utilidades de resumen
// ============================================================

if (!class_exists('OrdenViaje')) {
class OrdenViaje
{
    /* ------------------------------------------------------------
     * Obtener ids de órdenes asociadas a un viaje
     * ------------------------------------------------------------ */
    public static function obtenerIdsPorViaje($conn, int $id_viaje): array {
        $sql = "SELECT id_orden FROM Orden_Viaje WHERE id_viaje = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id_viaje]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        $ids = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ids[] = (int)$r['id_orden'];
        }
        return $ids;
    }

    /* ------------------------------------------------------------
     * Resumen (peso/volumen) por viaje
     * ------------------------------------------------------------ */
    public static function resumenPorViaje($conn, int $id_viaje): array {
        $sql = "
            SELECT 
                SUM(COALESCE(peso_asignado_kg, 0))     AS peso_total_kg,
                SUM(COALESCE(volumen_asignado_m3, 0))  AS volumen_total_m3,
                COUNT(*) AS total_ordenes
            FROM Orden_Viaje
            WHERE id_viaje = ?
        ";
        $stmt = sqlsrv_query($conn, $sql, [$id_viaje]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: [
            'peso_total_kg' => 0, 'volumen_total_m3' => 0, 'total_ordenes' => 0
        ];
        $row['peso_total_kg'] = (float)($row['peso_total_kg'] ?? 0);
        $row['volumen_total_m3'] = (float)($row['volumen_total_m3'] ?? 0);
        $row['total_ordenes'] = (int)($row['total_ordenes'] ?? 0);
        return $row;
    }

    /* ------------------------------------------------------------
     * Insertar vínculos masivos (id_viaje + [ids de orden])
     * Asigna peso_asignado_kg = Orden.peso_estimado_kg por defecto
     * ------------------------------------------------------------ */
    public static function vincularOrdenes($conn, int $id_viaje, array $ids_orden): void {
        if (empty($ids_orden)) return;

        $placeholders = implode(',', array_fill(0, count($ids_orden), '?'));
        $params = $ids_orden;

        $sql = "
            INSERT INTO Orden_Viaje (id_orden, id_viaje, peso_asignado_kg, volumen_asignado_m3)
            SELECT o.id_orden, ?, o.peso_estimado_kg, NULL
            FROM Orden o
            WHERE o.id_orden IN ($placeholders)
              AND NOT EXISTS (
                SELECT 1 FROM Orden_Viaje ov
                WHERE ov.id_orden = o.id_orden AND ov.id_viaje = ?
              )
        ";

        array_unshift($params, $id_viaje);
        $params[] = $id_viaje;

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* ------------------------------------------------------------
     * Eliminar vínculos de un viaje (por si no usas ON DELETE CASCADE)
     * ------------------------------------------------------------ */
    public static function eliminarPorViaje($conn, int $id_viaje): void {
        $stmt = sqlsrv_query($conn, "DELETE FROM Orden_Viaje WHERE id_viaje = ?", [$id_viaje]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* ------------------------------------------------------------
     * 🟡 Actualizar vínculos (para edición de viajes)
     * - Borra las órdenes antiguas y vincula las nuevas
     * ------------------------------------------------------------ */
    public static function actualizarVinculos($conn, int $id_viaje, array $nuevos): void {
        // 1️⃣ Elimina vínculos previos
        self::eliminarPorViaje($conn, $id_viaje);

        // 2️⃣ Inserta los nuevos vínculos
        if (!empty($nuevos)) {
            self::vincularOrdenes($conn, $id_viaje, $nuevos);
        }
    }
}}
?>
