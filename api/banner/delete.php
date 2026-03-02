<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/koneksi.php';

// Get Authorization header
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
$token = str_replace('Bearer ', '', $authHeader);

// Validate token
if (empty($token)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Token authorization diperlukan'
    ]);
    exit;
}

// Get JSON input
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

// Validate required field
if (!$data || !isset($data['id_banner'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Field id_banner wajib diisi'
    ]);
    exit;
}

try {
    $id_banner = (int)$data['id_banner'];
    
    // Check if banner exists
    $checkQuery = "SELECT id_banner FROM banner WHERE id_banner = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $id_banner);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Banner tidak ditemukan'
        ]);
        $checkStmt->close();
        exit;
    }
    
    $checkStmt->close();
    
    // Delete banner
    $query = "DELETE FROM banner WHERE id_banner = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_banner);
    $result = $stmt->execute();
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Banner tidak ditemukan atau sudah dihapus'
        ]);
        $stmt->close();
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Banner berhasil dihapus',
        'data' => [
            'id_banner' => $id_banner
        ]
    ]);
    
    $stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menghapus banner: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
