<?php 
	error_reporting(E_ERROR | E_PARSE);
	require_once 'connectDb.php';

	$tanggalawal = (string)$_POST['tanggalawal'];
	$tanggalakhir = (string)$_POST['tanggalakhir'];

	$tanggalawal = "2025-10-01";
	$tanggalakhir = "2025-10-30";

	//Dapatin List transaksi penjualan
	$sql = "SELECT IFNULL(SUM(Total_transaksi),0) AS 'total_transaksi', COUNT(id_transaksi) as 'jumlah_transasksi' FROM transaksi WHERE jenis_transaksi = 'Penjualan' AND tanggal_transaksi BETWEEN '". $tanggalawal ."' AND '". $tanggalakhir ."'";
	$result = $c->query($sql);
	$LaporonPenjualan = array();
	$LaporanPembelian = array();
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_object()) {
			$LaporonPenjualan[] = $obj;
		}		
	}
	// //Dapatin List transaksi ppembelian
	$sql2 = "SELECT IFNULL(SUM(Total_transaksi),0) AS 'total_transaksi', COUNT(id_transaksi) as 'jumlah_transasksi' FROM transaksi WHERE jenis_transaksi = 'Pembelian' AND tanggal_transaksi BETWEEN '". $tanggalawal ."' AND '". $tanggalakhir ."'";
	$result2 = $c->query($sql2);
	if ($result2->num_rows > 0) {
		while($obj2 = $result2 ->fetch_object()) {
			$LaporanPembelian[] = $obj2;
		}
	}
	
	if(count($LaporonPenjualan) == 0 && count($LaporanPembelian) == 0)
	{
		echo json_encode(array('result' => 'ERROR', 'sqlPenjualan' => $sql, 'sqlPembelian'=> $sql2 ,'Message' => "Data not Found"));	
	}
	else
	{
		echo json_encode(array('result' => 'OK', 'data' => $LaporonPenjualan, 'data2' => $LaporanPembelian));
	}


?>
