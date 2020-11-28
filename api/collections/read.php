<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/collections.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare collections item object
$item = new Collections($db);
 
// set type/inventoryId property of collections type property to be shown 
if (isset($_GET['type'])) {
    $item->type = $_GET['type'];
}
if (isset($_GET['inventoryId'])) {
    $item->inventoryId = $_GET['inventoryId'];
}

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query collections item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // sapres item array
        $output_arr=array();
        $output_arr["Collections"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $collections_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "type_id" => $type_id,
                "type_name" => $type_name,
                "name" => $name,
                "description" => $description,
                "qty" => $qty,
                "notes" => $notes,
                "firstname" => $firstname,
                "lastname" => $lastname
            );
            array_push($output_arr["Collections"], $collections_item);
        }
    
        echo json_encode($output_arr["Collections"]);
    }
    else{
        echo json_encode(array());
    }
}