<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare users object
$user = new Users($db);

// API Key - sessionId
$user->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// query users
$stmt = $user->read();

if ($stmt != false){
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // users array
        $user_arr=array();
        $user_arr["Users"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $users=array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname
            );
            array_push($user_arr["Users"], $users);
        }
    
        echo json_encode($user_arr["Users"]);
    }
    else{
        echo json_encode(array());
    }
}