<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/report.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare report item object
$item = new report($db);
 
// set item property values
$item->inventoryId = $_POST['inventoryId'];
$item->ticketNumber = $_POST['ticketNumber'];
$item->name = $_POST['name'];
$item->description = $_POST['description'];
$item->reportNumber = $_POST['reportNumber'];
$item->assignee_userId = $_POST['assignee_userId'];
$item->faulty_registryId = $_POST['faulty_registryId'];
$item->replacement_registryId = $_POST['replacement_registryId'];
$item->dateRequested = $_POST['dateRequested'];
$item->dateLeaving = $_POST['dateLeaving'];
$item->dateDispatched = $_POST['dateDispatched'];
$item->dateReturned = $_POST['dateReturned'];
$item->AWB = $_POST['AWB'];
$item->AWBreturn = $_POST['AWBreturn'];
$item->RMA = $_POST['RMA'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

if (($item->replacement_registryId AND $item->faulty_registryId) AND ($item->replacement_registryId == $item->faulty_registryId)){
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
            "ticketNumber" => $item->ticketNumber,
            "name" => $item->name,
            "description" => $item->description,
            "reportNumber" => $item->reportNumber,
            "assignee_userId" => $item->assignee_userId,
            "faulty_registryId" => $item->faulty_registryId,
            "replacement_registryId" => $item->replacement_registryId,
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
