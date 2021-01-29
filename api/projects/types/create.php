<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/projects_types.php';
include_once '../../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare projects type proprty object
$property = new Projects_Types($db);
 
// set projects type property values
$property->name = $_POST['name'];
$property->userId = $_POST['userId'];

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isCreate = true;
    $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'};
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the project type
if($property->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $property->id,
        "name" => $property->name
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));
