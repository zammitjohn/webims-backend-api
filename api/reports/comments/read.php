<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/reports_comments.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare reports comment property object
$property = new Reports_Comments($db);

// set reportId property of reports comment property to be shown 
$property->reportId = $_GET['reportId'];

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication 
	$user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); 
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query reports comment property
$stmt = $property->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // reports comment property array
        $output_arr=array();
        $output_arr["Reports_Comments"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $reports_comments_property=array(
                "id" => $id,
                "reportId" => $reportId,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "text" => $text,
                "timestamp" => $timestamp
            );
            array_push($output_arr["Reports_Comments"], $reports_comments_property);
        }
    
        echo json_encode($output_arr["Reports_Comments"]);
    }
    else{
        echo json_encode(array());
    }
}