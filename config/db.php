<?php
$serverName = "DESKTOP-7FKU6SO\\SQLEXPRESS01";
$connectionOptions = [
    "Database" => "LogisticaGlobal",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("❌ Error de conexión: " . print_r(sqlsrv_errors(), true));
}

return $conn;
?>
