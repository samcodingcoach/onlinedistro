<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    $query = "SELECT DISTINCT merk FROM produk ORDER BY merk ASC";
    
    $result = $conn->query($query);
    
    if ($result) {
        $merk_list = array();
        while ($row = $result->fetch_assoc()) {
            $merk_list[] = $row;
        }
        
        $response['success'] = true;
        $response['data'] = $merk_list;
        $response['message'] = 'Daftar merk berhasil diambil';
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
