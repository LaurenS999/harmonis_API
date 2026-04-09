<?php 
	error_reporting(E_ERROR | E_PARSE);
	$c = new mysqli("localhost", "root", "", "harmonis");
	if($c->connect_errno) {
		echo json_encode(array('result'=> 'ERROR', 'message' => 'Failed to connect DB'));
		die();
	}

	$tanggalawal = (string)$_POST['tanggalawal'];
	$tanggalakhir = (string)$_POST['tanggalakhir'];
	
	$tanggalawal = "2025-10-01";
	$tanggalakhir = "2025-10-30";

	//Dapatin List transaksi penjualan
	$sql = "SELECT p.*, SUM(td.jumlah_produk) as 'jumlah_produk', SUM(td.total_harga) as 'subTotal' 
			FROM transaksi t inner join transaksi_detail td on t.id_transaksi=td.id_transaksi inner join produk p on p.id_produk=td.id_produk 
			WHERE t.jenis_transaksi ='Penjualan' AND t.tanggal_transaksi BETWEEN '". $tanggalawal ."' AND '". $tanggalakhir ."' 
			GROUP by p.id_produk;";
	$result = $c->query($sql);
	$LaporanMenuPenjualan = array();
	$LaporanMenuPembelian = array();
	if ($result->num_rows > 0) {
		while ($obj = $result -> fetch_object()) {
			$LaporanMenuPenjualan[] = $obj;
		}		
	}

	// //Dapatin List transaksi ppembelian
	$sql2 = "SELECT p.*, SUM(td.jumlah_produk) as 'jumlah_produk', SUM(td.total_harga) as 'subTotal' 
			FROM transaksi t inner join transaksi_detail td on t.id_transaksi=td.id_transaksi inner join produk p on p.id_produk=td.id_produk 
			WHERE t.jenis_transaksi ='Pembelian' AND t.tanggal_transaksi BETWEEN '". $tanggalawal ."' AND '". $tanggalakhir ."' 
			GROUP by p.id_produk;";
	$result2 = $c->query($sql2);
	if ($result2->num_rows > 0) {
		while($obj2 = $result2 ->fetch_object()) {
			$LaporanMenuPembelian[] = $obj2;
		}
	}

	if(count($LaporanMenuPenjualan) == 0 && count($LaporanMenuPembelian) == 0)
	{
		echo json_encode(array('result' => 'ERROR', 'Message' => "Data not Found"));	
	}
	else
	{
		echo json_encode(array('result' => 'OK', 'data' => $LaporanMenuPenjualan, 'data2' => $LaporanMenuPembelian));	
	}

?>
