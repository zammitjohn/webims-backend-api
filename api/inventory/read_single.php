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

// set ID property of inventory item to be edited
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// read the details of inventory item to be edited
$stmt = $item->read_single();

if ($stmt != false){
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $output_arr=array(
            "id" => $row['id'],
            "SKU" => $row['SKU'],
            "type" => $row['type'],
            "category" => $row['category'],
            "description" => $row['description'],
            "qty" => $row['qty'],
            "qtyIn" => $row['qtyIn'],
            "qtyOut" => $row['qtyOut'],
            "supplier" => $row['supplier'],
            "isGSM" => $row['isGSM'],
            "isUMTS" => $row['isUMTS'],
            "isLTE" => $row['isLTE'],
            "ancillary" => $row['ancillary'],
            "toCheck" => $row['toCheck'],
            "notes" => $row['notes'],
            "inventoryDate" => $row['inventoryDate'],
            "lastChange" => $row['lastChange']
        );
    }
    // make it json format
    print_r(json_encode($output_arr));
}