<?php	
// include database and object files	
include_once '../config/database.php';	
include_once '../objects/reports.php';	
include_once '../objects/users.php';	

// get database connection	
$database = new Database();	
$db = $database->getConnection();	

// prepare reports item object	
$item = new Reports($db);	

// set reports item property values	
$item->id = $_POST['id'];	

// AUTH check 	
$user = new Users($db); // prepare users object	
if (isset($_COOKIE['UserSession'])){	
    $user->action_isUpdate = true;	
    $user->sessionId = json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'};	
}	
if (!$user->validAction()){	
    header("HTTP/1.1 401 Unauthorized");	
    die();	
}	

// remove the reports item	
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