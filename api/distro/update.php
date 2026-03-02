<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../auth/validate_token.php';

// Require authentication for update
$admin = requireAuth($conn);

$response = array();

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'PUT' && $method !== 'POST') {
    http_response_code(405);
    $response['success'] = false;
    $response['message'] = 'Method not allowed (use PUT or POST)';
    echo json_encode($response);
    exit;
}

$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if (!$data || !isset($data['id_distro'])) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'id_distro diperlukan';
    echo json_encode($response);
    exit;
}

$id_distro = $data['id_distro'];
$nama_distro = $data['nama_distro'] ?? null;
$alamat = $data['alamat'] ?? null;
$kota = $data['kota'] ?? null;
$provinsi = $data['provinsi'] ?? null;
$no_telepon = $data['no_telepon'] ?? null;
$ig = $data['ig'] ?? null;
$fb = $data['fb'] ?? null;
$email = $data['email'] ?? null;
$youtube = $data['youtube'] ?? null;
$twitter = $data['twitter'] ?? null;
$gps = $data['gps'] ?? null;
$slogan = $data['slogan'] ?? null;

try {
    // Check if distro exists
    $check_query = "SELECT id_distro FROM distro WHERE id_distro = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $id_distro);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        http_response_code(404);
        $response['success'] = false;
        $response['message'] = 'Distro tidak ditemukan';
        echo json_encode($response);
        exit;
    }
    
    // Build dynamic update query
    $update_fields = array();
    $types = "";
    $values = array();
    
    if ($nama_distro !== null) {
        $update_fields[] = "nama_distro = ?";
        $types .= "s";
        $values[] = $nama_distro;
    }
    if ($alamat !== null) {
        $update_fields[] = "alamat = ?";
        $types .= "s";
        $values[] = $alamat;
    }
    if ($kota !== null) {
        $update_fields[] = "kota = ?";
        $types .= "s";
        $values[] = $kota;
    }
    if ($provinsi !== null) {
        $update_fields[] = "provinsi = ?";
        $types .= "s";
        $values[] = $provinsi;
    }
    if ($no_telepon !== null) {
        $update_fields[] = "no_telepon = ?";
        $types .= "s";
        $values[] = $no_telepon;
    }
    if ($ig !== null) {
        $update_fields[] = "ig = ?";
        $types .= "s";
        $values[] = $ig;
    }
    if ($fb !== null) {
        $update_fields[] = "fb = ?";
        $types .= "s";
        $values[] = $fb;
    }
    if ($email !== null) {
        $update_fields[] = "email = ?";
        $types .= "s";
        $values[] = $email;
    }
    if ($youtube !== null) {
        $update_fields[] = "youtube = ?";
        $types .= "s";
        $values[] = $youtube;
    }
    if ($twitter !== null) {
        $update_fields[] = "twitter = ?";
        $types .= "s";
        $values[] = $twitter;
    }
    if ($gps !== null) {
        $update_fields[] = "gps = ?";
        $types .= "s";
        $values[] = $gps;
    }
    if ($slogan !== null) {
        $update_fields[] = "slogan = ?";
        $types .= "s";
        $values[] = $slogan;
    }
    
    // Always update timestamp
    $update_fields[] = "update_at = CURRENT_TIMESTAMP()";
    
    if (empty($update_fields)) {
        http_response_code(400);
        $response['success'] = false;
        $response['message'] = 'Tidak ada field yang akan diupdate';
        echo json_encode($response);
        exit;
    }
    
    $types .= "i";
    $values[] = $id_distro;
    
    $update_query = "UPDATE distro SET " . implode(", ", $update_fields) . " WHERE id_distro = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param($types, ...$values);
    
    if ($stmt->execute()) {
        // Get updated data
        $select_query = "SELECT * FROM distro WHERE id_distro = ? LIMIT 1";
        $select_stmt = $conn->prepare($select_query);
        $select_stmt->bind_param("i", $id_distro);
        $select_stmt->execute();
        $select_result = $select_stmt->get_result();
        $updated_data = $select_result->fetch_assoc();
        
        $response['success'] = true;
        $response['message'] = 'Distro berhasil diupdate';
        $response['data'] = $updated_data;
    } else {
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Gagal update distro: ' . $conn->error;
    }
    
    $stmt->close();
    $check_stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
