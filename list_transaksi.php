<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';
	
	$sql = "SELECT * FROM transaksi where transaksi_hapus = 0";
	$result = $c->query($sql);
	$transaksi = array();
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_object()) {
			$transaksi[] = $obj;
		}
	}
	echo json_encode($array);
	
	$c->close();
?>
