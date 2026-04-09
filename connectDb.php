<?php
	$host = getenv('MYSQLHOST');
	$user = getenv('MYSQLUSER');
	$pass = getenv('MYSQLPASSWORD');
	$db   = getenv('MYSQLDATABASE');
	$port = getenv('MYSQLPORT');

	$c = new mysqli($host, $user, $pass, $db, $port);

	if($c->connect_errno) {
	    header('Content-Type: application/json');
	    echo json_encode(array(
	        'result'=> 'ERROR', 
	        'message' => 'Failed to connect DB: ' . $c->connect_error
	    ));
    exit; // Berhenti jika koneksi gagal
}

	$c->set_charset("utf8");
?>