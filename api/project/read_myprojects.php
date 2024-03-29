<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/project.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project object
$project = new project($db);

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $project->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query project property
$stmt = $project->read();
if ($stmt->rowCount()) {    
    // project property array
    $output_arr=array();
    $output_arr["project"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $project_item_type_property=array(
            "id" => $id,
            "name" => $name
        );
        array_push($output_arr["project"], $project_item_type_property);
    }
    echo json_encode($output_arr["project"]);
    
} else {
    echo json_encode(array());
}