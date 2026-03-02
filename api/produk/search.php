<?php
// Start output buffering to prevent any errors from being output before JSON
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    $search_term = isset($_GET['q']) ? trim($_GET['q']) : '';

    $query = "SELECT
                produk.id_produk,
                produk.nama_produk,
                kategori.nama_kategori,
                produk.merk,
                produk.kode_produk,
                produk.warna,
                produk.harga_aktif,
                produk.harga_coret,
                produk.gambar1,
                produk.gambar2,
                produk.gambar3,
                produk.terjual,
                produk.in_stok
              FROM
                produk
                INNER JOIN
                kategori
                ON
                    produk.id_kategori = kategori.id_kategori
              WHERE produk.aktif = 1";

    if (!empty($search_term)) {
        $search_term = $conn->real_escape_string($search_term);
        $query .= " AND (produk.nama_produk LIKE '%$search_term%'
                    OR produk.kode_produk LIKE '%$search_term%'
                    OR produk.merk LIKE '%$search_term%'
                    OR kategori.nama_kategori LIKE '%$search_term%')";
    }

    $query .= " ORDER BY nama_produk ASC";

    $result = $conn->query($query);

    if ($result) {
        $produk_list = array();
        while ($row = $result->fetch_assoc()) {
            $produk_list[] = $row;
        }

        $response['success'] = true;
        $response['data'] = $produk_list;
        $response['message'] = 'Data produk berhasil diambil';
    } else {
        $response['success'] = false;
        $response['message'] = 'Query failed: ' . $conn->error;
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Clean any output buffer before sending JSON
ob_clean();

$conn->close();
echo json_encode($response, JSON_UNESCAPED_SLASHES);
// End output buffering
ob_end_flush();
?>
