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
$user->password = $_POST['password'];
$user->password_new = $_POST['password_new'];
 
// create the user
if($user->update_password()){
    $user_arr=array(
        "status" => true,
        "message" => "Update successful!"
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