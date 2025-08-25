<?php
include 'properties.php';

echo '<h2>IMPORT DES OBSERVATIONS faune du serveur nextcloud dans le dossier UUID/files/_qfield/observations.gpkg</h2>';
echo '<h3>table : obs_faune</h3>';


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  //if file_exists
  if (file_exists($observations_gpkg)) {
    $cmd='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg -nln sandbox.obs_faune -append -sql "SELECT *, \''.$row[1].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$faune.'  where date_import is null" 2>&1';
    echo '</br>'.$cmd.'</br>';
    exec($cmd, $output);
    if (!$output) {
        echo '<span style="font-weight:900;color:#056e15">DONE</span></br>';
    } else {
        echo '<span style="font-weight:900;color:#bc2020">FAILED</span></br>';
    }
  }
}
			
/* 			pg_prepare($dbconn, "sql_import_occtax", "select sandbox.import_faune();");
			$out = pg_execute($dbconn, "sql_import_occtax",array()) or die ( pg_last_error());
			if ($out) {
				pg_prepare($dbconn, "sql_up", "SELECT id, uuid_nx, uuid_obs, date_import FROM $suivi_faune where gpkg_updated is false and uuid_nx = $1 ;");
				$to_up = pg_execute($dbconn, "sql_up",array($row[3])) or die ( pg_last_error());
				while($row_ = pg_fetch_row($to_up))
					{
						$db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
						$db->loadExtension('mod_spatialite.so');
						echo '</br>'."UPDATE obs_faune set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';".'</br>'; //
						$results_write_gpkg = $db->query("UPDATE obs_faune set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';"); //
						pg_prepare($dbconn, "sql_down", "UPDATE $suivi_faune set gpkg_updated = true where uuid_obs = $1;");
						if ($results_write_gpkg) {
							pg_execute($dbconn, "sql_down",array($row_[2])) or die ( pg_last_error());
							$cmd2 = 'sudo -u www-data php /var/www/html/nextcloud/occ files:scan -p "'.$row[3].'"';
							exec($cmd2, $output);
						}
					}
			} */
			


pg_close($dbconn_geo);




?>