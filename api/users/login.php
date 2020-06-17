<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new Users($db);

// set user property values
$username = $_POST['username'];
$password = $_POST['password'];

// New LAPD connection
$ldap = ldap_connect("ldap://ECMTDC023VW.internal.vodafone.com",389);
$ldaprdn = 'vf-root' . "\\" . $username;
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

// Bind to LDAP directory
$bind = @ldap_bind($ldap, $ldaprdn, $password);

if ($bind) { // user found in directory 
    // narrow down search
    $filter="(sAMAccountName=$username)";
    $result = ldap_search($ldap,"DC=internal,DC=vodafone,DC=com",$filter);
    $info = ldap_get_entries($ldap, $result);
    for ($i=0; $i<$info["count"]; $i++) {

        // getting values from result
        $user->username = $info[$i]["samaccountname"][0];
        $user->firstname = $info[$i]["givenname"][0];
        $user->lastname = $info[$i]["sn"][0];

        // add user to database
        $stmt = $user->login();
        if($stmt){
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // create array
            $user_arr=array(
                "status" => true,
                "message" => "Login successful!",
                "id" => $row['id'],
                "username" => $row['username'],
                "firstname" => $row['firstname'],
                "lastname" => $row['lastname'],
                "sessionId" => $row['sessionId'],
                "created" => $row['created']
            );
        } else {
            $user_arr=array(
                "status" => false,
                "message" => "Failed to add user to database!"
            );
        }

    }
    @ldap_close($ldap);

} else { // user not found in directory
    $user_arr=array(
        "status" => false,
        "message" => "Invalid username or password!"
    );
}

print_r(json_encode($user_arr));
?>