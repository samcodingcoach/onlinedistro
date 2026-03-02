<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    $query = "SELECT id_video, nama_video, link, url_forward, aktif, created_date 
              FROM videobanner 
              ORDER BY created_date DESC";

    $result = $conn->query($query);

    if ($result) {
        $video_list = array();
        while ($row = $result->fetch_assoc()) {
            $video_list[] = $row;
        }

        $response['success'] = true;
        $response['data'] = $video_list;
        $response['message'] = 'Data videobanner berhasil diambil';
        $response['total'] = count($video_list);
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
