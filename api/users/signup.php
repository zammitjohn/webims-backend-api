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
$user->firstname = $_POST['firstname'];
$user->lastname = $_POST['lastname'];
$user->created = date('Y-m-d H:i:s');
 
// create the user
if($user->signup()){
    $user_arr=array(
        "status" => true,
        "message" => "Registration successful!",
        "id" => $user->id,
        "email" => $user->email,
        "firstname" => $user->firstname,
        "lastname" => $user->lastname
    );
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Email already exists!"
    );
}
print_r(json_encode($user_arr));
?>