<?php
// include database and object files
include_once '../config/database.php';
include_once '../tables/pools.php';
include_once '../tables/users.php';
 
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

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validKey()){
    header("HTTP/1.1 401 Unauthorized");
    die();
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
        "message" => "Failed to update!"
    );
}
print_r(json_encode($output_arr));