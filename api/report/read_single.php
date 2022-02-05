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

// set ID property of report item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// read the details of report item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $output_arr=array(
            "id" => $row['id'],
            "inventoryId" => $row['inventoryId'],
            "ticketNumber" => $row['ticketNumber'],
            "name" => $row['name'],
            "description" => $row['description'],
            "reportNumber" => $row['reportNumber'],
            "assignee_userId" => $row['assignee_userId'],
            "faulty_registryId" => $row['faulty_registryId'],
            "replacement_registryId" => $row['replacement_registryId'],
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