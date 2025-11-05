<?php
class TipoMercancia {

    /* Obtener todos los tipos de mercancía */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_tipo_mercancia,
                nombre,
                descripcion,
                costo_unitario,
                restricciones,
                estado
            FROM Tipo_Mercancia
            ORDER BY id_tipo_mercancia DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $mercancias = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $mercancias[] = $row;
        }
        return $mercancias;
    }

    /* Obtener tipo de mercancía por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Tipo_Mercancia WHERE id_tipo_mercancia = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo tipo de mercancía */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Tipo_Mercancia (nombre, descripcion, costo_unitario, restricciones, estado)
            OUTPUT INSERTED.id_tipo_mercancia
            VALUES (?, ?, ?, ?, ?)
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['costo_unitario'] ?? null,
            $data['restricciones'] ?? null,
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar tipo de mercancía */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Tipo_Mercancia
            SET nombre = ?, descripcion = ?, costo_unitario = ?, 
                restricciones = ?, estado = ?
            WHERE id_tipo_mercancia = ?
        ";
        $params = [
            $data['nombre'],
            $data['descripcion'],
            $data['costo_unitario'],
            $data['restricciones'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar tipo de mercancía */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Tipo_Mercancia WHERE id_tipo_mercancia = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
