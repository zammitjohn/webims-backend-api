<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();
 
// query inventory item
$stmt = $item->read();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // inventory item array
        $item_arr=array();
        $item_arr["Inventory"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $inventory_item=array(
                "id" => $id,
                "SKU" => $SKU,
                "description" => $description,
                "qty" => $qty,
                "isGSM" => $isGSM,
                "isUMTS" => $isUMTS,
                "isLTE" => $isLTE,
                "ancillary" => $ancillary,
                "toCheck" => $toCheck,
                "notes" => $notes
            );
            array_push($item_arr["Inventory"], $inventory_item);
        }
    
        echo json_encode($item_arr["Inventory"]);
    }
    else{
        echo json_encode(array());
    }
}