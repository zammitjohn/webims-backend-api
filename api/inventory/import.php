<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
include_once '../objects/warehouse_category.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();

$inventory = new inventory($db);
$inventory->warehouseId = $bodyData['warehouseId']; // set warehouseId property used by import inventorySweep function

// AUTH check
$user = new user($db); // prepare user object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isImport = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $inventory->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

$warehouse_category = new warehouse_category($db);
$categories = $warehouse_category->loadCategories($bodyData['warehouseId']); // load categories for warehouse

$modifiedItemIDs = []; // to keep track of modified inventory item IDs
// result counters
$created_counter = 0;
$updated_counter = 0;
$conflict_counter = 0;
$deleted_counter = 0;
$import_status = false;

// load file
$fileContents = $bodyData['file'];
$file = fopen('data://text/plain' . base64_decode($fileContents),'r');

if($file AND !feof($file)) {
    fgetcsv($file, 10000, ","); // before beginning the while loop, just get the first line and do nothing with it
    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
        if ($getData[0] == NULL) // skip blank lines in file
            continue;
        
        if (($getData[1] != NULL) && $data_warehouse_category = array_search(strtoupper(trim($getData[1])), $categories)) {
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
                        
            // prepare inventory object
            $inventory->SKU = $data_SKU;
            $inventory->warehouse_categoryId = $data_warehouse_category;
            $inventory->description = $data_description;
            $inventory->qty = $data_qty;
            $inventory->qtyIn = $data_qtyIn;
            $inventory->qtyOut = $data_qtyOut;
            $inventory->supplier = $data_supplier;
            $inventory->importDate = $data_date;

            // check if SKU already exists
            if ($existingId = $inventory->isAlreadyExist()) { // update existing inventory item
                $inventory->id = $existingId;

                // check if item was already modified
                if (in_array($inventory->id, $modifiedItemIDs)) {
                    if ($inventory->updateQuantities()) { // update inventory item with quantities to add up
                        $conflict_counter++;
                        $import_status = true;
                    }
                } else if ($inventory->update(true)) { // update inventory item
                    $updated_counter++;
                    $import_status = true;
                    array_push($modifiedItemIDs, $inventory->id); // push ID to modifiedItemIDs
                }

            } else {
                if ($inventory->create(true)) { // create inventory item
                    $created_counter++;
                    $import_status = true;
                    array_push($modifiedItemIDs, $inventory->id); // push ID to modifiedItemIDs
                }
            }

        }

    }
    fclose($file);
    $deleted_counter = $inventory->inventorySweep(); // clean-up operation
}

$result_arr=array(
    "status" => $import_status,
    "created_count" => $created_counter,
    "updated_count" => $updated_counter,
    "conflict_count" => $conflict_counter,
    "deleted_count" => $deleted_counter
);

print_r(json_encode($result_arr));