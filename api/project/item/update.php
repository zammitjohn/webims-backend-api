<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/project_item.php';
include_once '../../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project_item item object
$item = new project_item($db);
 
// set project_item item property values
$item->id = $bodyData['id'];
$item->inventoryId = $bodyData['inventoryId'];
$item->projectId = $bodyData['projectId'];
$item->description = $bodyData['description'];
$item->qty = $bodyData['qty'];
$item->notes = $bodyData['notes'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// create the project_item item
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