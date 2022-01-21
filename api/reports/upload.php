<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// uploads are saved to root
if (!(is_dir("../../../uploads"))) {
    mkdir("../../../uploads", 0700);
}
if (!(is_dir("../../../uploads/reports"))) {
    mkdir("../../../uploads/reports", 0700);
}
if (!(is_dir("../../../uploads/reports/" . $_POST['reportId'] . "/"))) {
    mkdir("../../../uploads/reports/" . $_POST['reportId'] . "/", 0700);
}

// setting target directory
$target_dir = "../../../uploads/reports/" . $_POST['reportId'] . "/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication
    $user->action_isImport = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isImport = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}


// Check file size
if ($_FILES["file"]["size"] > 20000000) {
    $status = false;
    $message = "File too large!";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $status = true;
        $message = "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
    } else {
        $status = false;
        $message = "Unknown error occured!";
    }
}


$result_arr=array(
    "status" => $status,
    "message" => $message
);

print_r(json_encode($result_arr));