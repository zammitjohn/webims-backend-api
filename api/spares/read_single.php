<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/spares.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare spares item object
$item = new Spares($db);

// set ID property of spares item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// read the details of spares item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $item_arr=array(
            "id" => $row['id'],
            "inventoryId" => $row['inventoryId'],
            "type" => $row['type'],
            "name" => $row['name'],
            "description" => $row['description'],
            "qty" => $row['qty'],
            "notes" => $row['notes']
        );
    }
    // make it json format
    print_r(json_encode($item_arr));
}