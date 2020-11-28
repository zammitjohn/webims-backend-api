<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/collections.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare collections item object
$item = new Collections($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->type = $_POST['type'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->notes = $_POST['notes'];
$item->userId = $_POST['userId'];

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    $user->action_isCreate = true;
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
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
        "type" => $item->type,
        "name" => $item->name,
        "description" => $item->description,
		"qty" => $item->qty,
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
