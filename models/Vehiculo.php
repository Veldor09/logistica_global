<?php
// ============================================================
// 🚚 MODELO: Vehiculo.php
// Gestiona los vehículos de la flota y sus relaciones con viajes
// ============================================================

class Vehiculo
{
    /* ============================================================
       📋 OBTENER TODOS LOS VEHÍCULOS (con tipo de camión)
    ============================================================ */
    public static function obtenerTodos($conn)
    {
        $sql = "
            SELECT 
                v.id_vehiculo,
                v.placa,
                v.capacidad_kg,
                v.marca,
                v.modelo,
                v.anio,
                v.estado,
                v.fecha_adquisicion,
                v.id_tipo_camion,
                tc.nombre_tipo AS tipo_camion
            FROM Vehiculo v
            LEFT JOIN Tipo_Camion tc ON v.id_tipo_camion = tc.id_tipo_camion
            ORDER BY v.id_vehiculo DESC
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $vehiculos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (!empty($row['fecha_adquisicion']) && $row['fecha_adquisicion'] instanceof DateTime) {
                $row['fecha_adquisicion'] = $row['fecha_adquisicion']->format('Y-m-d');
            }
            $vehiculos[] = $row;
        }
        return $vehiculos;
    }

    /* ============================================================
       🔎 OBTENER POR ID
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "
            SELECT v.*, tc.nombre_tipo AS tipo_camion
            FROM Vehiculo v
            LEFT JOIN Tipo_Camion tc ON v.id_tipo_camion = tc.id_tipo_camion
            WHERE v.id_vehiculo = ?
        ";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!empty($row['fecha_adquisicion']) && $row['fecha_adquisicion'] instanceof DateTime) {
            $row['fecha_adquisicion'] = $row['fecha_adquisicion']->format('Y-m-d');
        }
        return $row;
    }

    /* ============================================================
       ➕ CREAR VEHÍCULO
    ============================================================ */
    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Vehiculo (
                id_tipo_camion, placa, capacidad_kg, marca, modelo, anio, estado, fecha_adquisicion
            )
            OUTPUT INSERTED.id_vehiculo
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $params = [
            !empty($data['id_tipo_camion']) ? (int)$data['id_tipo_camion'] : null,
            trim($data['placa']),
            isset($data['capacidad_kg']) ? (float)$data['capacidad_kg'] : null,
            $data['marca'] ?? null,
            $data['modelo'] ?? null,
            !empty($data['anio']) ? (int)$data['anio'] : null,
            $data['estado'] ?? 'Activo',
            !empty($data['fecha_adquisicion']) ? $data['fecha_adquisicion'] : null
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ ACTUALIZAR VEHÍCULO
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $sql = "
            UPDATE Vehiculo
            SET id_tipo_camion = ?, placa = ?, capacidad_kg = ?, marca = ?, modelo = ?, 
                anio = ?, estado = ?, fecha_adquisicion = ?
            WHERE id_vehiculo = ?
        ";
        $params = [
            !empty($data['id_tipo_camion']) ? (int)$data['id_tipo_camion'] : null,
            trim($data['placa']),
            isset($data['capacidad_kg']) ? (float)$data['capacidad_kg'] : null,
            $data['marca'] ?? null,
            $data['modelo'] ?? null,
            !empty($data['anio']) ? (int)$data['anio'] : null,
            $data['estado'] ?? 'Activo',
            !empty($data['fecha_adquisicion']) ? $data['fecha_adquisicion'] : null,
            $id
        ];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ============================================================
       🗑️ ELIMINAR VEHÍCULO (validando viajes existentes)
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $check = sqlsrv_query($conn, "SELECT TOP 1 1 FROM Viaje WHERE id_vehiculo = ?", [$id]);
        if ($check && sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
            throw new Exception("No se puede eliminar: el vehículo tiene viajes registrados.");
        }

        $sql = "DELETE FROM Vehiculo WHERE id_vehiculo = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ============================================================
       🟢 OBTENER VEHÍCULOS ACTIVOS (para selects)
    ============================================================ */
    public static function obtenerActivos($conn): array
    {
        $sql = "
            SELECT id_vehiculo, placa, marca, modelo, capacidad_kg
            FROM Vehiculo
            WHERE estado IN ('Activo', 'Mantenimiento')
            ORDER BY placa
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $rows[] = $r;
        return $rows;
    }

    /* ============================================================
       🟡 VEHÍCULOS DISPONIBLES (sin viaje activo)
    ============================================================ */
    public static function obtenerDisponibles($conn): array
    {
        $sql = "
            SELECT v.id_vehiculo, v.placa, v.marca, v.modelo, v.capacidad_kg
            FROM Vehiculo v
            WHERE v.estado = 'Activo'
              AND v.id_vehiculo NOT IN (
                SELECT id_vehiculo FROM Viaje WHERE estado IN ('Pendiente','En_Ruta')
              )
            ORDER BY v.placa
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $rows[] = $r;
        return $rows;
    }
}
?>
