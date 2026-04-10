<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'connectDb.php';

// 1. Ambil keyword dari parameter GET (contoh: list_merek.php?keyword=honda)
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// 2. Buat Query Dasar
// Jika ada keyword, tambahkan kondisi AND nama_merek LIKE ...
if (!empty($keyword)) {
    $sql = "SELECT * FROM merek WHERE merek_hapus=0 AND nama_merek LIKE ? ORDER BY nama_merek ASC";
    $stmt = $c->prepare($sql);
    
    // Tambahkan wildcard % untuk pencarian fleksibel
    $searchParam = "%$keyword%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Jika tidak ada keyword, tampilkan semua seperti biasa
    $sql = "SELECT * FROM merek WHERE merek_hapus=0 ORDER BY nama_merek ASC";
    $result = $c->query($sql);
}

$array = array();

if ($result && $result->num_rows > 0) {
    while ($obj = $result->fetch_object()) {
        $array[] = $obj;
    }
    echo json_encode($array);
} else {
    // Mengembalikan array kosong [] lebih baik daripada string "No data found" 
    // agar frontend tidak error saat melakukan looping
    echo json_encode([]);
    die();
}
?>
