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
$item->inventoryId = $_POST['inventoryId'];
$item->ticketNo = $_POST['ticketNo'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->reportNo = $_POST['reportNo'];
$item->assigneeUserId = $_POST['assigneeUserId'];
$item->faultySN = $_POST['faultySN'];
$item->replacementSN = $_POST['replacementSN'];
$item->dateRequested = $_POST['dateRequested'];
$item->dateLeaving = $_POST['dateLeaving'];
$item->dateDispatched = $_POST['dateDispatched'];
$item->dateReturned = $_POST['dateReturned'];
$item->AWB = $_POST['AWB'];
$item->AWBreturn = $_POST['AWBreturn'];
$item->RMA = $_POST['RMA'];

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication
    $user->action_isUpdate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $item->userId = $user->getUserId();
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

if (($item->replacementSN AND $item->faultySN) AND ($item->replacementSN == $item->faultySN)){
    $output_arr=array(
        "status" => false,
        "message" => "You cannot use the same serial number twice!"
    );

} else {
    // update the reports item
    if($item->update()){
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
print_r(json_encode($output_arr));