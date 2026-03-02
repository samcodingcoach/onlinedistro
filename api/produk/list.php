<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/koneksi.php';

$response = array();

try {
    $query = "SELECT
                p.id_produk,
                p.id_kategori,
                kategori.nama_kategori,
                p.nama_produk,
                p.merk,
                p.kode_produk,
                p.deskripsi,
                p.harga_aktif,
                p.harga_coret,
                p.ukuran,
                p.warna,
                p.in_stok,
                p.jumlah_stok,
                p.gambar1,
                p.gambar2,
                p.gambar3,
                p.shopee_link,
                p.tiktok_link,
                p.aktif,
                p.favorit,
                p.terjual,
                p.update_at,
                p.id_admin,
                admin.username
              FROM
                produk p
                INNER JOIN
                admin
                ON
                    p.id_admin = admin.id_admin
                INNER JOIN
                kategori
                ON
                    p.id_kategori = kategori.id_kategori
              ORDER BY p.favorit DESC, p.nama_produk ASC";
    
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

$conn->close();
echo json_encode($response, JSON_PRETTY_PRINT);
