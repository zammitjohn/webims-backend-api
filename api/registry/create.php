<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry item object
$item = new Registry($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->serialNumber = $_POST['serialNumber'];
$item->datePurchased = $_POST['datePurchased'];

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication
    $user->action_isCreate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $item->userId = $user->getUserId();
}
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
if($item->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "inventoryId" => $item->inventoryId,
        "serialNumber" => $item->serialNumber,
		"datePurchased" => $item->datePurchased,
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));