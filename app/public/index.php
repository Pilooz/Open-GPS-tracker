<?php
require_once './config/config.php';
require_once '../lib/lib.db.php';

$action = (isset($_GET['action']) &&  $_GET['action'] !== "") ? $_GET['action'] : "gps";

switch ($action) {
    case 'gps':
        include_once('gps.php');
        break;

    case 'track':
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        $result = db_save($data);
        header("Content-Type: application/json");
        echo $result;
        break;
    
    case 'view':
        $res = db_get_track_by_name("pilooz");
        $res2 = db_get_last_position("pilooz");
        include_once('viewer.php');
        break;

    default:
    # code...
    break;
}