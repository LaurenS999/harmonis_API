<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'connectDb.php';

// 1. Tangkap parameter dari POST (sesuaikan dengan Volley getParams)
// Jika tidak ada data, default diset ke '0' atau string kosong
$search   = isset($_POST['nama']) ? $_POST['nama'] : '';
$id_jenis = isset($_POST['id_jenis_produk']) ? $_POST['id_jenis_produk'] : '0';
$id_merek = isset($_POST['id_merek']) ? $_POST['id_merek'] : '0';

// 2. Query Dasar
$sql = "SELECT p.id_produk, p.nama_produk, p.harga_beli_produk, p.harga_jual_produk, 
               p.jumlah_stok, p.deskripsi_lain_produk, p.foto_produk, 
               p.id_jenis_produk, jp.nama_jenis_produk, 
               p.id_merek, m.nama_merek 
        FROM produk p 
        INNER JOIN merek m ON p.id_merek = m.id_merek 
        INNER JOIN jenis_produk jp ON p.id_jenis_produk = jp.id_jenis_produk 
        WHERE p.produk_hapus = 0";

// 3. Tambahkan filter secara dinamis
$params = [];
$types = "";

// Filter Nama Produk: Aktif jika tidak kosong
if (!empty($search)) {
    $sql .= " AND p.nama_produk LIKE ?";
    $params[] = "%$search%";
    $types .= "s"; 
}

// Filter Jenis Produk: Aktif jika bukan '0' dan tidak kosong
if ($id_jenis != '0' && !empty($id_jenis)) {
    $sql .= " AND p.id_jenis_produk = ?";
    $params[] = $id_jenis;
    $types .= "i"; 
}

// Filter Merek: Aktif jika bukan '0' dan tidak kosong
if ($id_merek != '0' && !empty($id_merek)) {
    $sql .= " AND p.id_merek = ?";
    $params[] = $id_merek;
    $types .= "i"; 
}

// 4. Eksekusi Query menggunakan Prepared Statement
$stmt = $c->prepare($sql);

if (!empty($params)) {
    // Bind parameter secara dinamis
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
