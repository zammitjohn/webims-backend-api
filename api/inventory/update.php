<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);
 
// set inventory item property values
$item->id = $_POST['id'];
$item->SKU = $_POST['SKU'];
$item->type = $_POST['type'];
$item->category = $_POST['category'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->qtyIn = $_POST['qtyIn'];
$item->qtyOut = $_POST['qtyOut'];
$item->supplier = $_POST['supplier'];
$item->isGSM = $_POST['isGSM'];
$item->isUMTS = $_POST['isUMTS'];
$item->isLTE = $_POST['isLTE'];
$item->ancillary = $_POST['ancillary'];
$item->toCheck = $_POST['toCheck'];
$item->notes = $_POST['notes'];

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isUpdate = true;
    $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'};
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