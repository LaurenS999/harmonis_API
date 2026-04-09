<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Gagal Menghubungkan Ke Database'));
		die();
	}
	$sql = "SELECT * FROM jenis_produk WHERE jenis_produk_hapus = 0 ORDER BY nama_jenis_produk ASC";
	$result = $c->query($sql);
	$array = array();
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_object()) {
			$array[] = $obj;
		}
			echo json_encode($array);
		}
		else {
			echo json_encode('No data found');
			die();
		}
?>
