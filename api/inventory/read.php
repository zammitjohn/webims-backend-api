<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new inventory($db);

if (isset($_GET['warehouse_categoryId'])) {
    $item->warehouse_categoryId = $_GET['warehouse_categoryId'];
}
if (isset($_GET['warehouseId'])) {
    $item->warehouseId = $_GET['warehouseId'];
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
 
// query inventory item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // inventory item array
        $output_arr=array();
        $output_arr["inventory"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $inventory_item=array(
                "id" => $id,
                "SKU" => $SKU,
                "warehouse_categoryId" => $warehouse_categoryId,
                "warehouse_category_name" => $warehouse_category_name,
                "warehouse_category_importName" => $warehouse_category_importName,
                "warehouseId" => $warehouseId,
                "warehouse_name" => $warehouse_name,
                "description" => $description,
                "qty" => $qty,
                "qtyIn" => $qtyIn,
                "qtyOut" => $qtyOut,
                "supplier" => $supplier,
                "importDate" => $importDate,
                "qty_project_item_allocated" => $qty_project_item_allocated
                
            );
            array_push($output_arr["inventory"], $inventory_item);
        }
    
        echo json_encode($output_arr["inventory"]);
    }
    else{
        echo json_encode(array());
    }
}