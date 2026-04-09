<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';
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
			echo json_encode(array('result'=> 'ERROR', 'message' => 'No data found'));
			die();
		}
?>
