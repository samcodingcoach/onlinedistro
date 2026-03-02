<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    $query = "SELECT id_kategori, nama_kategori, background_url, favorit, aktif FROM kategori ORDER BY favorit DESC, nama_kategori ASC";
    $result = $conn->query($query);
    
    if ($result) {
        $kategori_list = array();
        while ($row = $result->fetch_assoc()) {
            $kategori_list[] = $row;
        }
        
        $response['success'] = true;
        $response['data'] = $kategori_list;
        $response['message'] = 'Data kategori berhasil diambil';
    } else {
        $response['success'] = false;
        $response['message'] = 'Query failed: ' . $conn->error;
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
