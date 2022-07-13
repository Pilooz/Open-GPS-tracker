<?php
// require_once '../lib/lib.auth.php';
require_once './config/config.php';
require_once '../lib/lib.db.php';

$action = (isset($_GET['action']) &&  $_GET['action'] !== "") ? $_GET['action'] : "gps";

switch ($action) {
    // Tracking position UI
    case 'gps':
        include_once('gps.php');
        break;

    // Viewing UI
    case 'view':
        include_once('viewer.php');
        break;
    
    // Tracking position API
    case 'track':
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        $result = db_save($data);
        header("Content-Type: application/json");
        echo $result;
        break;
    
    // Viewing API with gpx file caching
    case 'gpx':
        $file = db_generate_gpx($_GET['runnerid']);
        header("Content-Type: application/json");
        echo $file;
        break;

    // GPX Traces Listing API
    case 'tracklist':
        $res = db_get_track_list();
        header("Content-Type: application/json");
        echo json_encode($res);
        break;

    // CRUD API
    case 'delete':
        $res = db_delete_track($_GET['runnerid']);
        // header('Location: index.php?action=view');
        header("Content-Type: application/json");
        echo $res;        
        break;
    
    default:
        header('Location: index.php?action=gps');
        break;
}