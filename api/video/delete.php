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

    // Get id_video
    $id_video = isset($data['id_video']) ? (int)$data['id_video'] : 0;

    if ($id_video <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID video tidak valid'
        ]);
        exit;
    }

    // Check if video exists
    $check_stmt = $conn->prepare("SELECT id_video, link FROM videobanner WHERE id_video = ?");
    if (!$check_stmt) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error'
        ]);
        exit;
    }

    $check_stmt->bind_param("i", $id_video);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Video tidak ditemukan'
        ]);
        $check_stmt->close();
        exit;
    }

    $video = $result->fetch_assoc();
    $check_stmt->close();

    // Delete video file if it exists
    if (!empty($video['link'])) {
        $videoPath = __DIR__ . '/../../public/' . $video['link'];
        if (file_exists($videoPath)) {
            unlink($videoPath);
        }
    }

    // Delete from database
    $delete_stmt = $conn->prepare("DELETE FROM videobanner WHERE id_video = ?");
    if (!$delete_stmt) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyiapkan query hapus'
        ]);
        exit;
    }

    $delete_stmt->bind_param("i", $id_video);

    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Video berhasil dihapus'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Video tidak ditemukan'
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus video: ' . $delete_stmt->error
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
