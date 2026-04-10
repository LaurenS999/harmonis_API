<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'connectDb.php';

// 1. Ambil ID dengan aman
$id = isset($_GET['id_produk']) ? $_GET['id_produk'] : '';

if ($id != '') {
    // 2. Gunakan Prepared Statement agar aman
    $sql = "SELECT p.*, m.nama_merek, jp.nama_jenis_produk 
            FROM produk p 
            INNER JOIN merek m ON p.id_merek = m.id_merek 
            INNER JOIN jenis_produk jp ON p.id_jenis_produk = jp.id_jenis_produk 
            WHERE p.id_produk = ?";
            
    $stmt = $c->prepare($sql);
    $stmt->bind_param("i", $id); // "i" berarti integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $obj = $result->fetch_object();
        // Mengembalikan 1 objek produk
        echo json_encode($obj);
    } else {
        // Jika data tidak ditemukan, kirim null atau objek kosong
        echo json_encode(null);
    }
} else {
    // Jika ID tidak dikirim
    echo json_encode(array("error" => "ID tidak ditemukan"));
}

$c->close();
?>
