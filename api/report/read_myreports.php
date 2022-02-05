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

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->assignee_userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query report item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
 
        // report item array
        $output_arr=array();
        $output_arr["report"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            // different output depending on the params passed
            if (isset($isClosed)){
                $report_item=array(
                    "id" => $id,
                    "inventoryId" => $inventoryId,
                    "ticketNumber" => $ticketNumber,
                    "name" => $name,
                    "description" => $description,
                    "reportNumber" => $reportNumber,
                    "assignee_userId" => $assignee_userId,
                    "faulty_registryId" => $faulty_registryId,
                    "replacement_registryId" => $replacement_registryId,
                    "dateRequested" => $dateRequested,
                    "dateLeaving" => $dateLeaving,
                    "dateDispatched" => $dateDispatched,
                    "dateReturned" => $dateReturned,
                    "AWB" => $AWB,
                    "AWBreturn" => $AWBreturn,
                    "RMA" => $RMA,
                    "isClosed" => $isClosed,
                    "isRepairable" => $isRepairable
                );

            } else {
                $report_item=array(
                    "id" => $id,
                    "inventoryId" => $inventoryId,
                    "ticketNumber" => $ticketNumber,
                    "name" => $name,
                    "description" => $description,
                    "reportNumber" => $reportNumber,
                    "assignee_userId" => $assignee_userId,
                    "faulty_registryId" => $faulty_registryId,
                    "replacement_registryId" => $replacement_registryId,
                    "dateRequested" => $dateRequested,
                    "dateLeaving" => $dateLeaving,
                    "dateDispatched" => $dateDispatched,
                    "dateReturned" => $dateReturned,
                    "AWB" => $AWB,
                    "AWBreturn" => $AWBreturn,
                    "RMA" => $RMA,
                    "isRepairable" => $isRepairable
                );
            }
            array_push($output_arr["report"], $report_item);
        }
    
        echo json_encode($output_arr["report"]);
    }
    else{
        echo json_encode(array());
    }
}
