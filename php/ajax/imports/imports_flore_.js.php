<?php
include '../../properties.php';


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users where observations_gpkg <> 'ø' and (obs_flore > 0 or obs_flore_polygone > 0 );");
pg_prepare($dbconn_geo, "sql_import_occtax", "select sandbox.import_flore_();");
//$delete = pg_prepare($dbconn_geo, "sql_delete", "delete from sandbox.obs_flore_tmp ;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());


$tables_flore = array(' obs_flore ', ' obs_flore_polygone '); //, ' obs_flore_ligne '


while($row = pg_fetch_row($personne))
{
  echo '</br>----------------------------------------------------------</br>';
  echo 'Import des données flore pour l\'utilisateur : <span class="text-danger">'.$row[2].'</span> ('.$row[0].' - '.$row[3].')</br>';
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  if (file_exists($observations_gpkg)) {
    foreach ($tables_flore as $table) {
        $cmd_='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg -nln sandbox.obs_flore -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$table.'  where date_import is null" 2>&1';
        $output_=[];
        $return_var=0;
        exec($cmd_, $output_, $return_var);
        //echo '</br>'.$cmd_.'</br>';
        if ($return_var == 0) {
            $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
            $db->loadExtension('mod_spatialite.so');
            $results_write_photo_gpkg = $db->query("SELECT photo_url FROM $table WHERE date_import is null and photo_url is not null;"); //
            if ($results_write_photo_gpkg) {
                foreach ( $results_write_photo_gpkg as $row_photo ) {
                    $photo_file_name = explode("photo/", $row_photo['photo_url'])[1];
                    if ($photo_file_name != '' && file_exists('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/photo/'.$photo_file_name) ) {
                        $cmd_copy_photo = 'cp /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/photo/'.$photo_file_name.' /home/geoa/geonature/backend/media/attachments/5/'.$photo_file_name;
                        echo '</br>'.$cmd_copy_photo.'</br>';
                        $output_cp=[];
                        $return_var_cp=0;
                        exec($cmd_copy_photo, $output_cp, $return_var_cp);
                        if ($return_var_cp == 0) {
                            //echo 'Photo copiée avec succès ! </br>';
                            echo $cmd_copy_photo.'</br>';
                            echo 'Photo copiée avec succès : '.$photo_file_name.' </br>';
                        } else {
                            echo 'Erreur lors de la copie de la photo ! </br>';
                        }
                    }
                }
            } else {echo "Erreur sur le gpkg : " . $db->lastErrorMsg(); }
            $results_write_gpkg = $db->query("UPDATE $table set date_import = datetime('now') where date_import is null ;"); //
            if ($results_write_gpkg) {
                //echo '</br>Données flore ( '.$table.' ) importées avec succès ! </br>';
                echo $db->changes();
                echo ' données mises à jour dans le gpkg ( '.$table.' )</br>';
            } else {echo "Erreur sur le gpkg : " . $db->lastErrorMsg(); }
            $db->close();
        }
        else {
            echo '</br>FAILED try run : '.$cmd_.'</br>';
        }

    }
    
    //intégration des données de sandbox.obs_flore dans geonature --> occ_tax
    $out = pg_execute($dbconn_geo, "sql_import_occtax",array()) or die ( pg_last_error());
    if ($out) {
        echo 'Import des données flore dans Geonature réussi !</br>';
    } else {
        echo pg_last_error($dbconn_geo).'</br>';
    }
  }
}
pg_close($dbconn_geo);




?>