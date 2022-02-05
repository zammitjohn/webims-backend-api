<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
include_once '../objects/warehouse_category.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$inventory = new inventory($db);
$warehouse_category = []; // array to hold inventory types for particular warehouseId

// Body Data
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

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

// load categories for warehouse
$warehouse_category = new warehouse_category($db);
$categories = $warehouse_category->loadCategory($input['warehouseId']);

// load file
$fileContents = $input['file'];
if ($input['isBase64EncodedContent']){
    $file = fopen('data://text/plain' . base64_decode($fileContents),'r');
} else {
    $file = fopen('data://text/plain' . ($fileContents),'r');
}

if($file AND !feof($file)) {
    $inventory->import($file, $categories, $input['warehouseId']);
}

$result_arr=array(
    "status" => $inventory->import_status,
    "created_count" => $inventory->created_counter,
    "updated_count" => $inventory->updated_counter,
    "conflict_count" => $inventory->conflict_counter,
    "deleted_count" => $inventory->deleted_counter
);

print_r(json_encode($result_arr));