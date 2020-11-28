<?php
// include database and object files
include_once '../../api/config/database.php';
include_once '../../api/objects/inventory.php';
include_once '../../api/objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
$status = false;
$created_counter = 0;
$updated_counter = 0;
$conflict_counter = 0;
$modifiedItemIDs = []; // to keep track of modified inventory item IDs

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    $user->action_isImport = true;
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

$filename=$_FILES["file"]["tmp_name"];
if($_FILES["file"]["size"] > 0) {
    $file = fopen($filename, "r");
    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {

        if (stristr($getData[1],"VNETWORK") or stristr($getData[1],"INDOOR_REPEATER")) {

            // Get data from CSV and clean values
            if (!empty($getData[0])) {               
                $data_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($getData[0]))));
            } else {
                $data_date = "";
            }

            if (!empty($getData[1])) {               
                $data_type = trim($getData[1]);
            } else {
                $data_type = "";
            }

            if (!empty($getData[2])) {
                $data_SKU = trim($getData[2]); 
            } else {
                $data_SKU = ""; 
            }

            if (!empty($getData[3])) {
                $data_description = trim($getData[3]);  
            } else {
                $data_description = ""; 
            }

            if (!empty($getData[4])) {
                $data_qty = trim($getData[4]);  
            } else {
                $data_qty = "0"; 
            }

            if (!empty($getData[6])) {
                $data_qtyIn = trim($getData[6]);  
            } else {
                $data_qtyIn = "0"; 
            }

            if (!empty($getData[7])) {
                $data_qtyOut = trim($getData[7]);  
            } else {
                $data_qtyOut = "0"; 
            }

            if (!empty($getData[8])) {
                $data_supplier = trim($getData[8]);  
            } else {
                $data_supplier = ""; 
            }
            
            // Overwrite var data_type with correct terminology
            if (stristr($data_type,"return")) {
                $data_type = 4; // Returns
            } else if (stristr($data_type,"repeater")) {
                $data_type = 3; // Repeaters
            } else if (stristr($data_type,"spare")) {
                $data_type = 2; // Collections
            } else {
                $data_type = 1; // General
            }
            
            // prepare inventory item object
            $item = new Inventory($db);
            $item->SKU = $data_SKU;
            $item->category = 1;
            $item->type = $data_type;
            $item->description = $data_description;
            $item->qty = $data_qty;
            $item->qtyIn = $data_qtyIn;
            $item->qtyOut = $data_qtyOut;
            $item->supplier = $data_supplier;
            $item->inventoryDate = $data_date;

            // check if SKU already exists
            if ($existingId = $item->isAlreadyExist()) { // update existing inventory item
                $item->id = $existingId;

                // check if item was already modified
                if (in_array($item->id, $modifiedItemIDs)) {
                    if ($item->updateQuantities()) { // update inventory item with quantities to add up
                        $conflict_counter++;
                        $status = true;
                    }
                } else if ($item->update(true)) { // update inventory item
                    $updated_counter++;
                    $status = true;
                    array_push($modifiedItemIDs, $item->id); // push ID to modifiedItemIDs
                }

            } else {
                if ($item->create(true)) { // create inventory item
                    $created_counter++;
                    $status = true;
                    array_push($modifiedItemIDs, $item->id); // push ID to modifiedItemIDs
                }
            }

        }
    }
    fclose($file);	
}


$result_arr=array(
    "status" => $status,
    "created_count" => $created_counter,
    "updated_count" => $updated_counter,
    "conflict_count" => $conflict_counter
);

print_r(json_encode($result_arr));