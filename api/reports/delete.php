<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/reports.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare reports item object
$item = new Reports($db);
 
// set reports item property values
$item->id = $_POST['id'];

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();
 
// remove the reports item
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
