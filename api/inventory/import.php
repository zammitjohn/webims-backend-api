<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';
include_once '../objects/inventory_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$status = false;
$inventory_types = []; // array to hold inventory types for particular category
$created_counter = 0;
$updated_counter = 0;
$conflict_counter = 0;
$deleted_counter = 0;
$modifiedItemIDs = []; // to keep track of modified inventory item IDs

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isImport = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// load types all import_name for particular category
$property = new Inventory_Types($db);
$property->category = $_POST['category'];
$inventory_types_stmt  = $property->read();

while ($inventory_types_row = $inventory_types_stmt->fetch(PDO::FETCH_ASSOC)) { // ...then loop types andd add to array
  extract($inventory_types_row);
  $inventory_types[$id] = strtoupper($import_name);
}

$filename=$_FILES["file"]["tmp_name"];
if($_FILES["file"]["size"] > 0) {
    $file = fopen($filename, "r");
    fgetcsv($file, 10000, ","); // before beginning the while loop, just get the first line and do nothing with it
    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
        if ($getData[0] == NULL) // skip blank lines in file
            continue;
        
        if (($getData[1] != NULL) && $data_type = array_search(strtoupper(trim($getData[1])), $inventory_types)) {

            // Get data from CSV and clean values
            if (!empty($getData[0])) {               
                $data_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($getData[0]))));
            } else {
                $data_date = "";
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
                        
            // prepare inventory item object
            $item = new Inventory($db);
            $item->SKU = $data_SKU;
            $item->category = $_POST['category'];
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
    
    // clean-up operation
    $deleted_counter = $item->inventorySweep();
}


$result_arr=array(
    "status" => $status,
    "created_count" => $created_counter,
    "updated_count" => $updated_counter,
    "conflict_count" => $conflict_counter,
    "deleted_count" => $deleted_counter
);

print_r(json_encode($result_arr));