<?php
// ============================================================
// 📦 MODELO: Orden.php
// Gestión de Órdenes de Transporte (vinculadas a Solicitudes)
// ============================================================

if (!class_exists('Orden')) {
class Orden
{
    /* ============================================================
       🔧 Utilidades de normalización (decimales y fechas)
    ============================================================ */
    private static function normDecimal($v)
    {
        if ($v === '' || $v === null) return null;
        $v = str_replace(',', '.', (string)$v);
        return is_numeric($v) ? (float)$v : null;
    }

    private static function normDate($v)
    {
        $v = trim((string)($v ?? ''));
        if ($v === '') return null;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) return $v;
        return null;
    }

    /* ============================================================
       🟢 CREAR ORDEN (versión estable con SCOPE_IDENTITY)
    ============================================================ */
    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Orden (
                id_solicitud,
                direccion_origen,
                direccion_destino,
                peso_estimado_kg,
                fecha_carga,
                fecha_entrega_estimada,
                estado,
                observaciones
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?);
            SELECT SCOPE_IDENTITY() AS id_orden;
        ";

        $params = [
            (int)$data['id_solicitud'],
            ($data['direccion_origen']  ?? null) === '' ? null : $data['direccion_origen'],
            ($data['direccion_destino'] ?? null) === '' ? null : $data['direccion_destino'],
            self::normDecimal($data['peso_estimado_kg'] ?? null),
            self::normDate($data['fecha_carga'] ?? null),
            self::normDate($data['fecha_entrega_estimada'] ?? null),
            ($data['estado'] ?? 'Programada'),
            ($data['observaciones'] ?? null) === '' ? null : $data['observaciones'],
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception("Error al insertar la orden: " . print_r(sqlsrv_errors(), true));

        sqlsrv_next_result($stmt);
        sqlsrv_fetch($stmt);
        $idOrden = sqlsrv_get_field($stmt, 0);

        if (!$idOrden) throw new Exception('No se pudo obtener el ID de la orden creada.');
        return $idOrden;
    }

    /* ============================================================
       ✏️ ACTUALIZAR ORDEN
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $sql = "
            UPDATE Orden
            SET 
                direccion_origen = ?,
                direccion_destino = ?,
                peso_estimado_kg = ?,
                fecha_carga = ?,
                fecha_entrega_estimada = ?,
                estado = ?,
                observaciones = ?
            WHERE id_orden = ?
        ";

        $params = [
            ($data['direccion_origen']  ?? null) === '' ? null : $data['direccion_origen'],
            ($data['direccion_destino'] ?? null) === '' ? null : $data['direccion_destino'],
            self::normDecimal($data['peso_estimado_kg'] ?? null),
            self::normDate($data['fecha_carga'] ?? null),
            self::normDate($data['fecha_entrega_estimada'] ?? null),
            ($data['estado'] ?? 'Programada'),
            ($data['observaciones'] ?? null) === '' ? null : $data['observaciones'],
            (int)$id
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception("Error al actualizar la orden: " . print_r(sqlsrv_errors(), true));
    }

    /* ============================================================
       🔍 OBTENER TODAS LAS ÓRDENES
    ============================================================ */
    public static function obtenerTodos($conn)
    {
        $sql = "
            SELECT 
                o.id_orden,
                o.id_solicitud,
                s.origen          AS direccion_origen,
                s.destino_general AS direccion_destino,
                o.peso_estimado_kg,
                o.estado,
                COALESCE(MAX(tm.nombre), 'No asignado') AS tipo_mercancia,
                COALESCE(SUM(tmo.volumen_total_m3), 0) AS volumen_total_m3
            FROM Orden o
            INNER JOIN Solicitud s ON s.id_solicitud = o.id_solicitud
            LEFT  JOIN Tipo_Mercancia_Orden tmo ON tmo.id_orden = o.id_orden
            LEFT  JOIN Tipo_Mercancia tm ON tm.id_tipo_mercancia = tmo.id_tipo_mercancia
            GROUP BY 
                o.id_orden, o.id_solicitud, s.origen, s.destino_general, 
                o.peso_estimado_kg, o.estado
            ORDER BY o.id_orden DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception('Error al obtener las órdenes: ' . print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $r['peso_estimado_kg'] = number_format((float)$r['peso_estimado_kg'], 2);
            $r['volumen_total_m3'] = number_format((float)$r['volumen_total_m3'], 2);
            $rows[] = $r;
        }
        return $rows;
    }

    /* ============================================================
       🔍 OBTENER POR ID
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "SELECT * FROM Orden WHERE id_orden = ?";
        $stmt = sqlsrv_query($conn, $sql, [(int)$id]);
        if (!$stmt) throw new Exception("Error al obtener orden por ID: " . print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       🔍 OBTENER POR ORIGEN / DESTINO / ESTADO (para crear viaje)
    ============================================================ */
    public static function obtenerPorOrigenDestinoYEstado($conn, string $origen, string $destino, string $estado = 'Programada'): array
    {
        $sql = "
            SELECT 
                o.id_orden,
                o.direccion_origen,
                o.direccion_destino,
                o.peso_estimado_kg,
                o.estado,
                o.fecha_carga,
                o.fecha_entrega_estimada,
                s.id_solicitud
            FROM Orden o
            INNER JOIN Solicitud s ON s.id_solicitud = o.id_solicitud
            WHERE o.estado = ?
              AND o.direccion_origen = ?
              AND o.direccion_destino = ?
            ORDER BY o.id_orden DESC
        ";

        $stmt = sqlsrv_query($conn, $sql, [$estado, $origen, $destino]);
        if (!$stmt) throw new Exception("Error al filtrar órdenes por ruta: " . print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $r;
        }
        return $rows;
    }

    /* ============================================================
       📋 ÓRDENES PARA EDICIÓN DE UN VIAJE
    ============================================================ */
    public static function obtenerParaEdicionDeViaje($conn, string $origen, string $destino, int $id_viaje): array
    {
        $asignados = [];
        $stmtA = @sqlsrv_query($conn, "SELECT id_orden FROM Orden_Viaje WHERE id_viaje = ?", [$id_viaje]);
        if ($stmtA) {
            while ($row = sqlsrv_fetch_array($stmtA, SQLSRV_FETCH_ASSOC)) {
                $asignados[(int)$row['id_orden']] = true;
            }
        }

        $params = ['Programada', $origen, $destino];
        $inClause = '';
        if (!empty($asignados)) {
            $marks = implode(',', array_fill(0, count($asignados), '?'));
            $inClause = " OR o.id_orden IN ($marks)";
            foreach (array_keys($asignados) as $idAsig) {
                $params[] = (int)$idAsig;
            }
        }

        $sql = "
            SELECT
                o.id_orden,
                o.estado,
                o.direccion_origen,
                o.direccion_destino,
                o.peso_estimado_kg,
                o.fecha_carga,
                o.fecha_entrega_estimada,
                s.id_solicitud,
                s.tipo_servicio,
                s.origen          AS solicitud_origen,
                s.destino_general AS solicitud_destino
            FROM Orden o
            LEFT JOIN Solicitud s ON s.id_solicitud = o.id_solicitud
            WHERE
              (
                o.estado = ?
                AND COALESCE(o.direccion_origen,  s.origen)          = ?
                AND COALESCE(o.direccion_destino, s.destino_general) = ?
              )
              $inClause
            ORDER BY o.id_orden DESC
        ";

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new \Exception('SQL Error (Orden::obtenerParaEdicionDeViaje): ' . print_r(sqlsrv_errors(), true));
        }

        $out = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $id = (int)$r['id_orden'];
            $ori  = $r['direccion_origen']  ?: $r['solicitud_origen'];
            $dest = $r['direccion_destino'] ?: $r['solicitud_destino'];
            $tipo = $r['tipo_servicio'] ?? 'Orden';
            $r['asignado'] = isset($asignados[$id]);
            $r['label'] = sprintf('#%d · %s · %s → %s', $id, $tipo, (string)$ori, (string)$dest);
            $out[$id] = $r;
        }
        return array_values($out);
    }

    /* ============================================================
       🗑️ ELIMINAR ORDEN
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $sql = "DELETE FROM Orden WHERE id_orden = ?";
        $stmt = sqlsrv_query($conn, $sql, [(int)$id]);
        if (!$stmt) throw new Exception("Error al eliminar orden: " . print_r(sqlsrv_errors(), true));
    }

    /* ============================================================
       📦 Obtener órdenes que aún no tienen factura
    ============================================================ */
    public static function obtenerNoFacturadas($conn)
    {
        $sql = "
            SELECT 
                o.id_orden,
                o.direccion_origen,
                o.direccion_destino,
                o.peso_estimado_kg,
                o.estado,
                ISNULL(cf.nombre, cj.nombre_empresa) AS nombre_cliente
            FROM Orden o
            INNER JOIN Solicitud s ON s.id_solicitud = o.id_solicitud
            LEFT JOIN Cliente c ON c.id_cliente = s.id_cliente
            LEFT JOIN Cliente_Fisico cf ON cf.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
            LEFT JOIN Factura f ON o.id_orden = f.id_orden
            WHERE f.id_factura IS NULL
            ORDER BY o.id_orden DESC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) {
            throw new Exception('Error al obtener órdenes no facturadas: ' . print_r(sqlsrv_errors(), true));
        }

        $ordenes = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ordenes[] = [
                'id_orden' => $r['id_orden'],
                'cliente' => $r['nombre_cliente'] ?? '-',
                'origen' => $r['direccion_origen'] ?? '-',
                'destino' => $r['direccion_destino'] ?? '-',
                'peso_estimado_kg' => $r['peso_estimado_kg'] ?? 0,
                'estado' => $r['estado'] ?? '-',
            ];
        }

        return $ordenes;
    }

    /* ============================================================
       🧾 Marcar una orden como facturada
    ============================================================ */
    public static function marcarComoFacturada($conn, $id_orden)
    {
        $sql = "UPDATE Orden SET estado = 'Facturada' WHERE id_orden = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id_orden]);
        if (!$stmt) {
            throw new Exception('Error al marcar la orden como facturada: ' . print_r(sqlsrv_errors(), true));
        }
        return true;
    }
}}
?>
