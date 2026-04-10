<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'connectDb.php';

$sql = "SELECT * FROM merek WHERE merek_hapus=0 ORDER BY nama_merek ASC";
$result = $c->query($sql);

$array = array();

if ($result && $result->num_rows > 0) {
    while ($obj = $result->fetch_object()) {
        $array[] = $obj;
    }
    echo json_encode($array);
} else {
   echo json_encode([]);
    die();
}
?>
