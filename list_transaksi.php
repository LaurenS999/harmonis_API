<?php
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    // 1. Tangkap data tanggal dari POST (Jika tidak ada, set kosong)
    $tglAwal  = $_POST['tanggalawal'] ?? '';
    $tglAkhir = $_POST['tanggalakhir'] ?? '';

    // 2. Tentukan Query Dasar
    if (!empty($tglAwal) && !empty($tglAkhir)) {
        // Jika ada filter tanggal
        $sql = "SELECT * FROM transaksi 
                WHERE transaksi_hapus = 0 
                AND tanggal_transaksi BETWEEN ? AND ? 
                ORDER BY tanggal_transaksi DESC";
        
        $stmt = $c->prepare($sql);
        $stmt->bind_param("ss", $tglAwal, $tglAkhir);
    } else {
        // Jika tidak ada filter (tampilkan semua yang tidak dihapus)
        $sql = "SELECT * FROM transaksi 
                WHERE transaksi_hapus = 0 
                ORDER BY tanggal_transaksi DESC";
        
        $stmt = $c->prepare($sql);
    }

    // 3. Eksekusi Query
    $stmt->execute();
    $result = $stmt->get_result();
    $transaksi = array();

    if ($result && $result->num_rows > 0) {
        while ($obj = $result->fetch_object()) {
            $transaksi[] = $obj;
        }
    }

    // 4. Kirim Output JSON (Gunakan variabel $transaksi, bukan $array)
    echo json_encode($transaksi);

    $stmt->close();
    $c->close();
?>
