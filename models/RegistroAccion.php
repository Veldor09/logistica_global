<?php
// models/RegistroAccion.php

if (!class_exists('RegistroAccion')) {
class RegistroAccion
{
  /* ============================================================
     📋 Obtener todos los registros (últimos 200)
  ============================================================ */
  public static function obtenerTodos($conn)
  {
    $sql = "
      SELECT TOP 200
        id_registro,
        usuario,
        usuario_id,
        rol,
        modulo,
        accion,
        descripcion,
        fecha
      FROM Registro_Accion
      ORDER BY fecha DESC
    ";

    $stmt = sqlsrv_query($conn, $sql);
    if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));

    $registros = [];
    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      if ($r['fecha'] instanceof DateTime) {
        $r['fecha'] = $r['fecha']->format('Y-m-d H:i:s');
      }
      $registros[] = $r;
    }
    return $registros;
  }
}
}
?>
