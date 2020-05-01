<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/reports.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare reports item object
$item = new Reports($db);

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();
 
// query reports item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
 
        // reports item array
        $item_arr=array();
        $item_arr["reports"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $reports_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "ticketNo" => $ticketNo,
                "name" => $name,
                "reportNo" => $reportNo,
                "requestedBy" => $requestedBy,
                "faultySN" => $faultySN,
                "replacementSN" => $replacementSN,
                "dateRequested" => $dateRequested,
                "dateLeavingRBS" => $dateLeavingRBS,
                "dateDispatched" => $dateDispatched,
                "dateReturned" => $dateReturned,
                "AWB" => $AWB,
                "AWBreturn" => $AWBreturn,
                "RMA" => $RMA,
                "notes" => $notes
            );
            array_push($item_arr["reports"], $reports_item);
        }
    
        echo json_encode($item_arr["reports"]);
    }
    else{
        echo json_encode(array());
    }
}
