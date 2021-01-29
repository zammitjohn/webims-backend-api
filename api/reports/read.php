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

// set type property of inventory item type to be shown 
if (isset($_GET['userId'])) {
    $item->userId = $_GET['userId'];
}

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query reports item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
 
        // reports item array
        $output_arr=array();
        $output_arr["reports"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            // different output depending on the params passed
            if (isset($isClosed)){
                $reports_item=array(
                    "id" => $id,
                    "inventoryId" => $inventoryId,
                    "ticketNo" => $ticketNo,
                    "name" => $name,
                    "description" => $description,
                    "reportNo" => $reportNo,
                    "userId" => $userId,
                    "faultySN" => $faultySN,
                    "replacementSN" => $replacementSN,
                    "dateRequested" => $dateRequested,
                    "dateLeavingRBS" => $dateLeavingRBS,
                    "dateDispatched" => $dateDispatched,
                    "dateReturned" => $dateReturned,
                    "AWB" => $AWB,
                    "AWBreturn" => $AWBreturn,
                    "RMA" => $RMA,
                    "isClosed" => $isClosed,
                    "isRepairable" => $isRepairable
                );

            } else {
                $reports_item=array(
                    "id" => $id,
                    "inventoryId" => $inventoryId,
                    "ticketNo" => $ticketNo,
                    "name" => $name,
                    "description" => $description,
                    "reportNo" => $reportNo,
                    "userId" => $userId,
                    "faultySN" => $faultySN,
                    "replacementSN" => $replacementSN,
                    "dateRequested" => $dateRequested,
                    "dateLeavingRBS" => $dateLeavingRBS,
                    "dateDispatched" => $dateDispatched,
                    "dateReturned" => $dateReturned,
                    "AWB" => $AWB,
                    "AWBreturn" => $AWBreturn,
                    "RMA" => $RMA,
                    "isRepairable" => $isRepairable
                );
            }
            array_push($output_arr["reports"], $reports_item);
        }
    
        echo json_encode($output_arr["reports"]);
    }
    else{
        echo json_encode(array());
    }
}
