<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->type = $_POST['type'];
$item->pool = $_POST['pool'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->qtyOrdered = $_POST['qtyOrdered'];
$item->qtyStock = $_POST['qtyStock'];
$item->notes = $_POST['notes'];

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
        "pool" => $item->pool,
        "name" => $item->name,
        "description" => $item->description,
        "qtyOrdered" => $item->qtyOrdered,
        "qtyStock" => $item->qtyStock,
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
