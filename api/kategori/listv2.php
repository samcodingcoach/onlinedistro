<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    // Get pagination parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;
    
    // Get total count for pagination
    $count_query = "SELECT COUNT(DISTINCT k.id_kategori) as total FROM kategori k";
    $count_result = $conn->query($count_query);
    $total_count = 0;
    
    if ($count_result) {
        $count_row = $count_result->fetch_assoc();
        $total_count = $count_row['total'];
    }
    
    // Get paginated data
    $query = "SELECT
    k.id_kategori,
    k.nama_kategori,
    k.background_url,
    COUNT(p.id_kategori) AS jumlah_produk
FROM kategori k
LEFT JOIN produk p 
    ON p.id_kategori = k.id_kategori
GROUP BY
    k.id_kategori,
    k.nama_kategori,
    k.background_url
ORDER BY
    k.nama_kategori ASC
LIMIT $limit OFFSET $offset";
    $result = $conn->query($query);
    
    if ($result) {
        $kategori_list = array();
        while ($row = $result->fetch_assoc()) {
            $kategori_list[] = $row;
        }
        
        // Calculate pagination info
        $total_pages = ceil($total_count / $limit);
        
        $response['success'] = true;
        $response['data'] = $kategori_list;
        $response['pagination'] = [
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total_count,
            'total_pages' => $total_pages
        ];
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
