<?php
class Mercancia {
    public static function obtenerTipos($conn) {
        $query = "SELECT * FROM Tipo_Mercancia ORDER BY nombre ASC";
        $stmt = sqlsrv_query($conn, $query);
        $data = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public static function crearTipo($conn, $nombre, $descripcion, $costo_unitario, $restricciones) {
        $query = "INSERT INTO Tipo_Mercancia (nombre, descripcion, costo_unitario, restricciones)
                  VALUES (?, ?, ?, ?)";
        $params = [$nombre, $descripcion, $costo_unitario, $restricciones];
        return sqlsrv_query($conn, $query, $params);
    }
}
?>
