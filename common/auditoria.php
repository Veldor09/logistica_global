<?php
// ===========================================================
// 🧾 common/auditoria.php
// Módulo central de registro de acciones del sistema
// ===========================================================

if (!function_exists('registrarAccion')) {
  /**
   * 📜 Registra una acción en la tabla Registro_Accion
   *
   * Ejemplo de uso:
   * registrarAccion($conn, 'admin@correo.com', 'Usuario', 'INSERT', 'Se creó el usuario Juan Pérez', 15);
   *
   * @param resource $conn        Conexión SQL Server activa
   * @param string   $usuario     Correo o nombre del usuario que ejecuta la acción
   * @param string   $modulo      Módulo o entidad afectada (Usuario, Rol, Login, etc.)
   * @param string   $accion      Tipo de acción (INSERT, UPDATE, DELETE, LOGIN, etc.)
   * @param string   $descripcion Descripción detallada de la acción
   * @param int|null $registroId  ID del registro afectado (opcional)
   */
  function registrarAccion($conn, $usuario, $modulo, $accion, $descripcion, $registroId = null)
  {
    try {
      // ⚙️ Inserción de registro en tabla de auditoría
      $sql = "
        INSERT INTO Registro_Accion (usuario, modulo, accion, descripcion, registro_id, fecha)
        VALUES (?, ?, ?, ?, ?, SYSDATETIME())
      ";

      $params = [
        $usuario,
        $modulo,
        strtoupper(trim($accion)),
        $descripcion,
        $registroId
      ];

      $stmt = sqlsrv_query($conn, $sql, $params);

      if (!$stmt) {
        // No detener la ejecución si falla la auditoría, solo registrar en log
        error_log('❌ Error al registrar acción en auditoría: ' . print_r(sqlsrv_errors(), true));
      }
    } catch (Throwable $e) {
      error_log('⚠️ Excepción en auditoría: ' . $e->getMessage());
    }
  }
}
?>
