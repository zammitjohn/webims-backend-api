<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';
include_once '../objects/inventory_types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$inventory = new Inventory($db);
$inventory_types = []; // array to hold inventory types for particular category

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isImport = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $inventory->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// imports also saved to root
if (!(is_dir("../../../uploads"))) {
    mkdir("../../../uploads", 0700);
}
if (!(is_dir("../../../uploads/inventory"))) {
    mkdir("../../../uploads/inventory", 0700);
}

// setting target directory
$target_dir = "../../../uploads/inventory/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

// load types
$inventory_types = new Inventory_Types($db);
$inv_types = $inventory_types->loadTypes($_POST['category']);

$filename=$_FILES["file"]["tmp_name"];
if($_FILES["file"]["size"] > 0) {
    $file = fopen($filename, "r");
    move_uploaded_file($filename, $target_file);
    $inventory->import($file, $inv_types, $_POST['category']);
}

$result_arr=array(
    "status" => $inventory->import_status,
    "created_count" => $inventory->created_counter,
    "updated_count" => $inventory->updated_counter,
    "conflict_count" => $inventory->conflict_counter,
    "deleted_count" => $inventory->deleted_counter
);

print_r(json_encode($result_arr));