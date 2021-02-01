<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/projects_types.php';
include_once '../../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare projects item object
$property = new Projects_Types($db);
 
// set projects item property values
$property->id = $_POST['id'];

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isDelete = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $property->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// remove the projects item
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