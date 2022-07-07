<?php
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {        
		header( 'location: /index.php' );
    }

function db_connect() {
	// Doing connexion with IP instead of localhost.
	// https://stackoverflow.com/questions/29695450/pdoexception-sqlstatehy000-2002-no-such-file-or-directory
	return new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";port=".DB_PORT, DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false ]);
}

function db_save($data) {
	try {
		$pdo = db_connect();
		$statment = $pdo->prepare("INSERT INTO tracks (runnerid, lat, lon, time)  VALUES (:runnerId, :lat, :lon, :time)", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$statment->bindValue(':runnerId', $_GET['runnerId']);
		$statment->bindValue(':lat', $data->lat);
		$statment->bindValue(':lon', $data->lon);
		$statment->bindValue(':time', $data->time);
		$statment->execute();
		return'{ "message": "OK" }';
	} catch(PDOException $e) {
		return '{ "message": "' . str_replace('"', '\"', $e->getMessage()) . '" }';
	}
}

function db_select($qry){
	$pdo = db_connect();
	$statment = $pdo->prepare($qry);
	$statment->execute();
	$data = $statment->fetchAll();
	if (!$data) {
		return '{ "message": "' . str_replace('"', '\"', $e->getMessage()) . '" }';
	} else {
		return $data;
	}
}

function db_get_track_by_name($trackname) {
	return db_select("select t.lat, t.lon, t.time from tracks t where runnerid = '$trackname' order by pointid asc");
}

function db_get_last_position($trackname) {
	$lastid = db_select("select max(pointid) as lastid from tracks")[0]['lastid'];
	return db_select("select t.lat, t.lon, t.time, pointid as lastid from tracks t where runnerid = '$trackname' and pointid = '$lastid' limit 1");
}
