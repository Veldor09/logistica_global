<?php
class TipoCarretera {

  /* ============================================================
     📋 Obtener todos los tipos
  ============================================================ */
  public static function obtenerTodos($conn) {
    $sql = "SELECT id_tipo_carretera, nombre, descripcion FROM Tipo_Carretera ORDER BY id_tipo_carretera DESC";
    $stmt = sqlsrv_query($conn, $sql);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

    $tipos = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $tipos[] = $row;
    return $tipos;
  }

  /* ============================================================
     🔍 Obtener tipo por ID
  ============================================================ */
  public static function obtenerPorId($conn, $id) {
    $sql = "SELECT * FROM Tipo_Carretera WHERE id_tipo_carretera = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
  }

  /* ============================================================
     ➕ Crear tipo
  ============================================================ */
  public static function crear($conn, $data) {
    $sql = "
      INSERT INTO Tipo_Carretera (nombre, descripcion)
      OUTPUT INSERTED.id_tipo_carretera
      VALUES (?, ?)
    ";
    $params = [$data['nombre'], $data['descripcion'] ?: null];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

    sqlsrv_fetch($stmt);
    return sqlsrv_get_field($stmt, 0);
  }

  /* ============================================================
     ✏️ Actualizar tipo
  ============================================================ */
  public static function actualizar($conn, $id, $data) {
    $sql = "UPDATE Tipo_Carretera SET nombre=?, descripcion=? WHERE id_tipo_carretera=?";
    $params = [$data['nombre'], $data['descripcion'] ?: null, $id];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
  }

  /* ============================================================
     🗑️ Eliminar tipo
  ============================================================ */
  public static function eliminar($conn, $id) {
    $sql = "DELETE FROM Tipo_Carretera WHERE id_tipo_carretera = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
  }
}
?>
