<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$id = (string) $_GET['id_merek'];
	$sql = "SELECT * FROM merek Where id_merek  =". $id;
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
