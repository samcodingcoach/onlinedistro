<?php
// Include koneksi dan validasi token
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/../api/auth/validate_token.php';

// Require authentication
requireAuth($conn);

// Get database name from connection
$db = $conn->query("SELECT DATABASE()")->fetch_row()[0];

$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

$sqlDump = "-- Backup Database: $db\n";
$sqlDump .= "-- Tanggal: " . date("Y-m-d H:i:s") . "\n\n";
$sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($tables as $table) {

    // Struktur tabel
    $resCreate = $conn->query("SHOW CREATE TABLE `$table`");
    $rowCreate = $resCreate->fetch_assoc();
    $sqlDump .= "\n-- Struktur tabel `$table`\n";
    $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
    $sqlDump .= $rowCreate['Create Table'] . ";\n\n";

    // Data tabel
    $resData = $conn->query("SELECT * FROM `$table`");
    if ($resData->num_rows > 0) {
        $sqlDump .= "-- Data tabel `$table`\n";
        while ($row = $resData->fetch_assoc()) {
            $fields = array_keys($row);
            $values = array_values($row);

            $fields = array_map(function($f){ return "`$f`"; }, $fields);
            $values = array_map(function($v) use ($conn) {
                if ($v === null) return "NULL";
                return "'" . $conn->real_escape_string($v) . "'";
            }, $values);

            $sqlDump .= "INSERT INTO `$table` (" . implode(",", $fields) . ") VALUES (" . implode(",", $values) . ");\n";
        }
        $sqlDump .= "\n";
    }
}

$sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

// Nama file
$filename = $db . "_backup_" . date("Y-m-d_H-i-s") . ".sql";

// Kirim ke browser untuk download
header("Content-Type: application/sql");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Length: " . strlen($sqlDump));
echo $sqlDump;
exit;
?>
