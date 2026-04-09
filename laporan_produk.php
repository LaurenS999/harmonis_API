<?php 
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    // 1. Gunakan input dari Android, berikan default jika kosong
    $tanggalawal = $_POST['tanggalawal'] ?? "2025-01-01";
    $tanggalakhir = $_POST['tanggalakhir'] ?? "2025-12-31";

    // 2. Query Penjualan (Gunakan Prepared Statements agar AMAN dari SQL Injection)
    $sql1 = "SELECT p.*, SUM(td.jumlah_produk) as jumlah_produk, SUM(td.total_harga) as subTotal 
             FROM transaksi t 
             INNER JOIN transaksi_detail td ON t.id_transaksi = td.id_transaksi 
             INNER JOIN produk p ON p.id_produk = td.id_produk 
             WHERE t.jenis_transaksi = 'Penjualan' 
             AND t.tanggal_transaksi BETWEEN ? AND ? 
             GROUP BY p.id_produk";

    $stmt1 = $c->prepare($sql1);
    $stmt1->bind_param("ss", $tanggalawal, $tanggalakhir);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    
    $dataPenjualan = array();
    while ($obj = $res1->fetch_object()) {
        $dataPenjualan[] = $obj;
    }

    // 3. Query Pembelian
    $sql2 = "SELECT p.*, SUM(td.jumlah_produk) as jumlah_produk, SUM(td.total_harga) as subTotal 
             FROM transaksi t 
             INNER JOIN transaksi_detail td ON t.id_transaksi = td.id_transaksi 
             INNER JOIN produk p ON p.id_produk = td.id_produk 
             WHERE t.jenis_transaksi = 'Pembelian' 
             AND t.tanggal_transaksi BETWEEN ? AND ? 
             GROUP BY p.id_produk";

    $stmt2 = $c->prepare($sql2);
    $stmt2->bind_param("ss", $tanggalawal, $tanggalakhir);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    
    $dataPembelian = array();
    while ($obj2 = $res2->fetch_object()) {
        $dataPembelian[] = $obj2;
    }

    // 4. SELALU kirim struktur yang sama (result, data, data2)
    // Jika data kosong, isinya akan menjadi [] bukan hilang. Ini aman buat Kotlin.
    echo json_encode(array(
        'result' => 'OK',
        'data' => $dataPenjualan,
        'data2' => $dataPembelian
    ));

    $stmt1->close();
    $stmt2->close();
    $c->close();
?>
