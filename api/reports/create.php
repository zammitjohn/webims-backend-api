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
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->ticketNo = $_POST['ticketNo'];
$item->name = $_POST['name'];
$item->reportNo = $_POST['reportNo'];
$item->requestedBy = $_POST['requestedBy'];
$item->faultySN = $_POST['faultySN'];
$item->replacementSN = $_POST['replacementSN'];
$item->dateRequested = $_POST['dateRequested'];
$item->dateLeavingRBS = $_POST['dateLeavingRBS'];
$item->dateDispatched = $_POST['dateDispatched'];
$item->dateReturned = $_POST['dateReturned'];
$item->AWB = $_POST['AWB'];
$item->AWBreturn = $_POST['AWBreturn'];
$item->RMA = $_POST['RMA'];
$item->notes = $_POST['notes'];

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    $user->action_isCreate = true;
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validKey()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the item
if($item->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $item->id,
        "inventoryId" => $item->inventoryId,
        "ticketNo" => $item->ticketNo,
        "name" => $item->name,
		"reportNo" => $item->reportNo,
        "requestedBy" => $item->requestedBy,
        "faultySN" => $item->faultySN,
        "replacementSN" => $item->replacementSN,
        "dateRequested" => $item->dateRequested,
        "dateLeavingRBS" => $item->dateLeavingRBS,
        "dateDispatched" => $item->dateDispatched,
        "dateReturned" => $item->dateReturned,
        "AWB" => $item->AWB,
        "AWBreturn" => $item->AWBreturn,
        "RMA" => $item->RMA,
        "notes" => $item->notes
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
print_r(json_encode($output_arr));
