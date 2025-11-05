<?php
class RegistroAccion {

    /* Registrar una acción en el sistema */
    public static function registrar($conn, $data) {
        $query = "
            INSERT INTO Registro_Accion (usuario, modulo, accion, descripcion, ip_origen, fecha)
            VALUES (?, ?, ?, ?, ?, SYSDATETIME())
        ";

        $params = [
            $data['usuario'],
            $data['modulo'],
            $data['accion'],
            $data['descripcion'] ?? null,
            $data['ip_origen'] ?? $_SERVER['REMOTE_ADDR']
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Obtener todos los registros */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_registro,
                usuario,
                modulo,
                accion,
                descripcion,
                ip_origen,
                CONVERT(VARCHAR, fecha, 120) AS fecha
            FROM Registro_Accion
            ORDER BY fecha DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $registros = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $registros[] = $row;
        }
        return $registros;
    }

    /* Filtrar por módulo o usuario */
    public static function filtrar($conn, $filtros = []) {
        $query = "SELECT * FROM Registro_Accion WHERE 1=1";
        $params = [];

        if (!empty($filtros['usuario'])) {
            $query .= " AND usuario LIKE ?";
            $params[] = '%' . $filtros['usuario'] . '%';
        }

        if (!empty($filtros['modulo'])) {
            $query .= " AND modulo LIKE ?";
            $params[] = '%' . $filtros['modulo'] . '%';
        }

        if (!empty($filtros['accion'])) {
            $query .= " AND accion = ?";
            $params[] = $filtros['accion'];
        }

        $query .= " ORDER BY fecha DESC";

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $registros = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $registros[] = $row;
        }
        return $registros;
    }

    /* Eliminar registro individual */
    public static function eliminar($conn, $idRegistro) {
        $query = "DELETE FROM Registro_Accion WHERE id_registro = ?";
        $stmt = sqlsrv_query($conn, $query, [$idRegistro]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Limpiar todos los registros del sistema */
    public static function limpiar($conn) {
        $query = "DELETE FROM Registro_Accion";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
