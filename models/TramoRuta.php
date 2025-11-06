<?php
class TramoRuta {

  /* ============================================================
     🔹 Obtener todos los tramos (con ruta y tipo de carretera)
  ============================================================ */
  public static function obtenerTodos($conn) {
    $sql = "
      SELECT 
        tr.id_tramo,
        tr.id_ruta,
        r.nombre_ruta,
        tr.id_tipo_carretera,
        tc.nombre AS tipo_carretera,
        tr.orden_tramo,
        tr.punto_inicio,
        tr.punto_fin,
        tr.distancia_km,
        tr.tiempo_estimado_hr,
        tr.observaciones
      FROM Tramo_Ruta tr
      INNER JOIN Ruta r ON tr.id_ruta = r.id_ruta
      LEFT JOIN Tipo_Carretera tc ON tr.id_tipo_carretera = tc.id_tipo_carretera
      ORDER BY r.id_ruta, tr.orden_tramo ASC
    ";
    $stmt = sqlsrv_query($conn, $sql);
    if (!$stmt) die(print_r(sqlsrv_errors(), true));

    $tramos = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $tramos[] = $row;
    return $tramos;
  }

  /* ============================================================
     🔹 Obtener tramos por ruta
  ============================================================ */
  public static function obtenerPorRuta($conn, $idRuta) {
    $sql = "
      SELECT 
        tr.*, tc.nombre AS tipo_carretera
      FROM Tramo_Ruta tr
      LEFT JOIN Tipo_Carretera tc ON tr.id_tipo_carretera = tc.id_tipo_carretera
      WHERE tr.id_ruta = ?
      ORDER BY tr.orden_tramo ASC
    ";
    $stmt = sqlsrv_query($conn, $sql, [$idRuta]);
    if (!$stmt) die(print_r(sqlsrv_errors(), true));
    $tramos = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $tramos[] = $row;
    return $tramos;
  }

  /* ============================================================
     🔹 Obtener un tramo específico
  ============================================================ */
  public static function obtenerPorId($conn, $id) {
    $sql = "SELECT * FROM Tramo_Ruta WHERE id_tramo = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if (!$stmt) die(print_r(sqlsrv_errors(), true));
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
  }

  /* ============================================================
     ➕ Crear tramo
  ============================================================ */
  public static function crear($conn, $data) {
    $sql = "
      INSERT INTO Tramo_Ruta 
        (id_ruta, id_tipo_carretera, orden_tramo, punto_inicio, punto_fin, distancia_km, tiempo_estimado_hr, observaciones)
      OUTPUT INSERTED.id_tramo
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $params = [
      $data['id_ruta'],
      $data['id_tipo_carretera'] ?: null,
      $data['orden_tramo'],
      $data['punto_inicio'],
      $data['punto_fin'],
      $data['distancia_km'] ?: null,
      $data['tiempo_estimado_hr'] ?: null,
      $data['observaciones'] ?: null
    ];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    sqlsrv_fetch($stmt);
    return sqlsrv_get_field($stmt, 0);
  }

  /* ============================================================
     ✏️ Actualizar tramo
  ============================================================ */
  public static function actualizar($conn, $id, $data) {
    $sql = "
      UPDATE Tramo_Ruta
      SET id_ruta=?, id_tipo_carretera=?, orden_tramo=?, punto_inicio=?, punto_fin=?, 
          distancia_km=?, tiempo_estimado_hr=?, observaciones=?
      WHERE id_tramo=?
    ";
    $params = [
      $data['id_ruta'],
      $data['id_tipo_carretera'] ?: null,
      $data['orden_tramo'],
      $data['punto_inicio'],
      $data['punto_fin'],
      $data['distancia_km'] ?: null,
      $data['tiempo_estimado_hr'] ?: null,
      $data['observaciones'] ?: null,
      $id
    ];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
  }

  /* ============================================================
     🗑️ Eliminar tramo
  ============================================================ */
  public static function eliminar($conn, $id) {
    $sql = "DELETE FROM Tramo_Ruta WHERE id_tramo = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
  }
}
?>
