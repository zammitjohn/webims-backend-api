<?php
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/inventory.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
$status = false;
$created_counter = 0;
$updated_counter = 0;

$filename=$_FILES["file"]["tmp_name"];
if($_FILES["file"]["size"] > 0) {
    $file = fopen($filename, "r");
    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {

        if (stristr($getData[1],"VNETWORK") or stristr($getData[1],"INDOOR_REPEATER")) {

            // Get Data from CSV and clean unspecified values
            if (!empty($getData[1])) {               
                $data_type = $getData[1];
            } else {
                $data_type = "";
            }

            if (!empty($getData[2])) {
                $data_SKU = $getData[2]; 
            } else {
                $data_SKU = ""; 
            }

            if (!empty($getData[3])) {
                $data_description = $getData[3];  
            } else {
                $data_description = ""; 
            }

            if (!empty($getData[4])) {
                $data_qty = $getData[4];  
            } else {
                $data_qty = ""; 
            }

            if (!empty($getData[6])) {
                $data_qtyIn = $getData[6];  
            } else {
                $data_qtyIn = ""; 
            }

            if (!empty($getData[7])) {
                $data_qtyOut = $getData[7];  
            } else {
                $data_qtyOut = ""; 
            }

            if (!empty($getData[8])) {
                $data_supplier = $getData[8];  
            } else {
                $data_supplier = ""; 
            }
            
            // Overwrite var data_type with correct terminology
            if (stristr($data_type,"return")) {
                $data_type = 4; // Returns
            } else if (stristr($data_type,"repeater")) {
                $data_type = 3; // Repeaters
            } else if (stristr($data_type,"spare")) {
                $data_type = 2; // Spares
            } else {
                $data_type = 1; // General
            }
            
            // prepare inventory item object
            $item = new Inventory($db);
            $item->SKU = $data_SKU;
            $item->type = $data_type;
            $item->description = $data_description;
            $item->qty = $data_qty;
            $item->qtyIn = $data_qtyIn;
            $item->qtyOut = $data_qtyOut;
            $item->supplier = $data_supplier;
            $item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die(); // API Key - sessionId

            // check if SKU already exists
            if ($existingId = $item->isAlreadyExist()) {
                $item->id = $existingId;
                if ($item->update(true)) { // update inventory item
                    $updated_counter++;
                    $status = true;
                }
            } else {
                if ($item->create()) { // create inventory item
                    $created_counter++;
                    $status = true;
                }
            }

        }
    }
    fclose($file);	
}


$result_arr=array(
    "status" => $status,
    "created_count" => $created_counter,
    "updated_count" => $updated_counter
);

print_r(json_encode($result_arr));