<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/spares.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare spares item object
$item = new Spares($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->type = $_POST['type'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->notes = $_POST['notes'];

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// create the item
if($item->create()){
    $item_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "inventoryId" => $item->inventoryId,
        "type" => $item->type,
        "name" => $item->name,
        "description" => $item->description,
		"qty" => $item->qty,
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
