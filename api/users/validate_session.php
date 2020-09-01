<?php
// include database and object files
include_once '../config/database.php';
include_once '../tables/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare users object
$user = new Users($db);

// check if key was specified
if (!isset($_SERVER['HTTP_AUTH_KEY']) or $_SERVER['HTTP_AUTH_KEY'] == "null"){
    $output_arr=array(
        "valid" => false,
        "message" => "You need to login to access RIMS!"
    );
} else { // check if key is correct

    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];

    if($user->validKey()){
        $output_arr=array(
            "valid" => true,
            "message" => "Session Valid!"
        );
    }
    else{
        $output_arr=array(
            "valid" => false,
            "message" => "Session Timed Out!"
        );
    }
}
print_r(json_encode($output_arr));