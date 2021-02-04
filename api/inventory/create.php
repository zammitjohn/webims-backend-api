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
 
// set item property values
$item->SKU = $_POST['SKU'];
$item->type = $_POST['type'];
$item->category = $_POST['category'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->qtyIn = $_POST['qtyIn'];
$item->qtyOut = $_POST['qtyOut'];
$item->supplier = $_POST['supplier'];
$item->notes = $_POST['notes'];

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isCreate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the item
if($item->create(false)){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "SKU" => $item->SKU,
        "type" => $item->type,
        "category" => $item->category,
        "description" => $item->description,
		"qty" => $item->qty,
        "qtyIn" => $item->qtyIn,
        "qtyOut" => $item->qtyOut,
        "supplier" => $item->supplier,
        "notes" => $item->notes	
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));
