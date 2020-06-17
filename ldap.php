<?php
/**
 * Created by Joe of ExchangeCore.com
 */
if(isset($_POST['username']) && isset($_POST['password'])){

    $ldap = ldap_connect("ldap://ECMTDC023VW.internal.vodafone.com",389);
    $username = $_POST['username'];
    $password = $_POST['password'];

    $ldaprdn = 'vf-root' . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);

    if ($bind) {
		echo "Login successful";
        $filter="(sAMAccountName=$username)";
        $result = ldap_search($ldap,"DC=internal,DC=vodafone,DC=com",$filter);
        ldap_sort($ldap,$result,"sn");
        $info = ldap_get_entries($ldap, $result);
        for ($i=0; $i<$info["count"]; $i++)
        {
            if($info['count'] > 1)
                break;
            echo "<p>You are accessing <strong> ". $info[$i]["givenname"][0] ." " . $info[$i]["sn"][0] ."</strong><br /> (" . $info[$i]["samaccountname"][0] .")</p>\n";
			echo "<p>Your manager:". $info[$i]["gwereportingto"][0]; 
			echo "<p>Object dump:</p>"; 
            echo '<pre>';
            var_dump($info);
            echo '</pre>';
            $userDn = $info[$i]["distinguishedname"][0]; 
        }
        @ldap_close($ldap);
    } else {
        $msg = "Invalid email address / password";
        echo $msg;
    }

}else{
?>
    <form action="#" method="POST">
        <label for="username">Username: </label><input id="username" type="text" name="username" /> 
        <label for="password">Password: </label><input id="password" type="password" name="password" />        <input type="submit" name="submit" value="Submit" />
    </form>
<?php } ?> 