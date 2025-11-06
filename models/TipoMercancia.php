<?php
class TipoMercancia {

    /* ============================================================
       📋 Obtener todos los tipos de mercancía
    ============================================================ */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_tipo_mercancia,
                nombre,
                descripcion,
                costo_unitario,
                restricciones,
                peso_unitario_kg,
                volumen_unitario_m3,
                estado
            FROM Tipo_Mercancia
            ORDER BY nombre ASC
        ";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $items = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $items[] = $row;
        }
        return $items;
    }

    /* ============================================================
       🔍 Obtener tipo de mercancía por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Mercancia WHERE id_tipo_mercancia = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       ➕ Crear nuevo tipo de mercancía
    ============================================================ */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Mercancia 
            (nombre, descripcion, costo_unitario, restricciones, peso_unitario_kg, volumen_unitario_m3, estado)
            OUTPUT INSERTED.id_tipo_mercancia
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['costo_unitario'] ?? null,
            $data['restricciones'] ?? null,
            $data['peso_unitario_kg'] ?? null,
            $data['volumen_unitario_m3'] ?? null,
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ Actualizar tipo de mercancía
    ============================================================ */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Mercancia
            SET nombre = ?, descripcion = ?, costo_unitario = ?, 
                restricciones = ?, peso_unitario_kg = ?, volumen_unitario_m3 = ?, estado = ?
            WHERE id_tipo_mercancia = ?
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'],
            $data['costo_unitario'],
            $data['restricciones'],
            $data['peso_unitario_kg'],
            $data['volumen_unitario_m3'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* ============================================================
       🗑️ Eliminar tipo de mercancía
    ============================================================ */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Mercancia WHERE id_tipo_mercancia = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
