<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/validate_token.php';

$response = array();

try {
    // Require authentication for GET
    $admin = requireAuth($conn);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        $response['success'] = false;
        $response['message'] = 'Method not allowed (use GET)';
        echo json_encode($response);
        exit;
    }
    
    $query = "SELECT id_admin, username, aktif, token_expiry FROM admin ORDER BY id_admin DESC";
    $result = $conn->query($query);
    
    if ($result) {
        $admin_list = array();
        while ($row = $result->fetch_assoc()) {
            $admin_list[] = $row;
        }
        
        $response['success'] = true;
        $response['data'] = $admin_list;
        $response['message'] = 'Data admin berhasil diambil';
    } else {
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Query failed: ' . $conn->error;
    }
    
} catch (Exception $e) {
    http_response_code(401);
    $response['success'] = false;
    $response['message'] = 'Authentication failed: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
