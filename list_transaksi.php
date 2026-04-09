<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Failed to connect DB'));
		die();
	}
	
	$sql = "SELECT * FROM transaksi where transaksi_hapus = 0";
	$result = $c->query($sql);
	$transaksi = array();
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_object()) {
			$transaksi[] = $obj;

	}
		echo json_encode($transaksi);
	}
	else {
		echo json_encode('No data found');
		die();
	}
?>
