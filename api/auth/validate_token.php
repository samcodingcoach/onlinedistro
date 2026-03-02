<?php
function validateSessionToken($conn, $token) {
    if (!$token) {
        return false;
    }
    
    $query = "SELECT id_admin, username FROM admin WHERE session_token = ? AND token_expiry > NOW() AND aktif = 1 LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return false;
}

function requireAuth($conn) {
    $headers = getallheaders();
    $token = null;
    
    // Try multiple ways to get the Authorization header
    if (isset($headers['Authorization'])) {
        $auth_header = $headers['Authorization'];
        if (strpos($auth_header, 'Bearer ') === 0) {
            $token = substr($auth_header, 7);
        }
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($auth_header, 'Bearer ') === 0) {
            $token = substr($auth_header, 7);
        }
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        if (strpos($auth_header, 'Bearer ') === 0) {
            $token = substr($auth_header, 7);
        }
    } elseif (isset($_GET['token'])) {
        $token = $_GET['token'];
    }
    
    if (!$token) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Token diperlukan'
        ]);
        exit;
    }
    
    $admin = validateSessionToken($conn, $token);
    if (!$admin) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Token tidak valid atau kadaluarsa'
        ]);
        exit;
    }
    
    return $admin;
}
?>
