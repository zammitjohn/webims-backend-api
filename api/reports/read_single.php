<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/reports.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare reports item object
$item = new Reports($db);

// set ID property of reports item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// read the details of reports item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $item_arr=array(
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
    print_r(json_encode($item_arr));
}