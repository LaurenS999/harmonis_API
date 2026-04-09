<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Gagal Menghubungkan Ke Database'));
		die();
	}

	$idTransaksi = (int) $_POST['id_transaksi'];
	$idProduk = (int) $_POST['id_produk'];
	$Jumlah = (int) $_POST['jumlah'];
	$total = (int) $_POST['total'];
	
	$sql = "INSERT INTO transaksi_detail(id_transaksi, id_produk, jumlah_produk, total_harga) VALUES (". $idTransaksi .",".$idProduk .",". $Jumlah.",". $total .")";
		
	$result = $c->query($sql);
	$arr = array("result" => "OK", 
			"sql"	=> $sql,
			"message" => $Jenis ." Data Berhasil");
		echo json_encode($arr);
?>
