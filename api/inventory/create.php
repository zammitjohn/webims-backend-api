<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new inventory($db);
 
// set item property values
$item->SKU = $bodyData['SKU'];
$item->warehouse_categoryId = $bodyData['warehouse_categoryId'];
$item->description = $bodyData['description'];
$item->qty = $bodyData['qty'];
$item->qtyIn = $bodyData['qtyIn'];
$item->qtyOut = $bodyData['qtyOut'];
$item->supplier = $bodyData['supplier'];
$item->notes = $bodyData['notes'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();
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
        "warehouse_categoryId" => $item->warehouse_categoryId,
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
