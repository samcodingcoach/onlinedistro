<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
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

// Validate required fields
if (!$data || !isset($data['nama_banner']) || !isset($data['judul'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Field nama_banner dan judul wajib diisi'
    ]);
    exit;
}

try {
    $nama_banner = $data['nama_banner'];
    $judul = $data['judul'];
    $deskripsi = isset($data['deskripsi']) ? $data['deskripsi'] : '';
    $url_gambar = isset($data['url_gambar']) ? $data['url_gambar'] : '';
    $aktif = isset($data['aktif']) ? (int)$data['aktif'] : 0;
    
    $query = "INSERT INTO banner (nama_banner, judul, deskripsi, url_gambar, aktif) 
              VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama_banner, $judul, $deskripsi, $url_gambar, $aktif);
    $result = $stmt->execute();
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $id_banner = $stmt->insert_id;
    
    echo json_encode([
        'success' => true,
        'message' => 'Banner berhasil ditambahkan',
        'data' => [
            'id_banner' => $id_banner,
            'nama_banner' => $nama_banner,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'url_gambar' => $url_gambar,
            'aktif' => $aktif
        ]
    ]);
    
    $stmt->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menambahkan banner: ' . $e->getMessage()
    ]);
}

$conn->close();
?>