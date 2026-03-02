<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../auth/validate_token.php';

// Require authentication for POST
$admin = requireAuth($conn);

$response = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['success'] = false;
    $response['message'] = 'Method not allowed';
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
    $background_url = null;
}

// Get form data
$nama_kategori = $_POST['nama_kategori'] ?? '';
$favorit = intval($_POST['favorit'] ?? 0);
$aktif = intval($_POST['aktif'] ?? 1);

if (empty($nama_kategori)) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'nama_kategori diperlukan';
    echo json_encode($response);
    exit;
}

try {
    $query = "INSERT INTO kategori (nama_kategori, background_url, favorit, aktif) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $nama_kategori, $background_url, $favorit, $aktif);
    
    if ($stmt->execute()) {
        $id_kategori = $conn->insert_id;
        
        $response['success'] = true;
        $response['message'] = 'Kategori berhasil ditambahkan';
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
        $response['message'] = 'Gagal menambahkan kategori: ' . $conn->error;
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
