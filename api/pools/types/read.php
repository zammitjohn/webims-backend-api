<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/pools_types.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools type property object
$property = new Pools_Types($db);

// set type property of pools type property to be shown 
if (isset($_GET['id'])) {
    $property->id = $_GET['id'];
}

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query pools type property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // pools type property array
        $output_arr=array();
        $output_arr["Pools_Types"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $pools_type_property=array(
                "id" => $id,
                "name" => $name,
                "qty" => $qty
            );
            array_push($output_arr["Pools_Types"], $pools_type_property);
        }
    
        echo json_encode($output_arr["Pools_Types"]);
    }
    else{
        echo json_encode(array());
    }
}