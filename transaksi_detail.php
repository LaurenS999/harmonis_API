<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'connectDb.php';

// Gunakan isset untuk mencegah error jika parameter tidak dikirim
$id = isset($_POST['id_transaksi']) ? $_POST['id_transaksi'] : '';

// 1. Beri alias yang jelas pada id_transaksi agar tidak tertukar dengan tabel detail
$sql = "SELECT t.id_transaksi as id_t, t.*, td.*, p.* 
        FROM transaksi t 
        LEFT JOIN transaksi_detail td ON t.id_transaksi = td.id_transaksi 
        LEFT JOIN produk p ON p.id_produk = td.id_produk 
        WHERE t.id_transaksi = ?";

$stmt = $c->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

$transaksi = array();
$keranjang = array();
$produk = array();
$i = 0;

if ($result->num_rows > 0) {
    while ($obj = $result->fetch_assoc()) {
        // 2. Casting ke (int) agar JSON mengirimkan angka, bukan string
        // Gunakan 'id_t' (alias dari transaksi utama) bukan 'id_transaksi'
        $transaksi[0]['id_transaksi'] = (int)$obj['id_t'];
        $transaksi[0]['total_transaksi'] = (int)$obj['total_transaksi'];
        $transaksi[0]['total_pembayaran'] = (int)$obj['total_pembayaran'];
        $transaksi[0]['kembalian'] = (int)$obj['kembalian'];
        $transaksi[0]['nama_pelanggan'] = $obj['nama_pelanggan'];
        $transaksi[0]['alamat_pelanggan'] = $obj['alamat_pelanggan'];
        $transaksi[0]['tanggal_transaksi'] = $obj['tanggal_transaksi'];
        $transaksi[0]['transaksi_hapus'] = (int)$obj['transaksi_hapus'];
        $transaksi[0]['jenis_transaksi'] = $obj['jenis_transaksi'];
        $transaksi[0]['no_telpon'] = $obj['no_telpon'];

        // Cek jika ada detail produk (mencegah error jika detail kosong)
        if ($obj['id_produk'] != null) {
            $keranjang[$i]['jumlah_produk'] = (int)$obj['jumlah_produk'];
            $keranjang[$i]['total_harga'] = (int)$obj['total_harga'];

            $produk[$i]['id_produk'] = (int)$obj['id_produk'];
            $produk[$i]['nama_produk'] = $obj['nama_produk'];
            $produk[$i]['harga_beli_produk'] = (int)$obj['harga_beli_produk'];
            $produk[$i]['harga_jual_produk'] = (int)$obj['harga_jual_produk'];
            $produk[$i]['deskripsi_lain_produk'] = $obj['deskripsi_lain_produk'];
            $produk[$i]['jumlah_stok'] = (int)$obj['jumlah_stok'];
            $produk[$i]['foto_produk'] = $obj['foto_produk'];
            $i++;
        }
    }
    echo json_encode(array('result' => 'OK', 'data' => $transaksi, 'data2' => $keranjang, 'data3' => $produk));
} else {
    echo json_encode(array('result' => 'ERROR', 'message' => 'No data found'));
}
?>
