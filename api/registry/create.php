<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry item object
$item = new Registry($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->serialNumber = $_POST['serialNumber'];
$item->datePurchased = $_POST['datePurchased'];

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// create the item
if($item->create()){
    $item_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "inventoryId" => $item->inventoryId,
        "serialNumber" => $item->serialNumber,
		"datePurchased" => $item->datePurchased,
    );
}
else{
    $item_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($item_arr));