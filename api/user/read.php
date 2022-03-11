<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new user($db);

// AUTH check
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query user
$stmt = $user->read();

if ($stmt->rowCount()) {
    // user array
    $output_arr=array();
    $output_arr["user"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $user=array(
            "id" => $id,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "lastAvailable" => $lastAvailable
        );
        array_push($output_arr["user"], $user);
    }
    echo json_encode($output_arr["user"]);

} else {
    echo json_encode(array());
}