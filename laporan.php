<?php 
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    // Ambil data dari POST (Gunakan null coalescing agar tidak error)
    $tanggalawal = $_POST['tanggalawal'] ?? "2025-01-01";
    $tanggalakhir = $_POST['tanggalakhir'] ?? "2027-12-31";

    // 1. Ambil Penjualan
    $sql = "SELECT IFNULL(SUM(Total_transaksi),0) AS total_transaksi, 
                   COUNT(id_transaksi) as jumlah_transaksi 
            FROM transaksi 
            WHERE jenis_transaksi = 'Penjualan' 
            AND tanggal_transaksi BETWEEN ? AND ?";
            
    $stmt1 = $c->prepare($sql);
    $stmt1->bind_param("ss", $tanggalawal, $tanggalakhir);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    $dataPenjualan = $res1->fetch_object();

    // 2. Ambil Pembelian
    $sql2 = "SELECT IFNULL(SUM(Total_transaksi),0) AS total_transaksi, 
                    COUNT(id_transaksi) as jumlah_transaksi 
             FROM transaksi 
             WHERE jenis_transaksi = 'Pembelian' 
             AND tanggal_transaksi BETWEEN ? AND ?";
             
    $stmt2 = $c->prepare($sql2);
    $stmt2->bind_param("ss", $tanggalawal, $tanggalakhir);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $dataPembelian = $res2->fetch_object();

    // Kirim respon dengan struktur yang SELALU SAMA
    echo json_encode(array(
        'status' => 'OK',
        'penjualan' => $dataPenjualan,
        'pembelian' => $dataPembelian
    ));

    $stmt1->close();
    $stmt2->close();
    $c->close();
?>
