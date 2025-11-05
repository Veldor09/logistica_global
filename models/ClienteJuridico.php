<?php
require_once __DIR__ . '/RepresentanteLegal.php';

class ClienteJuridico {

    public static function crear($conn, $data) {
        // 1️⃣ Crear representante legal
        $idRep = RepresentanteLegal::crear($conn, $data);

        // 2️⃣ Crear cliente base
        $queryCliente = "
            INSERT INTO Cliente (tipo_identificacion, correo, telefono, direccion, provincia, canton, distrito, estado, fecha_registro)
            OUTPUT INSERTED.id_cliente
            VALUES ('JURIDICO', ?, ?, ?, ?, ?, ?, 'Activo', SYSDATETIME())
        ";
        $params = [
            $data['correo'],
            $data['telefono'],
            $data['direccion'],
            $data['provincia'],
            $data['canton'],
            $data['distrito']
        ];

        $stmt = sqlsrv_query($conn, $queryCliente, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        if (!sqlsrv_fetch($stmt)) {
            throw new Exception("No se pudo obtener el ID del cliente jurídico insertado.");
        }
        $idCliente = sqlsrv_get_field($stmt, 0);

        // 3️⃣ Crear registro en Cliente_Juridico
        $queryJuridico = "
            INSERT INTO Cliente_Juridico (id_cliente, id_representante, nombre_empresa, cedula_juridica)
            VALUES (?, ?, ?, ?)
        ";
        $params2 = [
            $idCliente,
            $idRep,
            $data['nombre_empresa'],
            $data['cedula_juridica']
        ];
        $stmt2 = sqlsrv_query($conn, $queryJuridico, $params2);
        if (!$stmt2) throw new Exception(print_r(sqlsrv_errors(), true));

        return $idCliente;
    }
}
?>