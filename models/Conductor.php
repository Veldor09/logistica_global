<?php
// ============================================================
// 🧑‍✈️ MODELO: Conductor.php
// Gestiona la información de los conductores de la flota
// ============================================================

class Conductor
{
    /* ============================================================
       📋 OBTENER TODOS LOS CONDUCTORES
    ============================================================ */
    public static function obtenerTodos($conn)
    {
        $sql = "SELECT * FROM Conductor ORDER BY id_conductor DESC";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (!empty($r['fecha_ingreso']) && $r['fecha_ingreso'] instanceof DateTime) {
                $r['fecha_ingreso'] = $r['fecha_ingreso']->format('Y-m-d');
            }
            $rows[] = $r;
        }
        return $rows;
    }

    /* ============================================================
       🔎 OBTENER CONDUCTOR POR ID
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "SELECT * FROM Conductor WHERE id_conductor = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       ➕ CREAR CONDUCTOR
    ============================================================ */
    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Conductor (
                nombre, apellido1, apellido2, cedula, telefono, correo, direccion, fecha_ingreso, estado
            )
            OUTPUT INSERTED.id_conductor
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $params = [
            trim($data['nombre']),
            trim($data['apellido1']),
            $data['apellido2'] ?? null,
            trim($data['cedula']),
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['direccion'] ?? null,
            $data['fecha_ingreso'] ?? null,
            $data['estado'] ?? 'Activo'
        ];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ ACTUALIZAR CONDUCTOR
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $sql = "
            UPDATE Conductor
            SET nombre = ?, apellido1 = ?, apellido2 = ?, cedula = ?, 
                telefono = ?, correo = ?, direccion = ?, fecha_ingreso = ?, estado = ?
            WHERE id_conductor = ?
        ";
        $params = [
            trim($data['nombre']),
            trim($data['apellido1']),
            $data['apellido2'] ?? null,
            trim($data['cedula']),
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['direccion'] ?? null,
            $data['fecha_ingreso'] ?? null,
            $data['estado'] ?? 'Activo',
            $id
        ];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ============================================================
       🗑️ ELIMINAR CONDUCTOR (validando viajes asignados)
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $check = sqlsrv_query($conn, "SELECT TOP 1 1 FROM Viaje WHERE id_conductor = ?", [$id]);
        if ($check && sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
            throw new Exception("No se puede eliminar: el conductor tiene viajes asignados.");
        }

        $sql = "DELETE FROM Conductor WHERE id_conductor = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ============================================================
       🟢 OBTENER CONDUCTORES ACTIVOS
    ============================================================ */
    public static function obtenerActivos($conn): array
    {
        $sql = "
            SELECT id_conductor, nombre, apellido1, apellido2
            FROM Conductor
            WHERE estado = 'Activo'
            ORDER BY nombre
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $rows[] = $r;
        return $rows;
    }

    /* ============================================================
       🟡 CONDUCTORES DISPONIBLES (sin viaje activo)
    ============================================================ */
    public static function obtenerDisponibles($conn): array
    {
        $sql = "
            SELECT c.id_conductor, c.nombre, c.apellido1, c.apellido2
            FROM Conductor c
            WHERE c.estado = 'Activo'
              AND c.id_conductor NOT IN (
                SELECT id_conductor FROM Viaje WHERE estado IN ('Pendiente','En_Ruta')
              )
            ORDER BY c.nombre
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $rows[] = $r;
        return $rows;
    }
}
?>
