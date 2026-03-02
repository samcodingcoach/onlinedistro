<?php
// API endpoint to fetch product details for modal
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once __DIR__ . '/../config/koneksi.php';

// Get product ID from request
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID'
    ]);
    exit;
}

// Fetch product details
try {
    $query = "SELECT * FROM produk WHERE id_produk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        // Add sample data for missing fields
        $product['brand'] = $product['merk'] ?? 'Brand Name';
        $product['deskripsi'] = $product['deskripsi'] ?? 'Crafted from premium materials with attention to detail and quality. This piece combines style and comfort for the perfect addition to your wardrobe.';
        
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
