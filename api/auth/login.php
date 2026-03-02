<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['success'] = false;
    $response['message'] = 'Method not allowed';
    echo json_encode($response);
    exit;
}

$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    $response['success'] = false;
    $response['message'] = 'Username dan password diperlukan';
    echo json_encode($response);
    exit;
}

$username = $data['username'];
$password = $data['password'];

try {
    $query = "SELECT id_admin, username, password, aktif FROM admin WHERE username = ? AND aktif = 1 LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        if ($password === $admin['password'] || password_verify($password, $admin['password'])) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $update_query = "UPDATE admin SET session_token = ?, token_expiry = ? WHERE id_admin = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ssi", $token, $expiry, $admin['id_admin']);
            
            if ($update_stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Login berhasil';
                $response['data'] = array(
                    'token' => $token,
                    'expiry' => $expiry,
                    'admin' => array(
                        'id_admin' => $admin['id_admin'],
                        'username' => $admin['username']
                    )
                );
            } else {
                $response['success'] = false;
                $response['message'] = 'Gagal membuat session token';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Password salah';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Username tidak ditemukan atau tidak aktif';
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
