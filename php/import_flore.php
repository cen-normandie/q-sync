<?php
include 'properties.php';

echo '<h2>IMPORT DES OBSERVATIONS flore du serveur nextcloud dans le dossier UUID/files/_qfield/observations.gpkg</h2>';
echo '<h3>table : obs_flore</h3>';


$dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", "SELECT id, courriel, gn_user_name, uuid_nx, active FROM $nx_users;");
$personne = pg_execute($dbconn, "sql",array()) or die ( pg_last_error());


while($row = pg_fetch_row($personne))
{
  echo $row[0].' - '.$row[1].' - '.$row[2].' - '.$row[3].' - '.$row[4];
  //FAUNE + AUTRE POINT
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  //if file_exists
  if (file_exists($observations_gpkg)) {
		$cmd='ogr2ogr -f PostgreSQL "PG:user=postgres host=192.168.1.244 dbname=geonature password='.$PASS.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg -nln sandbox.obs_flore -append -sql "SELECT *, \''.$row[1].'\' from obs_flore where date_import is null" 2>&1';
		echo '</br>'.$cmd.'</br>';
		exec($cmd, $output);
		if (!$output) {
			echo '<span style="font-weight:900;color:#056e15">DONE</span></br>';
			pg_prepare($dbconn, "sql_u", "UPDATE $point_flore set uuid_nx = $1, courriel = $2  where uuid_nx is null;");
			$update = pg_execute($dbconn, "sql_u",array($row[3], $row[1])) or die ( pg_last_error());
			
			pg_prepare($dbconn, "sql_import_occtax", "select sandbox.import_flore();");
			$out = pg_execute($dbconn, "sql_import_occtax",array()) or die ( pg_last_error());
			if ($out) {
				pg_prepare($dbconn, "sql_up", "SELECT id, uuid_nx, uuid_obs, date_import FROM $suivi_flore where gpkg_updated is false and uuid_nx = $1 ;");
				$to_up = pg_execute($dbconn, "sql_up",array($row[3])) or die ( pg_last_error());
				while($row_ = pg_fetch_row($to_up))
					{
						$db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
						$db->loadExtension('mod_spatialite.so');
						echo '</br>'."UPDATE obs_flore set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';".'</br>'; //
						$results_write_gpkg = $db->query("UPDATE obs_flore set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';"); //
						pg_prepare($dbconn, "sql_down", "UPDATE $suivi_flore set gpkg_updated = true where uuid_obs = $1;");
						if ($results_write_gpkg) {
							pg_execute($dbconn, "sql_down",array($row_[2])) or die ( pg_last_error());
							$cmd2 = 'sudo -u www-data php /var/www/html/nextcloud/occ files:scan -p "'.$row[3].'"';
							exec($cmd2, $output);
						}
					}
			}
			
		} else {
			echo '<span style="font-weight:900;color:#bc2020">FAILED</span></br>';
			print_r($output);
		}
  } else {
  echo '</br>NOT EXISTS</br>';
  }
}


pg_close($dbconn);




?>