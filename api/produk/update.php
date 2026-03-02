<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../auth/validate_token.php';

// Require authentication
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

// Handle both JSON and FormData input
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$gambar1 = $gambar2 = $gambar3 = null;

if (strpos($contentType, 'multipart/form-data') !== false) {
    // Handle FormData (file uploads)
    // Process uploaded files
    $imageFields = ['gambar1', 'gambar2', 'gambar3'];
    $uploadDir = __DIR__ . '/../../public/images/';
    $uploadedImages = [];
    
    foreach ($imageFields as $field) {
        if (isset($_FILES[$field]) && is_array($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$field];
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($file['type'], $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => "Format file {$file['name']} harus JPG atau PNG"]);
                exit;
            }
            
            // Validate file size (1MB)
            if ($file['size'] > 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => "Ukuran file {$file['name']} maksimal 1MB"]);
                exit;
            }
            
            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Move uploaded file
            $fileName = $file['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $uploadedImages[$field] = $fileName;
                
                // Delete old image if exists
                if (isset($_POST['old_' . $field]) && !empty($_POST['old_' . $field])) {
                    $oldImagePath = $uploadDir . $_POST['old_' . $field];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            } else {
                echo json_encode(['success' => false, 'message' => "Gagal upload gambar $field. Periksa permission folder."]);
                exit;
            }
        }
    }
    
        // Extract form data
    $id_produk      = isset($_POST['id_produk']) ? (int)$_POST['id_produk'] : 0;
    $id_kategori    = isset($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : 0;
    $nama_produk    = isset($_POST['nama_produk']) ? trim($_POST['nama_produk']) : '';
    $merk           = isset($_POST['merk']) ? trim($_POST['merk']) : '';
    $kode_produk    = isset($_POST['kode_produk']) ? trim($_POST['kode_produk']) : '';
    $deskripsi      = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : '';
    $harga_aktif    = isset($_POST['harga_aktif']) ? (float)$_POST['harga_aktif'] : 0.0;
    $harga_coret    = isset($_POST['harga_coret']) ? (float)$_POST['harga_coret'] : 0.0;
    $ukuran         = isset($_POST['ukuran']) ? trim($_POST['ukuran']) : '';
    $warna          = isset($_POST['warna']) ? trim($_POST['warna']) : '';
    $in_stok        = !empty($_POST['in_stok']) ? 1 : 0;
    $jumlah_stok    = isset($_POST['jumlah_stok']) ? (int)$_POST['jumlah_stok'] : 0;
    $shopee_link    = isset($_POST['shopee_link']) ? trim($_POST['shopee_link']) : '';
    $tiktok_link    = isset($_POST['tiktok_link']) ? trim($_POST['tiktok_link']) : '';
    $aktif          = !empty($_POST['aktif']) ? 1 : 0;
    $favorit        = !empty($_POST['favorit']) ? 1 : 0;
    $id_admin       = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : 0;
    $terjual        = isset($_POST['terjual']) ? (int)$_POST['terjual'] : 0;
    
    // Debug log
    error_log("Update Produk - id_produk extracted: $id_produk");
    
} else {
    // Handle JSON input
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        echo json_encode(['success' => false, 'message' => 'JSON tidak valid atau kosong']);
        exit;
    }
    
    // Extract data
    $id_produk      = isset($data['id_produk']) ? (int)$data['id_produk'] : 0;
    $id_kategori    = isset($data['id_kategori']) ? (int)$data['id_kategori'] : 0;
    $nama_produk    = isset($data['nama_produk']) ? trim($data['nama_produk']) : '';
    $merk           = isset($data['merk']) ? trim($data['merk']) : '';
    $kode_produk    = isset($data['kode_produk']) ? trim($data['kode_produk']) : '';
    $deskripsi      = isset($data['deskripsi']) ? $data['deskripsi'] : '';
    $harga_aktif    = isset($data['harga_aktif']) ? (float)$data['harga_aktif'] : 0.0;
    $harga_coret    = isset($data['harga_coret']) ? (float)$data['harga_coret'] : 0.0;
    $ukuran         = isset($data['ukuran']) ? trim($data['ukuran']) : '';
    $warna          = isset($data['warna']) ? trim($data['warna']) : '';
    $in_stok        = !empty($data['in_stok']) ? 1 : 0;
    $jumlah_stok    = isset($data['jumlah_stok']) ? (int)$data['jumlah_stok'] : 0;
    $shopee_link    = isset($data['shopee_link']) ? trim($data['shopee_link']) : '';
    $tiktok_link    = isset($data['tiktok_link']) ? trim($data['tiktok_link']) : '';
    $aktif          = !empty($data['aktif']) ? 1 : 0;
    $favorit        = !empty($data['favorit']) ? 1 : 0;
    $id_admin       = isset($data['id_admin']) ? (int)$data['id_admin'] : 0;
    $terjual        = isset($data['terjual']) ? (int)$data['terjual'] : 0;

    $gambar1 = isset($data['gambar1']) ? trim($data['gambar1']) : null;
    $gambar2 = isset($data['gambar2']) ? trim($data['gambar2']) : null;
    $gambar3 = isset($data['gambar3']) ? trim($data['gambar3']) : null;
}

