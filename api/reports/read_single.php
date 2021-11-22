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

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
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
            "description" => $row['description'],
            "reportNo" => $row['reportNo'],
            "asigneeUserId" => $row['asigneeUserId'],
            "faultySN" => $row['faultySN'],
            "replacementSN" => $row['replacementSN'],
            "dateRequested" => $row['dateRequested'],
            "dateLeaving" => $row['dateLeaving'],
            "dateDispatched" => $row['dateDispatched'],
            "dateReturned" => $row['dateReturned'],
            "AWB" => $row['AWB'],
            "AWBreturn" => $row['AWBreturn'],
            "RMA" => $row['RMA'],
            "isClosed" => $row['isClosed'],
            "isRepairable" => $row['isRepairable']
        );
    }
    // make it json format
    print_r(json_encode($output_arr));
}