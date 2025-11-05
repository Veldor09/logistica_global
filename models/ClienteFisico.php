<?php
class ClienteFisico {

    public static function crear($conn, $data) {
        // 1️⃣ Crear registro base en Cliente
        $queryCliente = "
            INSERT INTO Cliente (tipo_identificacion, correo, telefono, direccion, provincia, canton, distrito, estado, fecha_registro)
            OUTPUT INSERTED.id_cliente
            VALUES ('FISICO', ?, ?, ?, ?, ?, ?, 'Activo', SYSDATETIME())
        ";
        $params = [
            $data['correo'], $data['telefono'], $data['direccion'],
            $data['provincia'], $data['canton'], $data['distrito']
        ];
        $stmt = sqlsrv_query($conn, $queryCliente, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        if (!sqlsrv_fetch($stmt)) {
            throw new Exception("No se pudo obtener el ID del cliente físico insertado.");
        }
        $idCliente = sqlsrv_get_field($stmt, 0);

        // 2️⃣ Crear registro en Cliente_Fisico
        $queryFisico = "
            INSERT INTO Cliente_Fisico (id_cliente, nombre, primer_apellido, segundo_apellido, cedula)
            VALUES (?, ?, ?, ?, ?)
        ";
        $params2 = [
            $idCliente,
            $data['nombre'],
            $data['primer_apellido'],
            $data['segundo_apellido'],
            $data['cedula_fisica']
        ];
        $stmt2 = sqlsrv_query($conn, $queryFisico, $params2);
        if (!$stmt2) throw new Exception(print_r(sqlsrv_errors(), true));

        return $idCliente;
    }
}
?>