<?php
// models/Cliente.php
class Cliente {
  public static function obtenerTodos($conn, $q = '', $estado = 'todos') {
    $where = "1=1";
    $params = [];
    if ($q !== '') {
      $where .= " AND (
        c.correo LIKE ? OR c.telefono LIKE ? OR
        cf.cedula LIKE ? OR cj.cedula_juridica LIKE ? OR
        cf.nombre LIKE ? OR cf.primer_apellido LIKE ? OR cf.segundo_apellido LIKE ? OR
        cj.nombre_empresa LIKE ?
      )";
      $like = "%{$q}%";
      $params = array_merge($params, [$like,$like,$like,$like,$like,$like,$like,$like]);
    }
    if ($estado !== 'todos') {
      $where .= " AND c.estado = ?";
      $params[] = $estado;
    }

    $sql = "
      SELECT
        c.id_cliente, c.tipo_identificacion, c.correo, c.telefono, c.estado,
        cf.nombre AS fisico_nombre, cf.primer_apellido AS fisico_ape1, cf.segundo_apellido AS fisico_ape2, cf.cedula AS fisico_cedula,
        cj.nombre_empresa AS jur_nombre_empresa, cj.cedula_juridica AS jur_cedula_juridica,
        CASE WHEN c.tipo_identificacion='Fisica'
             THEN CONCAT(cf.nombre, ' ', cf.primer_apellido, COALESCE(' ' + cf.segundo_apellido, ''))
             ELSE cj.nombre_empresa END AS display_nombre,
        CASE WHEN c.tipo_identificacion='Fisica'
             THEN cf.cedula ELSE cj.cedula_juridica END AS display_cedula
      FROM Cliente c
      LEFT JOIN Cliente_Fisico cf ON cf.id_cliente = c.id_cliente
      LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
      WHERE {$where}
      ORDER BY c.id_cliente DESC
    ";
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    $out = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $out[] = $row;
    }
    return $out;
  }

  public static function crear($conn, $data) {
    $sql = "INSERT INTO Cliente (tipo_identificacion, correo, telefono, direccion, provincia, canton, distrito)
            OUTPUT INSERTED.id_cliente
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params = [
      $data['tipo_identificacion'], $data['correo'], $data['telefono'],
      $data['direccion'], $data['provincia'], $data['canton'], $data['distrito']
    ];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return (int)$row['id_cliente'];
  }

  public static function actualizar($conn, $id, $data) {
    $sql = "UPDATE Cliente SET
              tipo_identificacion = ?, correo = ?, telefono = ?, direccion = ?,
              provincia = ?, canton = ?, distrito = ?, estado = ?
            WHERE id_cliente = ?";
    $params = [
      $data['tipo_identificacion'], $data['correo'], $data['telefono'], $data['direccion'],
      $data['provincia'], $data['canton'], $data['distrito'], $data['estado'], $id
    ];
    $ok = sqlsrv_query($conn, $sql, $params);
    if (!$ok) throw new Exception(print_r(sqlsrv_errors(), true));
    return true;
  }

  public static function obtenerPorId($conn, $id) {
    $sql = "
      SELECT
        c.*, 
        cf.nombre AS fisico_nombre, cf.primer_apellido AS fisico_ape1, cf.segundo_apellido AS fisico_ape2, cf.cedula AS fisico_cedula,
        cj.nombre_empresa AS jur_nombre_empresa, cj.cedula_juridica AS jur_cedula_juridica,
        rl.nombre AS rep_nombre, rl.ape1 AS rep_ape1, rl.ape2 AS rep_ape2, rl.telefono AS rep_telefono, rl.correo AS rep_correo, rl.cedula AS rep_cedula
      FROM Cliente c
      LEFT JOIN Cliente_Fisico cf ON cf.id_cliente = c.id_cliente
      LEFT JOIN Cliente_Juridico cj ON cj.id_cliente = c.id_cliente
      LEFT JOIN Representante_Legal rl ON cj.id_representante = rl.id_representante
      WHERE c.id_cliente = ?
    ";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null;
  }

  public static function inactivar($conn, $id) {
    $sql = "UPDATE Cliente SET estado = 'Inactivo' WHERE id_cliente = ?";
    $ok = sqlsrv_query($conn, $sql, [$id]);
    if (!$ok) throw new Exception(print_r(sqlsrv_errors(), true));
    return true;
  }

  public static function tieneSolicitudesActivas($conn, $id) {
    $sql = "SELECT TOP 1 1 FROM Solicitud WHERE id_cliente = ? AND estado IN ('Pendiente','En_Proceso')";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return (bool)$row;
  }
}
