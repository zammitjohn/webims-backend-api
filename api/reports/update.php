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

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}

// create the reports item
if($item->update()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully updated!"
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Nothing to update!"
    );
}
print_r(json_encode($output_arr));