<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/projects.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare projects item object
$item = new Projects($db);
 
// set type property of projects type property to be shown 
if (isset($_GET['type'])) {
    $item->type = $_GET['type'];
}

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query projects item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // sapres item array
        $output_arr=array();
        $output_arr["Projects"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $projects_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "type_id" => $type_id,
                "type_name" => $type_name,
                "inventory_SKU" => $inventory_SKU,
                "inventory_category" => $inventory_category,
                "description" => $description,
                "qty" => $qty,
                "notes" => $notes,
                "user_fullname" => $user_fullname
            );
            array_push($output_arr["Projects"], $projects_item);
        }
    
        echo json_encode($output_arr["Projects"]);
    }
    else{
        echo json_encode(array());
    }
}