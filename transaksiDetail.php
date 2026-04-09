<?php
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    // 1. Ambil data dari POST
    $idTransaksi    = (int) ($_POST['id_transaksi'] ?? 0);
    $idProduk       = (int) ($_POST['id_produk'] ?? 0);
    $jumlah         = (int) ($_POST['jumlah'] ?? 0);
    $total          = (int) ($_POST['total'] ?? 0);
    $jenisTransaksi = $_POST['jenis_transaksi'] ?? ''; // 'Penjualan' atau 'Pembelian'

    if ($idTransaksi == 0 || $idProduk == 0 || empty($jenisTransaksi)) {
        echo json_encode(["result" => "ERROR", "message" => "Data tidak lengkap"]);
        exit;
    }

    // 2. Mulai Database Transaction (Agar data sinkron)
    $c->begin_transaction();

    try {
        // A. Insert ke tabel transaksi_detail
        $stmt1 = $c->prepare("INSERT INTO transaksi_detail(id_transaksi, id_produk, jumlah_produk, total_harga) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("iiii", $idTransaksi, $idProduk, $jumlah, $total);
        
        if (!$stmt1->execute()) {
            throw new Exception("Gagal menyimpan detail transaksi.");
        }

        // B. Logika Update Stok berdasarkan Jenis Transaksi
        if ($jenisTransaksi == "Penjualan") {
            // Jika Penjualan, stok dikurangi (-)
            $sqlStok = "UPDATE produk SET jumlah_stok = jumlah_stok - ? WHERE id_produk = ?";
        } else if ($jenisTransaksi == "Pembelian") {
            // Jika Pembelian, stok ditambah (+)
            $sqlStok = "UPDATE produk SET jumlah_stok = jumlah_stok + ? WHERE id_produk = ?";
        } else {
            throw new Exception("Jenis transaksi tidak dikenal.");
        }

        $stmt2 = $c->prepare($sqlStok);
        $stmt2->bind_param("ii", $jumlah, $idProduk);

        if (!$stmt2->execute()) {
            throw new Exception("Gagal memperbarui stok produk.");
        }

        // C. Jika semua perintah berhasil, simpan permanen
        $c->commit();

        echo json_encode([
            "result" => "OK",
            "message" => "Berhasil! Stok telah diperbarui otomatis ($jenisTransaksi)."
        ]);

    } catch (Exception $e) {
        // D. Jika ada error, batalkan semua (Data tidak akan masuk ke DB)
        $c->rollback();
        
        echo json_encode([
            "result" => "ERROR",
            "message" => "Gagal: " . $e->getMessage()
        ]);
    }

    $c->close();
?>
