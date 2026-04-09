<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';
	$sql = "SELECT p.id_produk, p.nama_produk, p.harga_beli_produk, p.harga_jual_produk, p.jumlah_stok, p.deskripsi_lain_produk, p.foto_produk, p.id_jenis_produk, jp.nama_jenis_produk, jp.jenis_produk_hapus, p.id_merek, m.nama_merek, m.merek_hapus, p.produk_hapus FROM produk p INNER JOIN merek m ON p.id_merek=m.id_merek inner join jenis_produk jp ON p.id_jenis_produk=jp.id_jenis_produk WHERE produk_hapus=0";
	$result = $c->query($sql);
	$array = array();
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_object()) {
			$array[] = $obj;
		}
			echo json_encode($array);
		}
		else {
			echo json_encode(array('result'=> 'ERROR', 'message' => 'No data found'));
			die();
		}
?>
