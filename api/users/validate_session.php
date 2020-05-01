<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare users object
$user = new Users($db);

// API Key - sessionId
$user->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// query users
$stmt = $user->keyExists();

if($stmt){
    $user_arr=array(
        "valid" => true,
        "message" => "Session Valid!"
    );
}
else{
    $user_arr=array(
        "valid" => false,
        "message" => "Session Timed Out!"
    );
}

print_r(json_encode($user_arr));