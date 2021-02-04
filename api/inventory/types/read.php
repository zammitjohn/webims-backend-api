<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/inventory_types.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory type property object
$property = new Inventory_Types($db);

// set type property of inventory type property to be shown 
if (isset($_GET['id'])) {
    $property->id = $_GET['id'];
}
if (isset($_GET['category'])) {
    $property->category = $_GET['category'];
}

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query inventory type property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // inventory type property array
        $output_arr=array();
        $output_arr["Inventory_Types"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $inventory_type_property=array(
                "id" => $id,
                "name" => $name,
                "import_name" => $import_name,
                "type_category" => $type_category,
                "category_name" => $category_name
            );
            array_push($output_arr["Inventory_Types"], $inventory_type_property);
        }
    
        echo json_encode($output_arr["Inventory_Types"]);
    }
    else{
        echo json_encode(array());
    }
}