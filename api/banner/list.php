<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

try {
    // Query untuk mengambil data dari tabel banner
    $query = "SELECT id_banner, nama_banner, judul, deskripsi, url_gambar, aktif, created_at FROM banner";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $banners = array();
    
    while ($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }
    
    // Return JSON response
    echo json_encode([
        'status' => 'success',
        'data' => $banners,
        'count' => count($banners)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>