<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$id = (int) $_POST['id_jenis_produk'];
	$nama = (string) $_POST['nama_jenis_produk'];
	$jenis = (string) $_POST['jenis_proses'];

	if($_POST['nama_jenis_produk'])
	{
		if($jenis == "ubah")
		{
			$sql = "UPDATE jenis_produk SET nama_jenis_produk = '". $nama ."' WHERE id_jenis_produk = ". $id;
		}
		else if($jenis == "tambah")
		{
			$sql = "INSERT INTO jenis_produk(nama_jenis_produk) VALUES ('".$nama."')";
		}
		else if($jenis == "hapus")
		{
			$sql = "UPDATE jenis_produk SET jenis_produk_hapus = 1 WHERE id_jenis_produk = ". $id;
		}

		$result = $c->query($sql);
		echo json_encode($result);
	}
	else
	{
		echo json_encode("Nama Jenis Barang Tidak Boleh Kosong");
	}
?>
