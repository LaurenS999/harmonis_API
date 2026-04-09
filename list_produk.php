<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'connectDb.php';

// 1. Ambil parameter dari request (GET atau POST)
// Menggunakan $_GET agar filter bisa terlihat di URL atau mudah ditesting
$searchNama  = isset($_GET['nama']) ? $_GET['nama'] : '';
$idJenis     = isset($_GET['id_jenis']) ? $_GET['id_jenis'] : '';
$idMerek     = isset($_GET['id_merek']) ? $_GET['id_merek'] : '';

// 2. Query dasar
$sql = "SELECT p.id_produk, p.nama_produk, p.harga_beli_produk, p.harga_jual_produk, 
               p.jumlah_stok, p.deskripsi_lain_produk, p.foto_produk, p.id_jenis_produk, 
               jp.nama_jenis_produk, jp.jenis_produk_hapus, p.id_merek, m.nama_merek, 
               m.merek_hapus, p.produk_hapus 
        FROM produk p 
        INNER JOIN merek m ON p.id_merek = m.id_merek 
        INNER JOIN jenis_produk jp ON p.id_jenis_produk = jp.id_jenis_produk 
        WHERE p.produk_hapus = 0";

// 3. Tambahkan kondisi filter secara dinamis
$params = [];
$types = "";

if (!empty($searchNama)) {
    $sql .= " AND p.nama_produk LIKE ?";
    $params[] = "%$searchNama%"; // Mencari nama yang mengandung kata tersebut
    $types .= "s"; // s = string
}

if (!empty($idJenis)) {
    $sql .= " AND p.id_jenis_produk = ?";
    $params[] = $idJenis;
    $types .= "i"; // i = integer
}

if (!empty($idMerek)) {
    $sql .= " AND p.id_merek = ?";
    $params[] = $idMerek;
    $types .= "i"; // i = integer
}

// 4. Eksekusi Query menggunakan Prepared Statement (Keamanan)
$stmt = $c->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$array = array();
if ($result->num_rows > 0) {
    while ($obj = $result->fetch_object()) {
        $array[] = $obj;
    }
}

// 5. Output JSON
header('Content-Type: application/json');
echo json_encode($array);

$stmt->close();
$c->close();
?>
