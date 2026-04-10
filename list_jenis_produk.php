<?php
    error_reporting(E_ERROR | E_PARSE);
    require_once 'connectDb.php';

    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

    $array = array();

    if (!empty($keyword)) {
        $sql = "SELECT * FROM jenis_produk 
                WHERE jenis_produk_hapus = 0 
                AND nama_jenis_produk LIKE ? 
                ORDER BY nama_jenis_produk ASC";
        
        $stmt = $c->prepare($sql);
        $searchParam = "%$keyword%";
        $stmt->bind_param("s", $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM jenis_produk 
                WHERE jenis_produk_hapus = 0 
                ORDER BY nama_jenis_produk ASC";
        $result = $c->query($sql);
    }

    if ($result && $result->num_rows > 0) {
        while ($obj = $result->fetch_object()) {
            $array[] = $obj;
        }
    }

    echo json_encode($array);
    
    $c->close();
?>
