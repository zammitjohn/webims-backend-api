<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/spares.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare spares item object
$item = new Spares($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->type = $_POST['type'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
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
