<?php
// ============================================================
// 📘 MODELO: TipoEvento.php
// Gestión de tipos de evento registrados en la tabla Tipo_Evento
// ============================================================

if (!class_exists('TipoEvento')) {
class TipoEvento
{
    /* ============================================================
       📋 Obtener todos los tipos de evento
    ============================================================ */
    public static function obtenerTodos($conn)
    {
        $sql = "
            SELECT id_tipo_evento, nombre, descripcion, estado
            FROM Tipo_Evento
            ORDER BY nombre ASC
        ";

        $stmt = sqlsrv_query($conn, $sql);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $tipos = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    /* ============================================================
       🔍 Obtener tipo de evento por ID
    ============================================================ */
    public static function obtenerPorId($conn, $id)
    {
        $sql = "
            SELECT id_tipo_evento, nombre, descripcion, estado
            FROM Tipo_Evento
            WHERE id_tipo_evento = ?
        ";

        $stmt = sqlsrv_query($conn, $sql, [(int)$id]);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row ?: null;
    }

    /* ============================================================
       🆕 Crear tipo de evento
    ============================================================ */
    public static function crear($conn, $data)
    {
        $sql = "
            INSERT INTO Tipo_Evento (nombre, descripcion, estado)
            OUTPUT INSERTED.id_tipo_evento
            VALUES (?, ?, ?)
        ";

        $params = [
            trim($data['nombre']),
            $data['descripcion'] ?? null,
            $data['estado'] ?? 'Activo'
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        sqlsrv_fetch($stmt);
        return sqlsrv_get_field($stmt, 0);
    }

    /* ============================================================
       ✏️ Actualizar tipo de evento
    ============================================================ */
    public static function actualizar($conn, $id, $data)
    {
        $sql = "
            UPDATE Tipo_Evento
            SET nombre = ?, descripcion = ?, estado = ?
            WHERE id_tipo_evento = ?
        ";

        $params = [
            trim($data['nombre']),
            $data['descripcion'] ?? null,
            $data['estado'] ?? 'Activo',
            (int)$id
        ];

        $stmt = sqlsrv_query($conn, $sql, $params);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        return true;
    }

    /* ============================================================
       🗑️ Eliminar tipo de evento
    ============================================================ */
    public static function eliminar($conn, $id)
    {
        $sql = "DELETE FROM Tipo_Evento WHERE id_tipo_evento = ?";
        $stmt = sqlsrv_query($conn, $sql, [(int)$id]);
        if (!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        return true;
    }
}}
?>
