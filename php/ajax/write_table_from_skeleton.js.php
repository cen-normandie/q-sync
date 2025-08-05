<?php
include '../properties.php';

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
    echo '</br>' .$row[0]. ' - ' . date('Y-m-d', filemtime($observations_gpkg)) . '</br>';

    echo("sqlite3 '/var/www/html/q-sync/_qfield_skeleton/observations.gpkg' .dump \"$table_name\" | sqlite3 '/var/www/html/nextcloud/data/\"$row[3]\"/files/_qfield/observations.gpkg'");
    echo '</br>';
  }


}





pg_close($dbconn_geo);
pg_close($dbconn_nx);




?>