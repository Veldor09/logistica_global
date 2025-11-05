<?php
class PlanMantenimientoSistema {

    /* Obtener todos los planes de mantenimiento */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                id_plan,
                descripcion,
                fecha_programada,
                responsable,
                estado,
                observaciones
            FROM Plan_Mantenimiento_Sistema
            ORDER BY fecha_programada DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $planes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $planes[] = $row;
        }
        return $planes;
    }

    /* Obtener un plan específico */
    public static function obtenerPorId($conn, $idPlan) {
        $query = "SELECT * FROM Plan_Mantenimiento_Sistema WHERE id_plan = ?";
        $stmt = sqlsrv_query($conn, $query, [$idPlan]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear un nuevo plan */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Plan_Mantenimiento_Sistema 
                (descripcion, fecha_programada, responsable, estado, observaciones)
            OUTPUT INSERTED.id_plan
            VALUES (?, ?, ?, ?, ?)
        ";

        $params = [
            $data['descripcion'],
            $data['fecha_programada'],
            $data['responsable'],
            $data['estado'] ?? 'Pendiente',
            $data['observaciones'] ?? null
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar un plan */
    public static function actualizar($conn, $idPlan, $data) {
        $query = "
            UPDATE Plan_Mantenimiento_Sistema
            SET descripcion = ?, fecha_programada = ?, responsable = ?, 
                estado = ?, observaciones = ?
            WHERE id_plan = ?
        ";

        $params = [
            $data['descripcion'],
            $data['fecha_programada'],
            $data['responsable'],
            $data['estado'],
            $data['observaciones'],
            $idPlan
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Cambiar estado del plan (por ejemplo: Pendiente → Completado) */
    public static function cambiarEstado($conn, $idPlan, $nuevoEstado) {
        $query = "UPDATE Plan_Mantenimiento_Sistema SET estado = ? WHERE id_plan = ?";
        $stmt = sqlsrv_query($conn, $query, [$nuevoEstado, $idPlan]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar plan */
    public static function eliminar($conn, $idPlan) {
        $query = "DELETE FROM Plan_Mantenimiento_Sistema WHERE id_plan = ?";
        $stmt = sqlsrv_query($conn, $query, [$idPlan]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
