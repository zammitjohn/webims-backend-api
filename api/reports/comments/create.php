<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/reports_comments.php';
include_once '../../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare reports comment property object
$property = new Reports_Comments($db);
 
// set object property values
$property->reportId = $_POST['reportId'];
$property->userId = $_POST['userId'];
$property->text = $_POST['text'];

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    $user->action_isCreate = true;
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create
if($property->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $property->id,
        "reportId" => $property->reportId,
        "userId" => $property->userId,
        "text" => $property->text
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));