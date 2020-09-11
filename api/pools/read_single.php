<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);

// set ID property of pools item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validKey()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// read the details of pools item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $output_arr=array(
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
    print_r(json_encode($output_arr));
}