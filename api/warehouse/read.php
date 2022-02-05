<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/warehouse.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare warehouse property object
$property = new warehouse($db);

// set type property of warehouse property to be shown 
if (isset($_GET['id'])) {
    $property->id = $_GET['id'];
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
 
// query warehouse property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // warehouse property array
        $output_arr=array();
        $output_arr["warehouse"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $warehouse_property=array(
                "id" => $id,
                "name" => $name,
                "supportImport" => $supportImport
            );
            array_push($output_arr["warehouse"], $warehouse_property);
        }
    
        echo json_encode($output_arr["warehouse"]);
    }
    else{
        echo json_encode(array());
    }
}