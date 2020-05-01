<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);
 
// set pools item property values
$item->id = $_POST['id'];
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
 
// create the pools item
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