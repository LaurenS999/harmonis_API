<?php
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';
    
    // Gunakan p. agar lebih spesifik jika nanti ada JOIN
    $sql = "SELECT * FROM transaksi WHERE transaksi_hapus = 0 ORDER BY id_transaksi DESC";
    
    $result = $c->query($sql);
    $transaksi = array(); // Inisialisasi variabel transaksi

    if ($result && $result->num_rows > 0) {
        while ($obj = $result->fetch_object()) {
            $transaksi[] = $obj;
        }
    }

    // PERBAIKAN: Gunakan variabel yang benar ($transaksi)
    echo json_encode($transaksi);
    
    $c->close();
?>
