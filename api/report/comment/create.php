<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/report_comment.php';
include_once '../../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare report comment property object
$report_comment = new report_comment($db);
 
// set object property values
$report_comment->reportId = $bodyData['reportId'];
$report_comment->text = htmlspecialchars($bodyData['text']);

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $report_comment->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create
if($report_comment->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $report_comment->id,
        "reportId" => $report_comment->reportId,
        "text" => $report_comment->text
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));