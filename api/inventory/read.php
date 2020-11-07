<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);

// set type property of inventory item type to be shown 
if (isset($_GET['type'])) {
    $item->type = $_GET['type'];
}
if (isset($_GET['category'])) {
    $item->category = $_GET['category'];
}

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
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
        $output_arr["Inventory"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $inventory_item=array(
                "id" => $id,
                "SKU" => $SKU,
                "type_id" => $type_id,
                "type_name" => $type_name,
                "type_altname" => $type_altname,
                "category_id" => $category_id,
                "category_name" => $category_name,
                "description" => $description,
                "qty" => $qty,
                "qtyIn" => $qtyIn,
                "qtyOut" => $qtyOut,
                "supplier" => $supplier,
                "inventoryDate" => $inventoryDate
            );
            array_push($output_arr["Inventory"], $inventory_item);
        }
    
        echo json_encode($output_arr["Inventory"]);
    }
    else{
        echo json_encode(array());
    }
}