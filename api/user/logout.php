<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new user($db);
 
// AUTH check

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// remove the user
if($user->logout()){
    $output_arr=array(
        "status" => true,
        "message" => "Log out successful!"
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Log out failed!"
    );
}
print_r(json_encode($output_arr));