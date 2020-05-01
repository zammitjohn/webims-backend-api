<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);
 
// set inventory item property values
$item->id = $_POST['id'];
$item->SKU = $_POST['SKU'];
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
 
// create the inventory item
if($item->update()){
    $item_arr=array(
        "status" => true,
        "message" => "Successfully updated!"
    );
}
else{
    $item_arr=array(
        "status" => false,
        "message" => "Failed to update!"
    );
}
print_r(json_encode($item_arr));