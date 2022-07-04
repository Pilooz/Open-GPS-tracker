<?php

function db_connect() {
	global $pdo;
	// Doing connexion with IP instead of localhost.
	// https://stackoverflow.com/questions/29695450/pdoexception-sqlstatehy000-2002-no-such-file-or-directory
	return new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";port=".DB_PORT, DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}

function db_version() {
	$pdo = db_connect();
	$query = $pdo->query('SHOW VARIABLES like "version"');
	$row = $query->fetch();
	echo 'MySQL version:' . $row['Value'];
}

function db_save() {
	$pdo = db_connect();

	$runnerId = $_GET['file'];
	$lat = $_POST['lat'];
	$lon = $_POST['lon'];
	$time = $_POST['time'];

	$qry="INSERT INTO tracks SET runnerid=':runnerId', lat=':lat', lon=':lon', time=':time'";
	$pdo->prepare($qry, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$pdo->execute(array(':runnerId' => $runnerId, ':lat' => $lat, ':lon' => $lon, ':time' => $time));
}
