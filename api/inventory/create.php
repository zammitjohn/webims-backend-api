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
 
$inventory = new inventory($db); // prepare inventory object
 
// set object values
$inventory->SKU = $bodyData['SKU'];
$inventory->warehouse_categoryId = $bodyData['warehouse_categoryId'];
$inventory->tag = $bodyData['tag'];
$inventory->description = $bodyData['description'];
$inventory->qty = $bodyData['qty'];
$inventory->qtyIn = $bodyData['qtyIn'];
$inventory->qtyOut = $bodyData['qtyOut'];
$inventory->supplier = $bodyData['supplier'];
$inventory->notes = $bodyData['notes'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $inventory->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the item
if($inventory->create(false)){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $inventory->id,
        "SKU" => $inventory->SKU,
        "warehouse_categoryId" => $inventory->warehouse_categoryId,
        "tag" => preg_replace('/[^a-zA-Z0-9_ -]/s',' ', $inventory->tag), // don't accept special characters for tag
        "description" => $inventory->description,
		"qty" => $inventory->qty,
        "qtyIn" => $inventory->qtyIn,
        "qtyOut" => $inventory->qtyOut,
        "supplier" => $inventory->supplier,
        "notes" => $inventory->notes	
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
echo json_encode($output_arr);
