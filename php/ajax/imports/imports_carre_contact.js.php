<?php
include '../../properties.php';


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users where observations_gpkg <> 'ø' and (obs_cc> 0);");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
pg_prepare($dbconn_geo, "sql_down", "UPDATE $suivi_point_cc set gpkg_updated = true where uuid_contact = $1;");
// ne fait rien pour le moment
pg_prepare($dbconn_geo, "sql_import_cc_to_occtax", "select sandbox.import_cc();");
pg_prepare($dbconn_geo, "sql_up", "SELECT id, uuid_nx, uuid_contact, date_import FROM $suivi_point_cc where gpkg_updated is false and uuid_nx = $1 ;");

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
    $cmd='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg -nln sandbox.obs_cc -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$cc.'  where date_import is null" 2>&1';
    echo '</br>'.$cmd.'</br>';
    $output=[];
    $return_var=0;
    exec($cmd, $output, $return_var);
    if ($return_var !== 2) {
        //importe toutes les obs cc de sandbox.obs_cc dans geonature
        // envoi tout dans la table sh.carre_contact
        $out = pg_execute($dbconn_geo, "sql_import_cc_to_occtax",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'uuid_contact :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    $results_write_gpkg = $db->query("UPDATE $cc set date_import = datetime('now') where date_import is null and uuid_contact = '".$row_[2]."';"); //
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