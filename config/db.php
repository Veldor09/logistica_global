<?php
/**
 * config/db.php
 * Conexión SQL Server (sqlsrv) con soporte para .env/variables de entorno.
 * Uso:
 *   require_once __DIR__ . '/db.php';
 *   $conn = db(); // obtiene el handler de conexión
 */

if (!function_exists('db')) {
  function db() {
    static $conn = null; // reusar la misma conexión en todo el request
    if ($conn !== null) return $conn;

    // Zona horaria (ajústala si corresponde)
    if (function_exists('date_default_timezone_set')) {
      date_default_timezone_set('America/Costa_Rica');
    }

    // Puedes definir estas variables en tu entorno/hosting:
    // DB_SERVER, DB_DATABASE, DB_USERNAME, DB_PASSWORD, DB_ENCRYPT, DB_TRUST_CERT
    $serverName = getenv('DB_SERVER') ?: "DESKTOP-7FKU6SO\\SQLEXPRESS01";
    $database   = getenv('DB_DATABASE') ?: "LogisticaGlobal";
    $username   = getenv('DB_USERNAME') ?: null; // ej: "sa"
    $password   = getenv('DB_PASSWORD') ?: null; // ej: "tu_contraseña"

    // Por defecto, desde los drivers recientes Encrypt=true.
    // En desarrollo, para evitar errores de certificado, activamos TrustServerCertificate.
    // En producción: usa un cert válido y pon TrustServerCertificate=false.
    $encrypt    = getenv('DB_ENCRYPT') !== false ? filter_var(getenv('DB_ENCRYPT'), FILTER_VALIDATE_BOOL) : true;
    $trustCert  = getenv('DB_TRUST_CERT') !== false ? filter_var(getenv('DB_TRUST_CERT'), FILTER_VALIDATE_BOOL) : true;

    $connectionOptions = [
      "Database"             => $database,
      "CharacterSet"         => "UTF-8",
      "Encrypt"              => $encrypt,
      "TrustServerCertificate"=> $trustCert,
      // "LoginTimeout"       => 5, // opcional
    ];

    // Si tienes autenticación SQL (no Windows Auth), agrega UID/PWD
    if (!empty($username)) $connectionOptions["UID"] = $username;
    if (!empty($password)) $connectionOptions["PWD"] = $password;

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
      // Error legible en desarrollo
      $errors = print_r(sqlsrv_errors(), true);
      die("❌ Error de conexión a SQL Server ({$serverName}/{$database}):\n{$errors}");
    }

    return $conn;
  }
}

// Si prefieres mantener compatibilidad con código que espera $conn global:
$conn = db();
