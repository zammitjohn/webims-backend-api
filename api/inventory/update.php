<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new inventory($db);
 
// set inventory item property values
$item->id = $_POST['id'];
$item->SKU = $_POST['SKU'];
$item->warehouse_categoryId = $_POST['warehouse_categoryId'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->qtyIn = $_POST['qtyIn'];
$item->qtyOut = $_POST['qtyOut'];
$item->supplier = $_POST['supplier'];
$item->notes = $_POST['notes'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// create the inventory item
if($item->update(false)){
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