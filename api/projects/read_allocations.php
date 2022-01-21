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
 
// set inventoryId property of projects type property to be shown 
if (isset($_GET['inventoryId'])) {
    $item->inventoryId = $_GET['inventoryId'];
}

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication 
	$user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); 
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query projects item
$stmt = $item->read_allocations();
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
                "inventoryId" => $inventoryId,
                "type_id" => $type_id,
                "type_name" => $type_name,
                "total_qty" => $total_qty
            );
            array_push($output_arr["Projects"], $projects_item);
        }
    
        echo json_encode($output_arr["Projects"]);
    }
    else{
        echo json_encode(array());
    }
}