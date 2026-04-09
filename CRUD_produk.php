<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$id = (int) $_POST['id_produk'];
	$nama = (string) $_POST['nama_produk'];
	$harga_beli = (string) $_POST['harga_beli'];
	$harga_jual = (string) $_POST['harga_jual'];
	$stok = (string) $_POST['stok'];
	$deskripsi_lain = (string) $_POST['deskripsi_lain'];
	$id_jenis_produk = (string) $_POST['id_jenis_produk'];
	$id_merek = (string) $_POST['id_merek'];
	$jenis = (string) $_POST['jenis_proses'];

	if($_POST['nama_produk'])
	{
		if($jenis == "ubah")
		{
			$sql = "UPDATE produk SET nama_produk='". $nama . "',harga_beli_produk=". $harga_beli .",harga_jual_produk=". $harga_jual. ",jumlah_stok=". $stok .",deskripsi_lain_produk='". $deskripsi_lain . "',id_jenis_produk=". $id_jenis_produk .",id_merek=". $id_merek ." WHERE id_produk=" . $id;
		}
		else if($jenis == "tambah")
		{
			$sql = "INSERT INTO produk(nama_produk, harga_beli_produk, harga_jual_produk, jumlah_stok, deskripsi_lain_produk, id_jenis_produk, id_merek, produk_hapus) VALUES ('". $nama ."',". $harga_beli .",". $harga_jual .",". $stok .",'". $deskripsi_lain ."',". $id_jenis_produk .",". $id_merek. ",0)";
		}
		else if($jenis == "hapus")
		{
			$sql = "UPDATE produk SET produk_hapus = 1 WHERE id_produk = ". $id;
		}
		error_log($sql);

		$result = $c->query($sql);
		echo json_encode($result);
	}
	else
	{
		echo json_encode("Data Tidak Boleh Kosong");
	}
?>
