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

// set ID property of reports item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}

// read the details of reports item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $output_arr=array(
            "id" => $row['id'],
            "inventoryId" => $row['inventoryId'],
            "ticketNo" => $row['ticketNo'],
            "name" => $row['name'],
            "reportNo" => $row['reportNo'],
            "requestedBy" => $row['requestedBy'],
            "faultySN" => $row['faultySN'],
            "replacementSN" => $row['replacementSN'],
            "dateRequested" => $row['dateRequested'],
            "dateLeavingRBS" => $row['dateLeavingRBS'],
            "dateDispatched" => $row['dateDispatched'],
            "dateReturned" => $row['dateReturned'],
            "AWB" => $row['AWB'],
            "AWBreturn" => $row['AWBreturn'],
            "RMA" => $row['RMA'],
            "notes" => $row['notes']
        );
    }
    // make it json format
    print_r(json_encode($output_arr));
}