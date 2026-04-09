<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Gagal Menghubungkan Ke Database'));
		die();
	}

	$id = (int) $_POST['id_merek'];
	$nama = (string) $_POST['nama_merek'];
	$jenis = (string) $_POST['jenis_proses'];

	if($_POST['nama_merek'])
	{
		if($jenis == "ubah")
		{
			$sql = "UPDATE merek SET nama_merek = '". $nama ."' WHERE id_merek = ". $id;
		}
		else if($jenis == "tambah")
		{
			$sql = "INSERT INTO merek(nama_merek) VALUES ('".$nama."')";
		}
		else if($jenis == "hapus")
		{
			$sql = "UPDATE merek SET merek_hapus = 1 WHERE id_merek = ". $id;
		}

		$result = $c->query($sql);
		echo json_encode("Berhasil");
	}
	else
	{
		echo json_encode("Nama Merek Tidak Boleh Kosong");
	}
?>
