<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Gagal Menghubungkan Ke Database'));
		die();
	}

	$id_transaksi = (string) $_POST['id_transaksi'];

	$sql = "UPDATE transaksi SET transaksi_hapus = 1 WHERE id_transaksi = ". $id_transaksi;
	$result = $c->query($sql);
	
	if ($result->affected_rows > 0) {
		$arr = array("result" => "OK", 
		"sql"	=> $sql,
		"message" => $Jenis ." Data Berhasil");
		echo json_encode($arr);
	}
	else
	{
		$arr = array('result' => "Gagal hapus transaksi", "sql" => $sql );
		echo json_encode($arr);
	}

?>
