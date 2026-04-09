<?php
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$total_transaksi = (string) $_POST['total_transaksi'];
	$total_pembayaran = (string) $_POST['total_pembayaran'];
	$total_kembalian = (string) $_POST['total_kembalian'];
	$nama_pelanggan = (string) $_POST['nama_pelanggan'];
	$alamat_pelanggan = (string) $_POST['alamat_pelanggan'];
	$tanggal_transasksi = (string) $_POST['tanggal_transasksi'];
	$jenis_transaksi = (string) $_POST['jenis_transaksi'];
	$no_telpon = (string) $_POST['no_telpon'];

	if($total_transaksi == "" || $total_pembayaran == "" || $total_kembalian == "" || $nama_pelanggan == "" || 
		$tanggal_transasksi == "" || $jenis_transaksi == "" || $no_telpon == "" || $alamat_pelanggan == "" )
	{
		$arr = array("result" => "Gagal", 
			"message" => "Data tidak boleh kosong");
		echo json_encode($arr);
	}
	else
	{
		$sql = "INSERT INTO transaksi(total_transaksi, total_pembayaran, kembalian, nama_pelanggan, alamat_pelanggan, tanggal_transaksi, transaksi_hapus, jenis_transaksi, no_telpon) VALUES ('". $total_transaksi ."','". $total_pembayaran ."','". $total_kembalian."','". $nama_pelanggan ."','". $alamat_pelanggan ."','". $tanggal_transasksi ."','0','". $jenis_transaksi ."','". $no_telpon ."')";
		error_log($sql);
		$result = $c->query($sql);
		$newID = $c->insert_id;
		$arr = array("result" => "OK", 
			"sql"	=> $sql,
			"id_transaksi" => $newID,
			"message" => $Jenis ." Data Berhasil");
		echo json_encode($arr);
	}
?>
