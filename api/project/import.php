<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/project_item.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();

$project_item = new project_item($db); // prepare project_item object

// AUTH check
$user = new user($db); // prepare user object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $project_item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

$inventory = new inventory($db); // prepare inventory object

$status = false;
$created_counter = 0;
$notfound_counter = 0;
$import_failed = "";

// load file
$fileContents = $bodyData['file'];
$file = fopen('data://text/plain' . base64_decode($fileContents),'r');

if($file AND !feof($file)) {
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


        $inventory->SKU = $data_SKU;
        $inventory->warehouse_categoryId = $bodyData['warehouse_categoryId'];

        // check if SKU exists in inventory
        if ($existingId = $inventory->isAlreadyExist()) { // get ID for an existing inventory item
            $project_item->inventoryId = $existingId;
            $project_item->projectId = $bodyData['id'];
            $project_item->description = $data_description;
            $project_item->qty = $data_qty;
            $project_item->notes = $data_notes;
            
            if ($project_item->create(true)) { // create project_item item
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