<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory object
$inventory = new inventory($db);

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query inventory
$stmt = $inventory->read_tags();
if ($stmt->rowCount()) {
    // tag item array
    $output_arr=array();
    $output_arr["tag"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $tag_item=array(
            "name" => $name,            
        );
        array_push($output_arr["tag"], $tag_item);
    }
    echo json_encode($output_arr["tag"]);
} else {
    echo json_encode(array());
}