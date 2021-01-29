<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/inventory_categories.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory category property object
$property = new Inventory_Categories($db);

// set type property of inventory category property to be shown 
if (isset($_GET['id'])) {
    $property->id = $_GET['id'];
}

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query inventory category property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // inventory category property array
        $output_arr=array();
        $output_arr["Inventory_Categories"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $inventory_category_property=array(
                "id" => $id,
                "name" => $name,
                "supportImport" => $supportImport
            );
            array_push($output_arr["Inventory_Categories"], $inventory_category_property);
        }
    
        echo json_encode($output_arr["Inventory_Categories"]);
    }
    else{
        echo json_encode(array());
    }
}