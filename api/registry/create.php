<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry object
$registry = new registry($db);
 
// set object values
$registry->inventoryId = $bodyData['inventoryId'];
$registry->serialNumber = $bodyData['serialNumber'];
$registry->datePurchased = $bodyData['datePurchased'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $registry->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the item
if($registry->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $registry->id,
        "inventoryId" => $registry->inventoryId,
        "serialNumber" => $registry->serialNumber,
		"datePurchased" => $registry->datePurchased,
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));