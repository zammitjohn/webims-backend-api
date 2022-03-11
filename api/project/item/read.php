<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/project_item.php';
include_once '../../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project_item object
$project_item = new project_item($db);
 
// set type property of project_item type property to be shown 
if (isset($_GET['projectId'])) {
    $project_item->projectId = $_GET['projectId'];
}

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query project_item
$stmt = $project_item->read();
if ($stmt->rowCount()) {
    // project_item array
    $output_arr=array();
    $output_arr["project_item"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $project_item_item=array(
            "id" => $id,
            "inventoryId" => $inventoryId,
            "projectId" => $projectId,
            "project_name" => $project_name,
            "inventory_SKU" => $inventory_SKU,
            "description" => $description,
            "qty" => $qty,
            "notes" => $notes,
            "user_fullName" => $user_fullName
        );
        array_push($output_arr["project_item"], $project_item_item);
    }
    echo json_encode($output_arr["project_item"]);

} else {
    echo json_encode(array());
}