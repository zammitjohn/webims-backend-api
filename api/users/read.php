<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare users object
$user = new Users($db);

// API AUTH Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validKey()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query users
$stmt = $user->read();

if ($stmt != false){
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // users array
        $output_arr=array();
        $output_arr["Users"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $users=array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname
            );
            array_push($output_arr["Users"], $users);
        }
    
        echo json_encode($output_arr["Users"]);
    }
    else{
        echo json_encode(array());
    }
}