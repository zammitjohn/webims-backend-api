<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new user($db);

// check if key was specified
if (!isset($_SERVER['HTTP_AUTH_KEY'])){
    $output_arr=array(
        "status" => false,
        "message" => "You need to login!"
    );
} else { // check if key is correct
    if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
        $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    }
    // query user
    $stmt = $user->validateSession();

    if ($stmt != false){
        if($stmt->rowCount() > 0){
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // create array
            $output_arr=array(
                "status" => true,
                "message" => "Session valid!",
                "firstName" => $row['firstName'],
                "lastName" => $row['lastName'],
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