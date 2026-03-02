<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/validate_token.php';

// Require authentication for POST
$admin = requireAuth($conn);

$response = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['success'] = false;
    $response['message'] = 'Method not allowed (use POST)';
    echo json_encode($response);
    exit;
}

// Get JSON input
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Username dan password diperlukan';
    echo json_encode($response);
    exit;
}

$username = trim($data['username']);
$password = trim($data['password']);

// Validation
if (empty($username)) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Username tidak boleh kosong';
    echo json_encode($response);
    exit;
}

if (strlen($username) < 3) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Username minimal 3 karakter';
    echo json_encode($response);
    exit;
}

if (strlen($password) < 4) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Password minimal 4 karakter';
    echo json_encode($response);
    exit;
}

try {
    // Check if username already exists
    $check_query = "SELECT id_admin FROM admin WHERE username = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        http_response_code(409);
        $response['success'] = false;
        $response['message'] = 'Username sudah digunakan, silakan pilih username lain';
        echo json_encode($response);
        exit;
    }
    
    // Insert new admin
    $insert_query = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ss", $username, $password);
    
    if ($insert_stmt->execute()) {
        $id_admin = $conn->insert_id;
        
        $response['success'] = true;
        $response['message'] = 'Admin berhasil ditambahkan';
        $response['data'] = array(
            'id_admin' => $id_admin,
            'username' => $username
        );
    } else {
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Gagal menambahkan admin: ' . $conn->error;
    }
    
    $check_stmt->close();
    $insert_stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
