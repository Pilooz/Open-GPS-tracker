<?php
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {        
		header( 'location: /index.php' );
    }

function _db_connect() {
	// Doing connexion with IP instead of localhost.
	// https://stackoverflow.com/questions/29695450/pdoexception-sqlstatehy000-2002-no-such-file-or-directory
	return new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";port=".DB_PORT, DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false ]);
}

function _db_select($qry){
	try {
		$pdo = _db_connect();
		$statment = $pdo->prepare($qry);
		$statment->execute();
		$data = $statment->fetchAll();
		return $data;
	} catch(PDOException $e) {
		return $e->getMessage();
	}
}

function _db_checksum($trackname) {
	// Checksum of the last position date
	$laspos = db_get_last_position($trackname);
	return sha1($laspos[0]['time']);
}

//
// Shity function to generate valide GPX format XML content
// @TODO : think about using phpGPX ? Is it neccesaary ?
//
function db_generate_gpx($trackname) {
	$sha1 = _db_checksum($trackname);
	// See if we need to generate file ?
	if (file_exists("./gpx/$trackname-$sha1.gpx")) {
		return '{ "file" : "./gpx/' . $trackname . '-' . $sha1 . '.gpx" }';
	}
	// Generate the file
	$points = db_get_track_by_name($trackname);
	$gpx = _shity_generate_gpx_head($trackname);
	foreach ($points as $point) {
		$gpx .= '		<trkpt lat="'.$point['lat'].'" lon="'.$point['lon'].'"><time>'.$point['time'].'</time></trkpt>'."\n";
	}
	$gpx .= _shity_generate_gpx_footer();

	file_put_contents("./gpx/$trackname-$sha1.gpx", $gpx);
	return '{ "file" : "./gpx/' . $trackname . '-' . $sha1 . '.gpx" }';
}

//
// Shity function to generate valide GPX format XML header 
//
function _shity_generate_gpx_head($trackname) {
	return '<?xml version="1.0"?>
<gpx version="1.0" creator="Pilooz-GPS-Tracker"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns="http://www.topografix.com/GPX/1/0"
	xsi:schemaLocation="http://www.topografix.com/GPX/1/0 http://www.topografix.com/GPX/1/0/gpx.xsd">
	<trk>
	<name>'.$trackname.'</name>
	<trkseg>
';
}

//
// Shity function to generate valide GPX format XML footer 
//
function _shity_generate_gpx_footer() {
	return "	</trkseg>
	</trk>
</gpx>";
}

//
//  Saving gpx point
//
function db_save($data) {
	try {
		$pdo = _db_connect();
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

//
// Requesting a whole GPX track by its name.
//
function db_get_track_by_name($trackname) {
	return _db_select("select t.lat, t.lon, t.time from tracks t where runnerid = '$trackname' order by pointid asc");
}

//
// Requesting the last position of a track by its name.
//
function db_get_last_position($trackname) {
	$lastid = _db_select("select max(pointid) as lastid from tracks where runnerid = '$trackname'")[0]['lastid'];
	return _db_select("select * from tracks t where runnerid = '$trackname' and pointid = '$lastid' limit 1");
}

//
// Requesting the liste of all the tracks
//
function db_get_track_list() {
	return _db_select("select distinct runnerid as trackname from tracks;");
}