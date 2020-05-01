<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);

// set ID property of pools item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// read the details of pools item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $item_arr=array(
            "id" => $row['id'],
            "inventoryId" => $row['inventoryId'],
            "tech" => $row['tech'],
            "pool" => $row['pool'],
            "name" => $row['name'],
            "description" => $row['description'],
            "qtyOrdered" => $row['qtyOrdered'],
            "qtyStock" => $row['qtyStock'],
            "notes" => $row['notes']
        );
    }
    // make it json format
    print_r(json_encode($item_arr));
}