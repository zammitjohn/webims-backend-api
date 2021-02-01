<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare users object
$user = new Users($db);

// check if key was specified
if (!isset($_COOKIE['UserSession'])){
    $output_arr=array(
        "status" => false,
        "message" => "You need to login!"
    );
} else { // check if key is correct

    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});

    // query users
    $stmt = $user->validateSession();

    if ($stmt != false){
        if($stmt->rowCount() > 0){
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // create array
            $output_arr=array(
                "status" => true,
                "message" => "Session valid!",
                "firstname" => $row['firstname'],
                "lastname" => $row['lastname'],
                "canUpdate" => $row['canUpdate'],
                "canCreate" => $row['canCreate'],
                "canImport" => $row['canImport'],
                "canDelete" => $row['canDelete']
            );
        } else {
            $output_arr=array(
                "status" => false,
                "message" => "Session invalid!"
            );
        }
    }
}
print_r(json_encode($output_arr));