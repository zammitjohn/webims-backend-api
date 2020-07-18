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

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}

// create the item
if($item->create(false)){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "SKU" => $item->SKU,
        "type" => $item->type,
        "description" => $item->description,
		"qty" => $item->qty,
        "qtyIn" => $item->qtyIn,
        "qtyOut" => $item->qtyOut,
        "supplier" => $item->supplier,        
        "isGSM" => $item->isGSM,
        "isUMTS" => $item->isUMTS,
        "isLTE" => $item->isLTE,
        "ancillary" => $item->ancillary,
        "toCheck" => $item->toCheck,
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
