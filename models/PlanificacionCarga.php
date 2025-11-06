<?php
// models/PlanificacionCarga.php

if (!class_exists('PlanificacionCarga')) {
class PlanificacionCarga
{
    public static function obtenerPorCarga($conn, $idCarga)
    {
        $sql = "
            SELECT 
                p.id_planificacion,
                p.id_carga,
                p.id_vehiculo,
                p.distribucion_porcentaje,
                p.balance_eje,
                v.placa
            FROM Planificacion_Carga p
            INNER JOIN Vehiculo v ON p.id_vehiculo = v.id_vehiculo
            WHERE p.id_carga = ?
        ";
        $stmt = sqlsrv_query($conn, $sql, [$idCarga]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        $rows = [];
        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $rows[] = $r;
        return $rows;
    }

    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Planificacion_Carga (id_carga, id_vehiculo, distribucion_porcentaje, balance_eje)
            VALUES (?, ?, ?, ?)
        ";
        $params = [
            $data['id_carga'],
            $data['id_vehiculo'],
            $data['distribucion_porcentaje'],
            $data['balance_eje']
        ];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    public static function eliminar($conn, $id)
    {
        $sql = "DELETE FROM Planificacion_Carga WHERE id_planificacion = ?";
        $stmt = sqlsrv_query($conn, $sql, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
}
?>
