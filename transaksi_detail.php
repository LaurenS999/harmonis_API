<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$id = (string) $_POST['id_transaksi'];
	$sql = "SELECT * FROM transaksi t left join transaksi_detail td on t.id_transaksi=td.id_transaksi left join produk p on p.id_produk = td.id_produk where t.id_transaksi = ". $id;
	// $sql = "SELECT * FROM transaksi t left join transaksi_detail td on t.idTransaksi=td.id_transaksi left join produk p on p.id_produk = td.id_produk where t.idTransaksi =". $id;
	$result = $c->query($sql);
	$transaksi = array();
	$keranjang = array();
	$produk = array();
	$i = 0;
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_assoc()) {

      		$transaksi[0]['id_transaksi'] = addslashes(htmlentities($obj['id_transaksi']));
      		$transaksi[0]['total_transaksi'] = addslashes(htmlentities($obj['total_transaksi']));
      		$transaksi[0]['total_pembayaran'] = addslashes(htmlentities($obj['total_pembayaran']));
      		$transaksi[0]['kembalian'] = addslashes(htmlentities($obj['kembalian']));
      		$transaksi[0]['nama_pelanggan'] = addslashes(htmlentities($obj['nama_pelanggan']));
      		$transaksi[0]['alamat_pelanggan'] = addslashes(htmlentities($obj['alamat_pelanggan']));
      		$transaksi[0]['tanggal_transaksi'] = addslashes(htmlentities($obj['tanggal_transaksi']));
      		$transaksi[0]['transaksi_hapus'] = addslashes(htmlentities($obj['transaksi_hapus']));
      		$transaksi[0]['jenis_transaksi'] = addslashes(htmlentities($obj['jenis_transaksi']));
      		$transaksi[0]['no_telpon'] = addslashes(htmlentities($obj['no_telpon']));

      		$keranjang[$i]['jumlah_produk'] = addslashes(htmlentities($obj['jumlah_produk']));
			$keranjang[$i]['total_harga'] = addslashes(htmlentities($obj['total_harga']));

      		$produk[$i]['id_produk'] = addslashes(htmlentities($obj['id_produk']));
      		$produk[$i]['nama_produk'] = addslashes(htmlentities($obj['nama_produk']));
      		$produk[$i]['harga_beli_produk'] = addslashes(htmlentities($obj['harga_beli_produk']));
      		$produk[$i]['harga_jual_produk'] = addslashes(htmlentities($obj['harga_jual_produk']));
      		$produk[$i]['deskripsi_lain_produk'] = addslashes(htmlentities($obj['deskripsi_lain_produk']));
      		$produk[$i]['jumlah_stok'] = addslashes(htmlentities($obj['jumlah_stok']));
      		$produk[$i]['foto_produk'] = addslashes(htmlentities($obj['foto_produk']));
      		$produk[$i]['id_jenis_produk'] = addslashes(htmlentities($obj['id_jenis_produk']));
      		$produk[$i]['id_merek'] = addslashes(htmlentities($obj['id_merek']));
			$produk[$i]['produk_hapus'] = addslashes(htmlentities($obj['produk_hapus']));
      		$i++;
		}
			echo json_encode(array('result' => 'OK', 'data' => $transaksi, 'data2' => $keranjang, 'data3' => $produk));
		}
		else {
			echo json_encode(array('result'=> 'ERROR', 'message' => 'No data found'));
			die();
		}
?>
