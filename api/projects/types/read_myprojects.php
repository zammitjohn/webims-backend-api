<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/projects_types.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare projects type property object
$property = new Projects_Types($db);

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); 
    $property->userId = $user->getUserId();
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $property->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query projects type property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // projects type property array
        $output_arr=array();
        $output_arr["Projects_Types"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $projects_type_property=array(
                "id" => $id,
                "name" => $name
            );
            array_push($output_arr["Projects_Types"], $projects_type_property);
        }
    
        echo json_encode($output_arr["Projects_Types"]);
    }
    else{
        echo json_encode(array());
    }
}