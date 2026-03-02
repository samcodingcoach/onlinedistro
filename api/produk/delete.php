<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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

// Get input data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's JSON input
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (strpos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
    } else {
        // Fallback to POST data
        $data = $_POST;
    }
    
    // Get id_produk
    $id_produk = isset($data['id_produk']) ? (int)$data['id_produk'] : 0;
    
    if ($id_produk <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID produk tidak valid'
        ]);
        exit;
    }
    
    // Check if produk exists
    $check_stmt = $conn->prepare("SELECT id_produk, gambar1, gambar2, gambar3 FROM produk WHERE id_produk = ?");
    if (!$check_stmt) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error'
        ]);
        exit;
    }
    
    $check_stmt->bind_param("i", $id_produk);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ]);
        $check_stmt->close();
        exit;
    }
    
    $produk = $result->fetch_assoc();
    $check_stmt->close();
    
    // Delete image files if they exist
    $imageDir = __DIR__ . '/../../public/images/';
    $imageFields = ['gambar1', 'gambar2', 'gambar3'];
    
    foreach ($imageFields as $field) {
        if (!empty($produk[$field])) {
            $imagePath = $imageDir . $produk[$field];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }
    
    // Delete from database
    $delete_stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
    if (!$delete_stmt) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyiapkan query hapus'
        ]);
        exit;
    }
    
    $delete_stmt->bind_param("i", $id_produk);
    
    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus produk: ' . $delete_stmt->error
        ]);
    }
    
    $delete_stmt->close();
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
}

$conn->close();
