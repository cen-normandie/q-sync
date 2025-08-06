<?php
include '../properties.php';

$table = $_POST['table_name'];
if (empty($table)) {
    echo "Table name is required.";
    exit();
}

$output=null;
$retval=null;
echo "INITIALISATION";
echo "</br>";
echo "sqlite3 /var/www/html/q-sync/_qfield_skeleton/observations.gpkg '.dump ".$table."' > /var/www/html/q-sync/_qfield_skeleton/dumps/".$table.".sql";

exec("sqlite3 /var/www/html/q-sync/_qfield_skeleton/observations.gpkg '.dump ".$table."' > /var/www/html/q-sync/_qfield_skeleton/dumps/".$table.".sql", $output, $retval);
echo "</br>Export depuis observations.gpkg SKELETON with status $retval and output:";
echo "</br>";
echo '</br>#########';

$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$dbconn_nx = pg_connect("hostaddr=$DBHOST_nextcloud port=$PORT_nextcloud dbname=$DBNAME_nextcloud user=$LOGIN_nextcloud password=$PASS_nextcloud") or die ('Connexion impossible :'. pg_last_error());

/* $db_skeleton = new SQLite3('/var/www/html/q-sync/_qfield_skeleton/observations.gpkg');
$db_skeleton->loadExtension('mod_spatialite.so'); */

$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  if (file_exists($observations_gpkg)) {
    //echo '</br>' .$row[0]. ' - ' . date('Y-m-d', filemtime($observations_gpkg)) . '</br>';
    echo '</br>#########' .$row[0].'</br>';
    exec("sqlite3 ".$observations_gpkg." 'DROP TABLE ".$_POST['table_name'].";'", $output, $retval);
    echo "Suppression de la table ".$_POST['table_name']." dans ".$observations_gpkg." avec status $retval";
    echo '</br>';
    exec("sqlite3 ".$observations_gpkg."  < /var/www/html/q-sync/_qfield_skeleton/dumps/".$_POST['table_name'].".sql", $output, $retval);
    echo "Importation de la table ".$_POST['table_name']." dans ".$observations_gpkg." avec status $retval";
    echo '</br>';
  }
}

//SCAN
exec("sudo -u www-data php occ files:scan --all", $output, $retval);
echo "Scan des fichiers Nextcloud avec status $retval ";

pg_close($dbconn_geo);
pg_close($dbconn_nx);




?>