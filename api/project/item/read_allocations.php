<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/project_item.php';
include_once '../../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project_item item object
$item = new project_item($db);
 
// set inventoryId property of project_item type property to be shown 
if (isset($_GET['inventoryId'])) {
    $item->inventoryId = $_GET['inventoryId'];
}

// AUTH check
$user = new user($db); // prepare user object
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

// query project_item item
$stmt = $item->read_allocations();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // sapres item array
        $output_arr=array();
        $output_arr["project_item"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $project_item_item=array(
                "inventoryId" => $inventoryId,
                "projectId" => $projectId,
                "project_name" => $project_name,
                "total_qty" => $total_qty
            );
            array_push($output_arr["project_item"], $project_item_item);
        }
    
        echo json_encode($output_arr["project_item"]);
    }
    else{
        echo json_encode(array());
    }
}