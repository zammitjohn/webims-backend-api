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

// set ID property of projects item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// read the details of projects item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $output_arr=array(
            "id" => $row['id'],
            "inventoryId" => $row['inventoryId'],
            "type" => $row['type'],
            "description" => $row['description'],
            "qty" => $row['qty'],
            "notes" => $row['notes']
        );
    }
    // make it json format
    print_r(json_encode($output_arr));
}