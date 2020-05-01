<?php
 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/users.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new Users($db);
 
// set user property values
$user->email = $_POST['email'];
$user->firstname = $_POST['firstname'];
$user->lastname = $_POST['lastname'];

// API Key - sessionId
$user->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();
 
// update the user profile
if($user->update_profile()){
    $user_arr=array(
        "status" => true,
        "message" => "Update successful!",
        "email" => $user->email,
        "firstname" => $user->firstname,
        "lastname" => $user->lastname
    );
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Failed to update!"
    );
}
print_r(json_encode($user_arr));
?>