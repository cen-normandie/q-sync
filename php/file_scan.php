<?php
$cmd2 = 'php /var/www/html/nextcloud/occ files:scan --all';
$output2=[];
$return_var2=0;
exec($cmd2, $output2, $return_var2);
if ($return_var2 !== 2) {
    echo 'scan ok ! </br>';
    //print_r($output2);
} else {
    echo 'scan impossible -_- !</br>';
    //print_r($output2);
}
?>