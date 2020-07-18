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
 
// set spares item property values
$item->id = $_POST['id'];
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
 
// create the spares item
if($item->update()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully updated!"
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Nothing to update!"
    );
}
print_r(json_encode($output_arr));