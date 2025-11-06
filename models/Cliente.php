<?php
require_once __DIR__ . '/ClienteFisico.php';
require_once __DIR__ . '/ClienteJuridico.php';

class Cliente {

    /* ============================================================
       📋 Obtener todos los clientes
    ============================================================ */
    public static function obtenerTodos($conn) {
        $query = "
            SELECT 
                c.id_cliente,
                c.tipo_identificacion,
                c.correo,
                c.telefono,
                c.provincia,
                c.canton,
                c.distrito,
                c.estado,
                c.fecha_registro,
                cf.nombre,
                cf.primer_apellido,
                cf.segundo_apellido,
                cf.cedula AS cedula_fisica,
                cj.nombre_empresa,
                cj.cedula_juridica
            FROM Cliente c
            LEFT JOIN Cliente_Fisico cf ON cf.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
            ORDER BY c.id_cliente DESC
        ";

        $stmt = sqlsrv_query($conn, $query);
        if (!$stmt) die(print_r(sqlsrv_errors(), true));

        $clientes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $clientes[] = $row;
        }
        return $clientes;
    }

    /* ============================================================
       🧾 Obtener cliente por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id) {
        $query = "
            SELECT 
                c.id_cliente,
                c.tipo_identificacion,
                c.correo,
                c.telefono,
                c.direccion,
                c.provincia,
                c.canton,
                c.distrito,
                c.estado,
                cf.nombre,
                cf.primer_apellido,
                cf.segundo_apellido,
                cf.cedula AS cedula_fisica,
                cj.nombre_empresa,
                cj.cedula_juridica
            FROM Cliente c
            LEFT JOIN Cliente_Fisico cf ON cf.id_cliente = c.id_cliente
            LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
            WHERE c.id_cliente = ?
        ";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    /* ============================================================
       🟢 Crear cliente físico
    ============================================================ */
    public static function crearFisico($conn, $data) {
        return ClienteFisico::crear($conn, $data);
    }

    /* ============================================================
       🟢 Crear cliente jurídico
    ============================================================ */
    public static function crearJuridico($conn, $data) {
        return ClienteJuridico::crear($conn, $data);
    }

    /* ============================================================
       ✏️ Actualizar cliente (físico o jurídico)
    ============================================================ */
    public static function actualizar($conn, $id, $data) {
        // Actualiza la tabla base
        $query = "
            UPDATE Cliente
            SET correo = ?, telefono = ?, direccion = ?, provincia = ?, canton = ?, distrito = ?, estado = ?
            WHERE id_cliente = ?
        ";
        $params = [
            $data['correo'],
            $data['telefono'],
            $data['direccion'],
            $data['provincia'],
            $data['canton'],
            $data['distrito'],
            $data['estado'] ?? 'Activo',
            $id
        ];
        $stmt = sqlsrv_query($conn, $query, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

        // Dependiendo del tipo, actualiza su subtipo
        if ($data['tipo_identificacion'] === 'FISICO') {
            $queryFisico = "
                UPDATE Cliente_Fisico
                SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, cedula = ?
                WHERE id_cliente = ?
            ";
            $params2 = [
                $data['nombre'],
                $data['primer_apellido'],
                $data['segundo_apellido'],
                $data['cedula_fisica'],
                $id
            ];
            $stmt2 = sqlsrv_query($conn, $queryFisico, $params2);
            if (!$stmt2) throw new Exception(print_r(sqlsrv_errors(), true));
        } elseif ($data['tipo_identificacion'] === 'JURIDICO') {
            $queryJuridico = "
                UPDATE Cliente_Juridico
                SET nombre_empresa = ?, cedula_juridica = ?
                WHERE id_cliente = ?
            ";
            $params3 = [
                $data['nombre_empresa'],
                $data['cedula_juridica'],
                $id
            ];
            $stmt3 = sqlsrv_query($conn, $queryJuridico, $params3);
            if (!$stmt3) throw new Exception(print_r(sqlsrv_errors(), true));
        }

        return true;
    }

    /* ============================================================
       🔴 Eliminar cliente
    ============================================================ */
    public static function eliminar($conn, $id) {
        $query = "DELETE FROM Cliente WHERE id_cliente = ?";
        $stmt = sqlsrv_query($conn, $query, [$id]);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    }
}
?>