<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/projects.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare projects item object
$item = new Projects($db);
 
// set projects item property values
$item->id = $_POST['id'];
$item->inventoryId = $_POST['inventoryId'];
$item->type = $_POST['type'];
$item->description = $_POST['description'];
$item->qty = $_POST['qty'];
$item->notes = $_POST['notes'];

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isUpdate = true;
    $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'};
    $item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// create the projects item
if($item->update()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully updated!"
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to update!"
    );
}
print_r(json_encode($output_arr));