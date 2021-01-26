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

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    $user->action_isDelete = true;
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// remove the projects item
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