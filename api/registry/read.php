<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry item object
$item = new Registry($db);
 
// inventoryId of registry item to list
$item->inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : die();

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}

// query registry item
$stmt = $item->read();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // registry item array
        $output_arr=array();
        $output_arr["Registry"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $registry_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "serialNumber" => $serialNumber,
                "datePurchased" => $datePurchased,
            );
            array_push($output_arr["Registry"], $registry_item);
        }
    
        echo json_encode($output_arr["Registry"]);
    }
    else{
        echo json_encode(array());
    }
}