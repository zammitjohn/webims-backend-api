<?php	
// include database and object files	
include_once '../config/database.php';	
include_once '../objects/report.php';	
include_once '../objects/user.php';	

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection	
$database = new Database();	
$db = $database->getConnection();	

// prepare report object	
$report = new report($db);	

// set report object values	
$report->id = $bodyData['id'];	

// AUTH check	
$user = new user($db); // prepare user object	

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;	
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $report->userId = $user->getUserId();	
}
if (!$user->validAction()){	
    header("HTTP/1.1 401 Unauthorized");	
    die();	
}	

// remove the report item	
if($report->toggle_repairable()){	
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