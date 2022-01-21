<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/projects.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$userId;
$status = false;
$created_counter = 0;
$notfound_counter = 0;
$import_failed = "";

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication
    $user->action_isUpdate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $userId = $user->getUserId();
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

$filename=$_FILES["file"]["tmp_name"];
if($_FILES["file"]["size"] > 0) {
    $file = fopen($filename, "r");
    fgetcsv($file, 10000, ","); // before beginning the while loop, just get the first line and do nothing with it
    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
        if ($getData[0] == NULL) // skip blank lines in file
            continue;

        // Get data from CSV and clean values
        if (!empty($getData[0])) {
            $data_SKU = trim($getData[0]); 
        } else {
            $data_SKU = ""; 
        }

        if (!empty($getData[1])) {
            $data_description = trim($getData[1]);  
        } else {
            $data_description = ""; 
        }

        if (!empty($getData[2])) {
            $data_qty = trim($getData[2]);  
        } else {
            $data_qty = "0"; 
        }

        if (!empty($getData[3])) {
            $data_notes = trim($getData[3]);  
        } else {
            $data_notes = ""; 
        }

        // prepare inventory item object
        $inventory_item = new Inventory($db);
        $inventory_item->SKU = $data_SKU;
        $inventory_item->type = $_POST['inventory_type'];
        $inventory_item->category = $_POST['inventory_category'];

        // prepare projects item object
        $projects_item = new Projects($db);

        // check if SKU exists in inventory
        if ($existingId = $inventory_item->isAlreadyExist()) { // get ID for an existing inventory item
            $projects_item->inventoryId = $existingId;
            $projects_item->type = $_POST['type'];
            $projects_item->description = $data_description;
            $projects_item->qty = $data_qty;
            $projects_item->notes = $data_notes;
            $projects_item->userId = $userId;
            
            if ($projects_item->create(true)) { // create projects item
                $created_counter++;
                $status = true;
            }

        } else {
            $notfound_counter++;
            $import_failed .= $data_SKU . ' ';
        }
       
    }
    fclose($file);
}


$result_arr=array(
    "status" => $status,
    "created_count" => $created_counter,
    "notfound_count" => $notfound_counter,
    "additional_info" => $import_failed
);

print_r(json_encode($result_arr));