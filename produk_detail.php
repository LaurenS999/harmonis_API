<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$id = (string) $_GET['id_produk'];
	$sql = "SELECT * FROM produk p INNER JOIN merek m ON p.id_merek=m.id_merek inner join jenis_produk jp ON p.id_jenis_produk=jp.id_jenis_produk Where id_produk =". $id;
	$result = $c->query($sql);
	$array = array();
	if ($result->num_rows > 0) {
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