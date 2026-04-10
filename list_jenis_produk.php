<?php
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    $array = array();

    $sql = "SELECT * FROM jenis_produk 
             WHERE jenis_produk_hapus = 0 
             ORDER BY nama_jenis_produk ASC";
    $result = $c->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($obj = $result->fetch_object()) {
            $array[] = $obj;
        }
    }

    echo json_encode($array);
    
    $c->close();
?>