// Validation
if ($id_produk <= 0) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'ID produk tidak valid';
    echo json_encode($response);
    exit;
}

if ($kode_produk === '') {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'kode_produk wajib diisi';
    echo json_encode($response);
    exit;
}

// Check if produk exists
$check_stmt = $conn->prepare("SELECT id_produk, gambar1, gambar2, gambar3 FROM produk WHERE id_produk = ?");
if (!$check_stmt) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Database error';
    echo json_encode($response);
    exit;
}

$check_stmt->bind_param("i", $id_produk);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    $response['success'] = false;
    $response['message'] = 'Produk tidak ditemukan';
    $check_stmt->close();
    exit;
}

$existingProduk = $result->fetch_assoc();
$check_stmt->close();

// Check for duplicate kode_produk (excluding current product)
$check_dup = $conn->prepare("SELECT id_produk FROM produk WHERE kode_produk = ? AND id_produk != ?");
if (!$check_dup) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Prepare check gagal';
    exit;
}

$check_dup->bind_param("si", $kode_produk, $id_produk);
$check_dup->execute();
$dup_result = $check_dup->get_result();
if ($dup_result && $dup_result->num_rows > 0) {
    http_response_code(400);
    $response['success'] = false;
    $response['message'] = 'Kode produk sudah digunakan oleh produk lain';
    $check_dup->close();
    $conn->close();
    exit;
}
$check_dup->close();

// Prepare image values (use new uploaded images or keep existing)
$gambar1_value = isset($uploadedImages['gambar1']) ? $uploadedImages['gambar1'] : ($gambar1 !== null ? $gambar1 : $existingProduk['gambar1']);
$gambar2_value = isset($uploadedImages['gambar2']) ? $uploadedImages['gambar2'] : ($gambar2 !== null ? $gambar2 : $existingProduk['gambar2']);
$gambar3_value = isset($uploadedImages['gambar3']) ? $uploadedImages['gambar3'] : ($gambar3 !== null ? $gambar3 : $existingProduk['gambar3']);

// Prepare UPDATE statement
$stmt = $conn->prepare("UPDATE produk SET
    id_kategori = ?,
    nama_produk = ?,
    merk = ?,
    kode_produk = ?,
    deskripsi = ?,
    harga_aktif = ?,
    harga_coret = ?,
    ukuran = ?,
    warna = ?,
    in_stok = ?,
    jumlah_stok = ?,
    gambar1 = ?,
    gambar2 = ?,
    gambar3 = ?,
    shopee_link = ?,
    tiktok_link = ?,
    aktif = ?,
    favorit = ?,
    id_admin = ?,
    terjual = ?
    WHERE id_produk = ?");

if (!$stmt) {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Prepare update gagal: ' . $conn->error;
    $conn->close();
    exit;
}

// Bind parameters
$types = 'issssdsssiissssiiiiii';
$stmt->bind_param(
    $types,
    $id_kategori,
    $nama_produk,
    $merk,
    $kode_produk,
    $deskripsi,
    $harga_aktif,
    $harga_coret,
    $ukuran,
    $warna,
    $in_stok,
    $jumlah_stok,
    $gambar1_value,
    $gambar2_value,
    $gambar3_value,
    $shopee_link,
    $tiktok_link,
    $aktif,
    $favorit,
    $id_admin,
    $terjual,
    $id_produk
);

// Execute
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Produk berhasil diupdate';
    } else {
        $response['success'] = false;
        $response['message'] = 'Tidak ada perubahan data';
    }
} else {
    http_response_code(500);
    $response['success'] = false;
    $response['message'] = 'Execute gagal: ' . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
