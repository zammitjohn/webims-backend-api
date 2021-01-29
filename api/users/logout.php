<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new users($db);
 
// AUTH check 
if (isset($_COOKIE['UserSession'])){
    $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'};
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// remove the user
if($user->logout()){

    // empty cookie value and old timestamp
    if (isset($_COOKIE['UserSession'])) {
        unset($_COOKIE['UserSession']);
        setcookie('UserSession', '', time() - 3600, '/');
    }

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