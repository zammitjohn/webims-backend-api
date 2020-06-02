<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);
 
// set item property values
$item->SKU = $_POST['SKU'];
$item->type = $_POST['type'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->isGSM = $_POST['isGSM'];
$item->isUMTS = $_POST['isUMTS'];
$item->isLTE = $_POST['isLTE'];
$item->ancillary = $_POST['ancillary'];
$item->toCheck = $_POST['toCheck'];
$item->notes = $_POST['notes'];

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// create the item
if($item->create()){
    $item_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "SKU" => $item->SKU,
        "type" => $item->type,
        "description" => $item->description,
		"qty" => $item->qty,
        "isGSM" => $item->isGSM,
        "isUMTS" => $item->isUMTS,
        "isLTE" => $item->isLTE,
        "ancillary" => $item->ancillary,
        "toCheck" => $item->toCheck,
        "notes" => $item->notes	
    );
}
else{
    $item_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($item_arr));
