<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/report.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare report object
$report = new report($db);
 
// set report object values
$report->id = $bodyData['id'];
$report->inventoryId = $bodyData['inventoryId'];
$report->ticketNumber = $bodyData['ticketNumber'];
$report->name = $bodyData['name'];
$report->description = $bodyData['description'];
$report->reportNumber = $bodyData['reportNumber'];
$report->assignee_userId = $bodyData['assignee_userId'];
$report->faulty_registryId = $bodyData['faulty_registryId'];
$report->replacement_registryId = $bodyData['replacement_registryId'];
$report->dateRequested = $bodyData['dateRequested'];
$report->dateLeaving = $bodyData['dateLeaving'];
$report->dateDispatched = $bodyData['dateDispatched'];
$report->dateReturned = $bodyData['dateReturned'];
$report->AWB = $bodyData['AWB'];
$report->AWBreturn = $bodyData['AWBreturn'];
$report->RMA = $bodyData['RMA'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $report->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

if (($report->replacement_registryId AND $report->faulty_registryId) AND ($report->replacement_registryId == $report->faulty_registryId)){
    $output_arr=array(
        "status" => false,
        "message" => "You cannot use the same serial number twice!"
    );

} else {
    // update the report
    if($report->update()){
        $output_arr=array(
            "status" => true,
            "message" => "Successfully updated!"
        );
    }
    else{
        $output_arr=array(
            "status" => false,
            "message" => "Failed to update!"
        );
    }
}
echo json_encode($output_arr);