<?php
class TipoCamion {

    /* ===========================================================
       📋 Obtener todos los tipos de camión (autoinserta por defecto)
    ============================================================ */
    public static function obtenerTodos($conn) {
        // Verificar si existen registros
        $check = sqlsrv_query($conn, "SELECT COUNT(*) AS total FROM Tipo_Camion");
        if (!$check) throw new Exception(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);

        // Si está vacía, insertar tipos predefinidos con medidas por defecto
        if ((int)$row['total'] === 0) {
            $default = [
                ['Camión Liviano',     'Capacidad menor a 3.5 toneladas', 15.0, 4.5, 2.0, 2.0],
                ['Camión Mediano',     'Capacidad entre 3.5 y 7 toneladas', 25.0, 6.0, 2.5, 2.5],
                ['Camión Pesado',      'Capacidad mayor a 7 toneladas', 40.0, 8.0, 2.6, 3.0],
                ['Camión Plataforma',  'Ideal para carga voluminosa o maquinaria', 50.0, 10.0, 3.0, 2.5],
                ['Camión Cisterna',    'Transporte de líquidos o combustibles', 35.0, 8.0, 2.5, 2.5],
                ['Camión Refrigerado', 'Con sistema de refrigeración para alimentos', 30.0, 7.5, 2.5, 2.8],
            ];

            foreach ($default as $t) {
                $insert = "
                    INSERT INTO Tipo_Camion (
                        nombre_tipo, descripcion,
                        capacidad_volumen_m3, largo_m, ancho_m, alto_m
                    ) VALUES (?, ?, ?, ?, ?, ?)";
                $ok = sqlsrv_query($conn, $insert, $t);
                if (!$ok) throw new Exception(print_r(sqlsrv_errors(), true));
            }
        }

        // Consultar todos los tipos actualizados
        $sql = "
            SELECT id_tipo_camion, nombre_tipo, descripcion,
                   capacidad_volumen_m3, largo_m, ancho_m, alto_m
            FROM Tipo_Camion
            ORDER BY id_tipo_camion ASC
        ";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* ===========================================================
       🔎 Obtener un tipo de camión por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $sql = "
            SELECT id_tipo_camion, nombre_tipo, descripcion,
                   capacidad_volumen_m3, largo_m, ancho_m, alto_m
            FROM Tipo_Camion
            WHERE id_tipo_camion = ?
        ";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ===========================================================
       ➕ Crear nuevo tipo de camión (si quieres agregar más)
    ============================================================ */
    public static function crear($conn, $data) {
        $sql = "
            INSERT INTO Tipo_Camion (
                nombre_tipo, descripcion,
                capacidad_volumen_m3, largo_m, ancho_m, alto_m
            )
            OUTPUT INSERTED.id_tipo_camion
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $params = [
            trim($data['nombre_tipo'] ?? ''),
            $data['descripcion'] ?? null,
            $data['capacidad_volumen_m3'] ?? null,
            $data['largo_m'] ?? null,
            $data['ancho_m'] ?? null,
            $data['alto_m'] ?? null
        ];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ===========================================================
       ✏️ Actualizar tipo de camión
    ============================================================ */
    public static function actualizar($conn, $id, $data) {
        $sql = "
            UPDATE Tipo_Camion
            SET nombre_tipo = ?, descripcion = ?,
                capacidad_volumen_m3 = ?, largo_m = ?, ancho_m = ?, alto_m = ?
            WHERE id_tipo_camion = ?
        ";
        $params = [
            trim($data['nombre_tipo']),
            $data['descripcion'] ?? null,
            $data['capacidad_volumen_m3'] ?? null,
            $data['largo_m'] ?? null,
            $data['ancho_m'] ?? null,
            $data['alto_m'] ?? null,
            $id
        ];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }

    /* ===========================================================
       🗑️ Eliminar tipo de camión
    ============================================================ */
    public static function eliminar($conn, $id) {
        // Validar si hay vehículos asociados
        $check = sqlsrv_query($conn, "SELECT TOP 1 1 FROM Vehiculo WHERE id_tipo_camion = ?", [$id]);
        if ($check && sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
            throw new Exception("No se puede eliminar: existen vehículos asociados a este tipo.");
        }

        $sql = "DELETE FROM Tipo_Camion WHERE id_tipo_camion = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return true;
    }
}
?>
