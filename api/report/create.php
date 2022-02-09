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
 
// prepare report item object
$item = new report($db);
 
// set item property values
$item->inventoryId = $bodyData['inventoryId'];
$item->ticketNumber = $bodyData['ticketNumber'];
$item->name = $bodyData['name'];
$item->description = $bodyData['description'];
$item->reportNumber = $bodyData['reportNumber'];
$item->assignee_userId = $bodyData['assignee_userId'];
$item->faulty_registryId = $bodyData['faulty_registryId'];
$item->replacement_registryId = $bodyData['replacement_registryId'];
$item->dateRequested = $bodyData['dateRequested'];
$item->dateLeaving = $bodyData['dateLeaving'];
$item->dateDispatched = $bodyData['dateDispatched'];
$item->dateReturned = $bodyData['dateReturned'];
$item->AWB = $bodyData['AWB'];
$item->AWBreturn = $bodyData['AWBreturn'];
$item->RMA = $bodyData['RMA'];

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
