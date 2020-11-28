<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/collections_types.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare collections type property object
$property = new Collections_Types($db);

// set id/userId property of collections type property to be shown 
if (isset($_GET['id'])) {
    $property->id = $_GET['id'];
}
if (isset($_GET['userId'])) {
    $property->userId = $_GET['userId'];
}

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query collections type property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // collections type property array
        $output_arr=array();
        $output_arr["Collections_Types"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $collections_type_property=array(
                "id" => $id,
                "name" => $name
            );
            array_push($output_arr["Collections_Types"], $collections_type_property);
        }
    
        echo json_encode($output_arr["Collections_Types"]);
    }
    else{
        echo json_encode(array());
    }
}