<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/project.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project proprty object
$project = new project($db);
 
// set project property values
$project->name = htmlspecialchars($bodyData['name']);

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $project->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the project type
if($project->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $project->id,
        "name" => $project->name
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));
