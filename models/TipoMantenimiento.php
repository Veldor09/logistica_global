<?php
class TipoMantenimiento {

    public static function obtenerTodos($conn) {
        // ✅ Insertar tipos por defecto si la tabla está vacía
        $check = sqlsrv_query($conn, "SELECT COUNT(*) AS total FROM Tipo_Mantenimiento");
        $row = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
        if ($row['total'] == 0) {
            $defaults = [
                ['Cambio de aceite', 'Reemplazo de aceite de motor', 90],
                ['Cambio de frenos', 'Sustitución de pastillas y revisión del sistema de frenos', 180],
                ['Revisión general', 'Inspección preventiva completa del vehículo', 365],
                ['Cambio de filtro', 'Reemplazo de filtros de aire y combustible', 120],
                ['Alineamiento y balanceo', 'Ajuste de llantas y ejes', 180]
            ];
            foreach ($defaults as $t) {
                sqlsrv_query($conn, "INSERT INTO Tipo_Mantenimiento (nombre, descripcion, frecuencia_dias) VALUES (?, ?, ?)", $t);
            }
        }

        // ✅ Devolver todos los registros
        $sql = "SELECT id_tipo_mantenimiento, nombre, descripcion FROM Tipo_Mantenimiento ORDER BY id_tipo_mantenimiento ASC";
        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }
}
?>
