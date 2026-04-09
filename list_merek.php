<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'koneksi.php';
	
	$sql = "SELECT * FROM merek Where merek_hapus=0 ORDER BY nama_merek ASC";
	
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
