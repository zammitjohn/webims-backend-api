<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory object
$inventory = new inventory($db);

// set ID property of inventory item to be edited
$inventory->id = isset($_GET['id']) ? $_GET['id'] : die();

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// read the details of inventory item to be edited
$stmt = $inventory->read_single();

if ($stmt->rowCount()) {
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    $output_arr=array(
        "id" => $row['id'],
        "SKU" => $row['SKU'],
        "warehouse_categoryId" => $row['warehouse_categoryId'],
        "warehouseId" => $row['warehouseId'],
        "description" => $row['description'],
        "qty" => $row['qty'],
        "qtyIn" => $row['qtyIn'],
        "qtyOut" => $row['qtyOut'],
        "supplier" => $row['supplier'],
        "notes" => $row['notes'],
        "importDate" => $row['importDate'],
        "lastChange" => $row['lastChange']
    );
    // make it json format
    echo json_encode($output_arr);
} else {
    header("HTTP/1.0 404 Not Found");
}