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

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}
 
// create the pools item
if($item->update()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully updated!"
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Nothing to update!"
    );
}
print_r(json_encode($output_arr));