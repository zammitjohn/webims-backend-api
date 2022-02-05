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
$warehouse_category = []; // array to hold inventory types for particular warehouse

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
$categories = $warehouse_category->loadCategory($_POST['warehouseId']);

$filename=$_FILES["file"]["tmp_name"];
if($_FILES["file"]["size"] > 0) {
    $file = fopen($filename, "r");
    $inventory->import($file, $categories, $_POST['warehouseId']);
}

$result_arr=array(
    "status" => $inventory->import_status,
    "created_count" => $inventory->created_counter,
    "updated_count" => $inventory->updated_counter,
    "conflict_count" => $inventory->conflict_counter,
    "deleted_count" => $inventory->deleted_counter
);

print_r(json_encode($result_arr));