<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry item object
$item = new Registry($db);
 
// set registry item property values
$item->id = $_POST['id'];

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();
 
// remove the registry item
if($item->delete()){
    $item_arr=array(
        "status" => true,
        "message" => "Successfully deleted!"
    );
}
else{
    $item_arr=array(
        "status" => false,
        "message" => "Failed to delete!"
    );
}
print_r(json_encode($item_arr));