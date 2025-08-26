<?php
$cmd2 = 'sudo -u www-data php /var/www/html/nextcloud/occ files:scan -p "8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67"';
$output2=[];
$return_var2=0;
exec($cmd2, $output2, $return_var2);
if ($return_var2 !== 2) {
    echo 'scan ok -_- </br>';
} else {
    echo 'scan impossible -_- </br>';
    print_r($output2);
}
?>