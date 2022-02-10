<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/warehouse_category.php';
include_once '../../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare warehouse category property object
$warehouse_category = new warehouse_category($db);

// set type property of warehouse category property to be shown 
if (isset($_GET['id'])) {
    $warehouse_category->id = $_GET['id'];
}
if (isset($_GET['warehouseId'])) {
    $warehouse_category->warehouseId = $_GET['warehouseId'];
}

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query warehouse category property
$stmt = $warehouse_category->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // warehouse category property array
        $output_arr=array();
        $output_arr["warehouse_category"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $warehouse_category_property=array(
                "id" => $id,
                "name" => $name,
                "importName" => $importName,
                "warehouseId" => $warehouseId,
                "warehouse_name" => $warehouse_name
            );
            array_push($output_arr["warehouse_category"], $warehouse_category_property);
        }
    
        echo json_encode($output_arr["warehouse_category"]);
    }
    else{
        echo json_encode(array());
    }
}