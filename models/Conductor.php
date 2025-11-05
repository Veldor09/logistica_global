<?php
class Conductor {

    /* Obtener todos los conductores */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT *
            FROM Conductor
            ORDER BY id_conductor DESC
        ";
        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $conductores = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $conductores[] = $row;
        }
        return $conductores;
    }

    /* Obtener un conductor por ID */
    public static function obtenerPorId($conn, $id) {
        $query = "SELECT * FROM Conductor WHERE id_conductor = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* Crear nuevo conductor */
    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Conductor (
                nombre, apellido1, apellido2, cedula, telefono, correo, direccion, fecha_ingreso, estado
            )
            OUTPUT INSERTED.id_conductor
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $params = [
            $data['nombre'],
            $data['apellido1'],
            $data['apellido2'] ?? null,
            $data['cedula'],
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['direccion'] ?? null,
            $data['fecha_ingreso'] ?? null,
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* Actualizar conductor */
    public static function actualizar($conn, $id, $data) {
        $query = "
            UPDATE Conductor
            SET nombre = ?, apellido1 = ?, apellido2 = ?, cedula = ?, 
                telefono = ?, correo = ?, direccion = ?, fecha_ingreso = ?, estado = ?
            WHERE id_conductor = ?
        ";
        $params = [
            $data['nombre'],
            $data['apellido1'],
            $data['apellido2'],
            $data['cedula'],
            $data['telefono'],
            $data['correo'],
            $data['direccion'],
            $data['fecha_ingreso'],
            $data['estado'],
            $id
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }

    /* Eliminar conductor */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Conductor WHERE id_conductor = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>
