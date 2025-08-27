<?php
session_start ();
include 'properties.php';

if (isset($_SESSION)) {
    session_destroy();
}

$mail_bd_result = '';
$password_bd_result = '';
$id_user_bd_result = '';
$id_ids_obs_bd_result = '';
$nom_ids_obs_bd_result = '';

if (isset($_POST['email']) && isset($_POST['password'])) {
    if( ($_POST['email'] != '') && ($_POST['password'] != '') ) {

        $ldaphost="192.168.0.211";
        $ldapconn=ldap_connect($ldaphost);
        if($ldapconn)
            //echo "Connect success<br>";
        //else
            //echo "Connect Failure";
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if ($ldapconn) {
            $log = "CSNHN\\".$_POST['email'];
            // binding to ldap server
            $ldapbind = ldap_bind($ldapconn, $log, $_POST['password']);
            // verify binding
            if ($ldapbind) {
                    $filter="(sAMAccountName=".$_POST['email'].")";
                    $result=ldap_search($ldapconn, "DC=CSNHN,DC=LOCAL", $filter);
                    $entries= ldap_get_entries($ldapconn, $result);
                    $groups = $entries[0]["memberof"];

                    session_start ();
                    $_SESSION['email'] = $entries[0]["mail"][0];
                    $_SESSION['password'] = $_POST['password'];
                    $_SESSION['session'] = $entries[0]["mail"][0];
                    $_SESSION['is_equipe_si'] = false;
        
                    foreach($groups as $group) {
                        if( str_contains($group, 'si_web')) {
                            $_SESSION['is_equipe_si'] = true;
                        }
                    }
                if($_SESSION['is_equipe_si']) {
                    echo "Success";
                } else {
                    echo "not_admin";
                }
                
            } else {
                echo "LDAP bind failed...";
            }
        }

    }
    else {
        echo "Failed";
    }
}
else {
    /*header ('location: index.php');*/
    echo "Failed and failed";
}
?>