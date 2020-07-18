<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/reports.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare reports item object
$item = new Reports($db);
 
// set reports item property values
$item->id = $_POST['id'];

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}
 
// remove the reports item
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
