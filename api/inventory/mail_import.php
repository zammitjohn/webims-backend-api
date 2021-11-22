<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';
include_once '../objects/inventory_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$inventory_types = []; // array to hold inventory types for particular category

// Body Data
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($input['sessionId'])){
    $user->action_isImport = true;
    $user->sessionId = $input['sessionId'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// load types
$inventory_types = new Inventory_Types($db);
$inv_types = $inventory_types->loadTypes($input['category']);

// load file
$fileContents = $input['file'];
if ($input['isBase64EncodedContent']){
    $file = fopen('data://text/plain' . base64_decode($fileContents),'r');
} else {
    $file = fopen('data://text/plain' . ($fileContents),'r');
}

if($file AND !feof($file)) {
    $inventory = new Inventory($db);
    $inventory->import($file, $inv_types, $input['category']);
}

$result_arr=array(
    "status" => $inventory->import_status,
    "created_count" => $inventory->created_counter,
    "updated_count" => $inventory->updated_counter,
    "conflict_count" => $inventory->conflict_counter,
    "deleted_count" => $inventory->deleted_counter
);

print_r(json_encode($result_arr));