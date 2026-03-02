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

// Handle form data (file upload)
if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['background_image'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $file_type = $file['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        http_response_code(400);
        $response['success'] = false;
        $response['message'] = 'Format file harus JPG atau PNG';
        echo json_encode($response);
        exit;
    }
    
    // Validate file size (500KB)
    if ($file['size'] > 500 * 1024) {
        http_response_code(400);
        $response['success'] = false;
        $response['message'] = 'Ukuran file maksimal 500KB';
        echo json_encode($response);
        exit;
    }
    
    // Generate random filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $random_name = 'kategori_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $upload_path = __DIR__ . '/../../public/images/' . $random_name;
    
    // Create directory if not exists
    $upload_dir = dirname($upload_path);
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        $background_url = $random_name;
    } else {
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Gagal upload gambar';
        echo json_encode($response);
        exit;
    }
} else {
    // Keep existing background_url if no new image is uploaded
    $background_url = null; // Will be handled in the query
}

// Get form data
$id_kategori = $_POST['id_kategori'] ?? '';
$nama_kategori = $_POST['nama_kategori'] ?? '';
$favorit = intval($_POST['favorit'] ?? 0);
$aktif = intval($_POST['aktif'] ?? 1);

if (empty($id_kategori)) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'id_kategori diperlukan';
    echo json_encode($response);
    exit;
}

if (empty($nama_kategori)) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'nama_kategori diperlukan';
    echo json_encode($response);
    exit;
}

try {
    // Check if kategori exists
    $check_query = "SELECT id_kategori FROM kategori WHERE id_kategori = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $id_kategori);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        http_response_code(404);
        $response['success'] = false;
        $response['message'] = 'Kategori tidak ditemukan';
        echo json_encode($response);
        exit;
    }
    
    // Update with or without new image
    if ($background_url !== null) {
        // New image uploaded - update all fields including background_url
        $update_query = "UPDATE kategori SET nama_kategori = ?, background_url = ?, favorit = ?, aktif = ? WHERE id_kategori = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssiii", $nama_kategori, $background_url, $favorit, $aktif, $id_kategori);
    } else {
        // No new image - update all fields except background_url
        $update_query = "UPDATE kategori SET nama_kategori = ?, favorit = ?, aktif = ? WHERE id_kategori = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("siii", $nama_kategori, $favorit, $aktif, $id_kategori);
    }
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Kategori berhasil diupdate';
        $response['data'] = array(
            'id_kategori' => $id_kategori,
            'nama_kategori' => $nama_kategori,
            'background_url' => $background_url,
            'favorit' => $favorit,
            'aktif' => $aktif
        );
    } else {
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Gagal update kategori: ' . $conn->error;
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
