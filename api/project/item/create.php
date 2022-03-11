<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/project_item.php';
include_once '../../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project_item object
$project_item = new project_item($db);
 
// set object values
$project_item->inventoryId = $bodyData['inventoryId'];
$project_item->projectId = $bodyData['projectId'];
$project_item->description = $bodyData['description'];
$project_item->qty = $bodyData['qty'];
$project_item->notes = $bodyData['notes'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isCreate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $project_item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// create the item
if($project_item->create()){
    $output_arr=array(
        "status" => true,
        "message" => "Successfully created!",
        "id" => $project_item->id,
        "inventoryId" => $project_item->inventoryId,
        "projectId" => $project_item->projectId,
        "description" => $project_item->description,
		"qty" => $project_item->qty,
        "notes" => $project_item->notes	
    );
}
else{
    $output_arr=array(
        "status" => false,
        "message" => "Failed to create!"
    );
}
echo json_encode($output_arr);
