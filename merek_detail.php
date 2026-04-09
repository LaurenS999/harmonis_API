<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Gagal Menghubungkan Ke Database'));
		die();
	}

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
