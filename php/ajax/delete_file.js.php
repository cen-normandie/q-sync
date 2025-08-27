<?php
include '../properties.php';

$output=null;
$retval=null;

$filename = $_POST['file_name'];

$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$dbconn_nx = pg_connect("hostaddr=$DBHOST_nextcloud port=$PORT_nextcloud dbname=$DBNAME_nextcloud user=$LOGIN_nextcloud password=$PASS_nextcloud") or die ('Connexion impossible :'. pg_last_error());

$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $path_file = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/'.$filename.'';
  if (file_exists($path_file)) {
    echo '</br>#########' .$row[0].'</br>';
    exec("rm ".$path_file, $output, $retval);
    echo "status $retval ";
    echo '</br>';
  }
}

pg_close($dbconn_geo);
pg_close($dbconn_nx);




?>