<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/report_comment.php';
include_once '../../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare report comment property object
$property = new report_comment($db);

// set reportId property of report comment property to be shown 
$property->reportId = $_GET['reportId'];

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query report comment property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // report comment property array
        $output_arr=array();
        $output_arr["report_comment"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $report_comment_property=array(
                "id" => $id,
                "reportId" => $reportId,
                "firstName" => $firstName,
                "lastName" => $lastName,
                "text" => $text,
                "timestamp" => $timestamp
            );
            array_push($output_arr["report_comment"], $report_comment_property);
        }
    
        echo json_encode($output_arr["report_comment"]);
    }
    else{
        echo json_encode(array());
    }
}