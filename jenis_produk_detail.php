<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';


	$id = (string)$_GET['id_jenis_produk'];
	$sql = "SELECT * FROM jenis_produk Where id_jenis_produk =". $id;
	
	$result = $c->query($sql);
	$array = array();
	if ($result->num_rows > 0) 
	{
		while ($obj = $result -> fetch_object()) {
			$array[] = $obj;
		}
		echo json_encode($array[0]);
	}
	else {
		echo json_encode('No data found');
		die();
	}
?>
