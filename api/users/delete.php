<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new users($db);
 
// set users property values
$user->id = $_POST['id'];

// API Key - sessionId
$user->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();
 
// remove the user
if($user->delete()){
    $user_arr=array(
        "status" => true,
        "message" => "Successfully deleted!"
    );
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Failed to delete!"
    );
}
print_r(json_encode($user_arr));