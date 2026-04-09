<?php
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ALL); // Ubah sementara ke E_ALL untuk debug
    ini_set('display_errors', 1);
    require_once 'connectDb.php';

    // Log di paling atas untuk memastikan request MASUK
    error_log("--- DEBUG: transaksiDetail.php dipanggil ---");

    $idTransaksi    = (int) ($_POST['id_transaksi'] ?? 0);
    $idProduk       = (int) ($_POST['id_produk'] ?? 0);
    $jumlah         = (int) ($_POST['jumlah'] ?? 0);
    $total          = (int) ($_POST['total'] ?? 0);
    $jenisTransaksi = $_POST['jenis_transaksi'] ?? ''; 

    if ($idTransaksi == 0 || $idProduk == 0) {
        error_log("ERROR: Data tidak lengkap. ID Transaksi: $idTransaksi, Produk: $idProduk");
        echo json_encode(["result" => "ERROR", "message" => "Data tidak lengkap"]);
        exit;
    }

    $c->begin_transaction();

    try {
        // A. Insert detail
        $sql1 = "INSERT INTO transaksi_detail(id_transaksi, id_produk, jumlah_produk, total_harga) VALUES (?, ?, ?, ?)";
        $stmt1 = $c->prepare($sql1);
        
        if (!$stmt1) {
            throw new Exception("Prepare SQL1 gagal: " . $c->error);
        }

        $stmt1->bind_param("iiii", $idTransaksi, $idProduk, $jumlah, $total);
        
        if (!$stmt1->execute()) {
            throw new Exception("Execute SQL1 gagal: " . $stmt1->error);
        }

        // B. Update stok
        if ($jenisTransaksi == "Penjualan") {
            $sqlStok = "UPDATE produk SET jumlah_stok = jumlah_stok - ? WHERE id_produk = ?";
        } else {
            $sqlStok = "UPDATE produk SET jumlah_stok = jumlah_stok + ? WHERE id_produk = ?";
        }

        $stmt2 = $c->prepare($sqlStok);
        if (!$stmt2) {
            throw new Exception("Prepare SQLStok gagal: " . $c->error);
        }

        $stmt2->bind_param("ii", $jumlah, $idProduk);
        if (!$stmt2->execute()) {
            throw new Exception("Execute SQLStok gagal: " . $stmt2->error);
        }

        $c->commit();
        error_log("SUCCESS: Detail Transaksi ID $idTransaksi berhasil.");

        echo json_encode(["result" => "OK", "message" => "Berhasil"]);

    } catch (Exception $e) {
        $c->rollback();
        error_log("FAIL: " . $e->getMessage()); // Ini sekarang AKAN muncul di Railway
        echo json_encode(["result" => "ERROR", "message" => $e->getMessage()]);
    }
    $c->close();
?>
