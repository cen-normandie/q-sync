<?php
include '../../properties.php';


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users where n2k_gpkg <> '' ;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());


$tables_previ = array(' n2k_previ_point ', ' n2k_previ_ligne ', ' n2k_previ_polygone ');
$tables_realise = array($n2k_real_polygone_gpkg, $n2k_real_point_gpkg, $n2k_real_ligne_gpkg);


while($row = pg_fetch_row($personne))
{
  echo '</br>----------------------------------------------------------</br>';
  echo 'Import des données n2k pour l\'utilisateur : <span class="text-danger">'.$row[2].'</span> ('.$row[0].' - '.$row[3].')</br>';
  $n2k_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg';
  if (file_exists($n2k_gpkg)) {

    foreach ($tables_previ as $table) {
        $cmd_='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_previ -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx, id_uuid_n2k as id_uuid_n2k_up from '.$table.'  where importe is null" 2>&1';
        $output_=[];
        $return_var=0;
        exec($cmd_, $output_, $return_var);
        if ($return_var == 0) {
            //echo '<br>Mise à jour de la colonne "importe" du geopackage </br>';
            $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
            $db->loadExtension('mod_spatialite.so');
            $results_write_gpkg = $db->query("UPDATE $table set importe = datetime('now') where importe is null ;"); //
            if ($results_write_gpkg) {
                //echo '</br>Données n2k ( '.$table.' ) importées avec succès ! </br>';
                echo $db->changes();
                echo ' données mises à jour dans le gpkg ( '.$table.' )</br>';
            } else {echo "Erreur sur le gpkg : " . $db->lastErrorMsg(); }
            $db->close();
        }
        else {
            echo '</br>FAILED try run : '.$cmd_polygone.'</br>';
        }

    }
  }
}

pg_close($dbconn_geo);




?>