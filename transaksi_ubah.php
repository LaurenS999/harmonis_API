<?php
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Gagal Menghubungkan Ke Database'));
		die();
	}

	$id_transaksi = (string) $_POST['id_transaksi'];
	$total_transaksi = (string) $_POST['total_transaksi'];
	$total_pembayaran = (string) $_POST['total_pembayaran'];
	$total_kembalian = (string) $_POST['total_kembalian'];
	$nama_pelanggan = (string) $_POST['nama_pelanggan'];
	$alamat_pelanggan = (string) $_POST['alamat_pelanggan'];
	$tanggal_transaksi = (string) $_POST['tanggal_transaksi'];
	$jenis_transaksi = (string) $_POST['jenis_transaksi'];
	$no_telpon = (string) $_POST['no_telpon'];

	if($total_transaksi == "" || $total_pembayaran == "" || $total_kembalian == "" || $nama_pelanggan == "" || 
		$tanggal_transaksi == "" || $jenis_transaksi == "" || $no_telpon == "" || $alamat_pelanggan == "" || $id_transaksi == "")
	{
		$arr = array("result" => "Gagal", 
			"message" => "Data tidak boleh kosong");
		echo json_encode($arr);
	}
	else
	{
		$sql = "UPDATE transaksi SET total_transaksi='". $total_transaksi ."',total_pembayaran='". $total_pembayaran ."',kembalian='". $total_kembalian ."',nama_pelanggan='". $nama_pelanggan ."',alamat_pelanggan='". $alamat_pelanggan ."',tanggal_transaksi='". $tanggal_transaksi."',jenis_transaksi='". $jenis_transaksi ."',no_telpon='". $no_telpon. "' WHERE id_transaksi = ". $id_transaksi;

		$result = $c->query($sql);

		if ($result->affected_rows > 0) {
			$arr = array("result" => "OK", "sql"	=> $sql,
				"message" => $Jenis ." Data Berhasil");
			echo json_encode($arr);
		}
		else
		{
			$arr = array('result' => "Gagal ubah transaksi", "sql" => $sql );
			echo json_encode($arr);
		}

	}
?>
