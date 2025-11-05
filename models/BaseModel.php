<?php
abstract class BaseModel
{
    protected $conn;

    public function __construct($conn)
    {
        if (!$conn) throw new Exception("Conexión SQLSRV inválida.");
        $this->conn = $conn;
    }

    /* ========== TX ========== */
    public function begin(): void
    {
        if (!sqlsrv_begin_transaction($this->conn)) throw new Exception(print_r(sqlsrv_errors(), true));
    }
    public function commit(): void
    {
        if (!sqlsrv_commit($this->conn)) throw new Exception(print_r(sqlsrv_errors(), true));
    }
    public function rollback(): void
    {
        sqlsrv_rollback($this->conn);
    }

    /* ========== EXECUTORS ========== */
    protected function exec(string $sql, array $params = [])
    {
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        if (!$stmt) throw new Exception(print_r(sqlsrv_errors(), true));
        return $stmt;
    }

    protected function execInsert(string $sql, array $params = []): int
    {
        $stmt = $this->exec($sql, $params);
        if (!sqlsrv_fetch($stmt)) throw new Exception("No se pudo obtener el ID insertado.");
        return (int) sqlsrv_get_field($stmt, 0);
    }

    /* ========== FETCHERS ========== */
    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->fetchAllAssoc($this->exec($sql, $params));
    }

    protected function fetchOne(string $sql, array $params = []): ?array
    {
        return $this->fetchOneAssoc($this->exec($sql, $params));
    }

    private function fetchAllAssoc($stmt): array
    {
        $out = []; while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $out[] = $row;
        sqlsrv_free_stmt($stmt); return $out;
    }

    private function fetchOneAssoc($stmt): ?array
    {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC); sqlsrv_free_stmt($stmt); return $row ?: null;
    }

    /* ========== PAGINATION ========== */
    protected function paginate(string $select, array $params, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "$select ORDER BY 1 DESC OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
        $params[] = $offset; $params[] = $perPage;
        return $this->fetchAll($sql, $params);
    }

    /* ========== VALIDATION ========== */
    protected function exists(string $table, string $column, $value): bool
    {
        $stmt = $this->exec("SELECT 1 FROM $table WHERE $column = ?", [$value]);
        return sqlsrv_fetch($stmt) === true;
    }
}