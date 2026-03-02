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

// Validate video file upload
if (!isset($_FILES['video_file']) || $_FILES['video_file']['error'] === UPLOAD_ERR_NO_FILE) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'File video wajib diupload';
    echo json_encode($response);
    exit;
}

$file = $_FILES['video_file'];

// Check for upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Error upload file: kode ' . $file['error'];
    echo json_encode($response);
    exit;
}

// Validate file type (MP4 only)
$allowed_types = ['video/mp4', 'video/x-m4v', 'video/quicktime'];
$file_type = $file['type'];

if (!in_array($file_type, $allowed_types)) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Format file harus MP4';
    echo json_encode($response);
    exit;
}

// Validate file size (max 10MB)
$max_size = 10 * 1024 * 1024; // 10MB
if ($file['size'] > $max_size) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Ukuran file maksimal 10MB';
    echo json_encode($response);
    exit;
}

// Generate filename with timestamp
$extension = 'mp4';
$filename = time() . '.' . $extension;
$upload_dir = __DIR__ . '/../../public/videos/';
$upload_path = $upload_dir . $filename;

// Create directory if not exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Upload file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Gagal upload video';
    echo json_encode($response);
    exit;
}

// Get form data
$nama_video = $_POST['nama_video'] ?? '';
$url_forward = $_POST['url_forward'] ?? '';
$aktif = intval($_POST['aktif'] ?? 1);

// Validate required fields
if (empty($nama_video)) {
    // Delete uploaded file since validation failed
    unlink($upload_path);
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'nama_video diperlukan';
    echo json_encode($response);
    exit;
}

// Store relative path in database
$link = 'videos/' . $filename;

try {
    $query = "INSERT INTO videobanner (nama_video, link, url_forward, aktif) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nama_video, $link, $url_forward, $aktif);

    if ($stmt->execute()) {
        $id_video = $conn->insert_id;

        $response['success'] = true;
        $response['message'] = 'Video banner berhasil ditambahkan';
        $response['data'] = array(
            'id_video' => $id_video,
            'nama_video' => $nama_video,
            'link' => $link,
            'url_forward' => $url_forward,
            'aktif' => $aktif
        );
    } else {
        // Delete uploaded file since insert failed
        unlink($upload_path);
        http_response_code(500);
        $response['success'] = false;
        $response['message'] = 'Gagal menambahkan video banner: ' . $conn->error;
    }

    $stmt->close();

} catch (Exception $e) {
    // Delete uploaded file since insert failed
    unlink($upload_path);
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
?>
