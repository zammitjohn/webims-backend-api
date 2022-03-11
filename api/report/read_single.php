<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/report.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare report object
$report = new report($db);

// set ID property of report to be edited
$report->id = isset($_GET['id']) ? $_GET['id'] : die();

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// read the details of report to be edited
$stmt = $report->read_single();

if ($stmt->rowCount()) {
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
    // make it json format
    echo json_encode($output_arr);

} else {
    header("HTTP/1.0 404 Not Found");
}