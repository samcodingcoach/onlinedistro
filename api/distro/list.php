<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    $query = "SELECT id_distro, nama_distro, alamat, kota, provinsi, no_telepon, ig, fb, email, youtube, twitter, gps, slogan, update_at FROM distro ORDER BY nama_distro ASC";
    $result = $conn->query($query);
    
    if ($result) {
        $distro_list = array();
        while ($row = $result->fetch_assoc()) {
            $distro_list[] = $row;
        }
        
        $response['success'] = true;
        $response['data'] = $distro_list;
        $response['message'] = 'Data distro berhasil diambil';
        $response['total'] = count($distro_list);
    } else {
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Query failed: ' . $conn->error;
    }
} catch (Exception $e) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
