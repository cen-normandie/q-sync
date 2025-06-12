<?php
include 'properties.php';

echo '<h2>IMPORT DES OBSERVATIONS faune du serveur nextcloud dans le dossier UUID/files/_qfield/observations.gpkg</h2>';
echo '<h3>table : obs_faune</h3>';


$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", "SELECT id, courriel, gn_user_name, uuid_nx, active FROM $nx_users;");
$personne = pg_execute($dbconn, "sql",array()) or die ( pg_last_error());


while($row = pg_fetch_row($personne))
{
  echo $row[0].' - '.$row[1].' - '.$row[2].' - '.$row[3].' - '.$row[4];
  //FAUNE + AUTRE POINT
  pg_prepare($dbconn, "sql_up", "SELECT id, uuid_nx, uuid_obs, date_import FROM sandbox.suivi_faune where gpkg_updated is false and uuid_nx = $1 LIMIT 1;");
  $to_up = pg_execute($dbconn, "sql_up",array($row[3])) or die ( pg_last_error());
  while($row_ = pg_fetch_row($to_up))
  	{
  		$db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/test.gpkg');
  		$db->loadExtension('mod_spatialite.so');
  		$db->query("CREATE TABLE okay ( aa integer primary key );");
		
		# creating a POINT table
$sql = "CREATE TABLE test_pt (";
$sql .= "id INTEGER NOT NULL PRIMARY KEY,";
$sql .= "name TEXT NOT NULL)";
$db->exec($sql);
# creating a POINT Geometry column
$sql = "SELECT AddGeometryColumn('test_pt', ";
$sql .= "'geom', 4326, 'POINT', 'XY')";
$db->exec($sql);

# creating a POLYGON table
$sql = "CREATE TABLE test_pg (";
$sql .= "id INTEGER NOT NULL PRIMARY KEY,";
$sql .= "name TEXT NOT NULL)";
$db->exec($sql);
# creating a POLYGON Geometry column
$sql = "SELECT AddGeometryColumn('test_pg', ";
$sql .= "'geom', 4326, 'POLYGON', 'XY')";
$db->exec($sql);
		
		
  	}
}


pg_close($dbconn);




?>