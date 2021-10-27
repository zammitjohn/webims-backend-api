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
$item->description = $_POST['description'];
$item->reportNo = $_POST['reportNo'];
$item->userId = $_POST['userId'];
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
if (isset($_COOKIE['UserSession'])){
    $user->action_isCreate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
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
    // create the item
    if($item->create()){
        $output_arr=array(
            "status" => true,
            "message" => "Successfully created!",
            "id" => $item->id,
            "inventoryId" => $item->inventoryId,
            "ticketNo" => $item->ticketNo,
            "name" => $item->name,
            "description" => $item->description,
            "reportNo" => $item->reportNo,
            "userId" => $item->userId,
            "faultySN" => $item->faultySN,
            "replacementSN" => $item->replacementSN,
            "dateRequested" => $item->dateRequested,
            "dateLeaving" => $item->dateLeaving,
            "dateDispatched" => $item->dateDispatched,
            "dateReturned" => $item->dateReturned,
            "AWB" => $item->AWB,
            "AWBreturn" => $item->AWBreturn,
            "RMA" => $item->RMA
        );
    }
    else{
        $output_arr=array(
            "status" => false,
            "message" => "Failed to create!"
        );
    }
}
print_r(json_encode($output_arr));
