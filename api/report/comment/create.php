<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/report_comment.php';
include_once '../../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare report comment property object
$property = new report_comment($db);
 
// set object property values
$property->reportId = $_POST['reportId'];
$property->text = htmlspecialchars($_POST['text']);

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $property->userId = $user->getUserId();
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