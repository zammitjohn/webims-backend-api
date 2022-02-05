<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/project_item.php';
include_once '../../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project_item item object
$item = new project_item($db);
 
// set project_item item property values
$item->id = $_POST['id'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isDelete = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// remove the project_item item
if($item->delete()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully deleted!"
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to delete!"
    );
}
print_r(json_encode($output_arr));