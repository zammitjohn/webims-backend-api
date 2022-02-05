<?php	
// include database and object files	
include_once '../config/database.php';	
include_once '../objects/report.php';	
include_once '../objects/user.php';	

// get database connection	
$database = new Database();	
$db = $database->getConnection();	

// prepare report item object	
$item = new report($db);	

// set report item property values	
$item->id = $_POST['id'];	

// AUTH check	
$user = new user($db); // prepare user object	
if (isset($_COOKIE['UserSession'])){ // Cookie authentication	
    $user->action_isUpdate = true;	
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $item->userId = $user->getUserId();	
}	
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;	
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $item->userId = $user->getUserId();	
}
if (!$user->validAction()){	
    header("HTTP/1.1 401 Unauthorized");	
    die();	
}	

// remove the report item	
if($item->toggle_repairable()){	
    $output_arr=array(	
        "status" => true,	
        "message" => "Successfully updated!"	
    );	
}	
else{	
    $output_arr=array(	
        "status" => false,	
        "message" => "Failed to update!"	
    );	
}	
print_r(json_encode($output_arr));