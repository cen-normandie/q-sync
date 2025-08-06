<?php
exec("sqlite3 /var/www/html/q-sync/_qfield_skeleton/observations.gpkg '.dump meta_qsync' > /var/www/html/q-sync/_qfield_skeleton/dumps/meta_qsync.sql", $output, $retval);
echo "</br>Export depuis observations.gpkg SKELETON with status $retval and output:";
print_r($output);
























?>
