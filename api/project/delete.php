<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/project.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project object
$property = new project($db);
 
// set project property values
$property->id = $bodyData['id'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isDelete = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $property->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// remove the project
if($property->delete()){
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