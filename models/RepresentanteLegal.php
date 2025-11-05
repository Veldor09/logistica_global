<?php
class RepresentanteLegal {

    public static function crear($conn, $data) {
        $query = "
            INSERT INTO Representante_Legal (nombre, ape1, ape2, telefono, correo, cedula)
            OUTPUT INSERTED.id_representante
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $params = [
            $data['rep_nombre'],
            $data['rep_ape1'],
            $data['rep_ape2'],
            $data['rep_telefono'],
            $data['rep_correo'],
            $data['rep_cedula']
        ];

        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        if (!sqlsrv_fetch($stmt)) {
            throw new Exception("No se pudo obtener el ID del representante insertado.");
        }

        return sqlsrv_get_field($stmt, 0);
    }
}
?>