<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);

// set ID property of inventory item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// read the details of inventory item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $item_arr=array(
            "id" => $row['id'],
            "SKU" => $row['SKU'],
            "type" => $row['type'],
            "description" => $row['description'],
            "qty" => $row['qty'],
            "qtyIn" => $row['qtyIn'],
            "qtyOut" => $row['qtyOut'],
            "supplier" => $row['supplier'],
            "isGSM" => $row['isGSM'],
            "isUMTS" => $row['isUMTS'],
            "isLTE" => $row['isLTE'],
            "ancillary" => $row['ancillary'],
            "toCheck" => $row['toCheck'],
            "notes" => $row['notes'],
            "inventoryDate" => $row['inventoryDate'],
            "lastChange" => $row['lastChange']
        );
    }
    // make it json format
    print_r(json_encode($item_arr));
}