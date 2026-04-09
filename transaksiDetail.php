<?php
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    $idTransaksi    = (int) ($_POST['id_transaksi'] ?? 0);
    $idProduk       = (int) ($_POST['id_produk'] ?? 0);
    $jumlah         = (int) ($_POST['jumlah'] ?? 0);
    $total          = (int) ($_POST['total'] ?? 0);
    $jenisTransaksi = $_POST['jenis_transaksi'] ?? ''; 

    if ($idTransaksi == 0 || $idProduk == 0 || empty($jenisTransaksi)) {
        echo json_encode(["result" => "ERROR", "message" => "Data tidak lengkap"]);
        exit;
    }

    $c->begin_transaction();

    try {
        // A. Insert detail
        $stmt1 = $c->prepare("INSERT INTO transaksi_detail(id_transaksi, id_produk, jumlah_produk, total_harga) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("iiii", $idTransaksi, $idProduk, $jumlah, $total);
        error_log($stmt1);
        
        if (!$stmt1->execute()) {
            throw new Exception("Gagal simpan detail: " . $stmt1->error);
        }

        // B. Update stok
        if ($jenisTransaksi == "Penjualan") {
            $sqlStok = "UPDATE produk SET jumlah_stok = jumlah_stok - ? WHERE id_produk = ?";
        } else if ($jenisTransaksi == "Pembelian") {
            $sqlStok = "UPDATE produk SET jumlah_stok = jumlah_stok + ? WHERE id_produk = ?";
        } else {
            throw new Exception("Jenis transaksi tidak dikenal.");
        }

        $stmt2 = $c->prepare($sqlStok);
        $stmt2->bind_param("ii", $jumlah, $idProduk);
        error_log($stmt2);
        if (!$stmt2->execute()) {
            throw new Exception("Gagal update stok: " . $stmt2->error);
        }

        $c->commit();
        error_log("SUCCESS: Transaksi $idTransaksi, Produk $idProduk berhasil.");

        echo json_encode(["result" => "OK", "message" => "Berhasil update stok ($jenisTransaksi)"]);

    } catch (Exception $e) {
        $c->rollback();
        error_log("ERROR DETAIL: " . $e->getMessage()); // Ini akan muncul di Railway
        echo json_encode(["result" => "ERROR", "message" => $e->getMessage()]);
    }
    $c->close();
?>
