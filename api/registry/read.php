<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry item object
$item = new registry($db);
 
// inventoryId of registry item to list
$item->inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : die();

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query registry item
$stmt = $item->read();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // registry item array
        $output_arr=array();
        $output_arr["registry"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $registry_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "serialNumber" => $serialNumber,
                "datePurchased" => $datePurchased,
                "state" => $state
            );
            array_push($output_arr["registry"], $registry_item);
        }
    
        echo json_encode($output_arr["registry"]);
    }
    else{
        echo json_encode(array());
    }
}