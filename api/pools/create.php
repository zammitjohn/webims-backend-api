<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->tech = $_POST['tech'];
$item->pool = $_POST['pool'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->qtyOrdered = $_POST['qtyOrdered'];
$item->qtyStock = $_POST['qtyStock'];
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
        "tech" => $item->tech,
        "pool" => $item->pool,
        "name" => $item->name,
        "description" => $item->description,
        "qtyOrdered" => $item->qtyOrdered,
        "qtyStock" => $item->qtyStock,
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
