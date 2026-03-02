<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');
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
if (!$data || !isset($data['id_banner']) || !isset($data['nama_banner']) || !isset($data['judul'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Field id_banner, nama_banner, dan judul wajib diisi'
    ]);
    exit;
}

try {
    $id_banner = (int)$data['id_banner'];
    $nama_banner = $data['nama_banner'];
    $judul = $data['judul'];
    $deskripsi = isset($data['deskripsi']) ? $data['deskripsi'] : '';
    $url_gambar = isset($data['url_gambar']) ? $data['url_gambar'] : '';
    $aktif = isset($data['aktif']) ? (int)$data['aktif'] : 0;
    
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
    
    // Update banner
    $query = "UPDATE banner SET nama_banner = ?, judul = ?, deskripsi = ?, url_gambar = ?, aktif = ? WHERE id_banner = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssii", $nama_banner, $judul, $deskripsi, $url_gambar, $aktif, $id_banner);
    $result = $stmt->execute();
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    if ($stmt->affected_rows === 0) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Tidak ada perubahan data banner',
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
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Banner berhasil diperbarui',
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
        'message' => 'Gagal memperbarui banner: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
