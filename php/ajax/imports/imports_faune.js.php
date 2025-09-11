<?php
include '../../properties.php';


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users ;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
pg_prepare($dbconn_geo, "sql_down", "UPDATE $suivi_point_faune set gpkg_updated = true where uuid_obs = $1;");
pg_prepare($dbconn_geo, "sql_import_occtax", "select sandbox.import_faune();");
pg_prepare($dbconn_geo, "sql_up", "SELECT id, uuid_nx, uuid_obs, date_import FROM $suivi_point_faune where gpkg_updated is false and uuid_nx = $1 ;");

while($row = pg_fetch_row($personne))
{
  /* 
  echo 'courriel :'.$row[0].'</br>';
  echo 'gn_user_name :'.$row[1].'</br>';
  echo 'nom_ad :'.$row[2].'</br>';
  echo 'uuid_nx :'.$row[3].'</br>'; 
  */
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  if (file_exists($observations_gpkg)) {
    $cmd='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg -nln sandbox.obs_faune -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$faune.'  where date_import is null" 2>&1';
    $output=[];
    $return_var=0;
    //Envoi toutes les obs faune sans date_import du geopackage dans sandbox.obs_faune
    exec($cmd, $output, $return_var);
    if ($return_var !== 2) {
        //Execution de la fonction PG sandbox.import_faune() qui traite les données de sandbox.obs_faune sans date_import pour en faire des occurences
        $out = pg_execute($dbconn_geo, "sql_import_occtax",array()) or die ( pg_last_error());
        //TO DO : créé un relevé pour un ensemble d'occurences
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'uuid_obs :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    $results_write_gpkg = $db->query("UPDATE $faune set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    } else {
        echo '</br>FAILED</br>';
    }
  }
}

pg_close($dbconn_geo);




?>