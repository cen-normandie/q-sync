<?php
include '../../properties.php';


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users ;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
pg_prepare($dbconn_geo, "sql_down_previ", "UPDATE $n2k_suivi_previ set gpkg_updated = true where id_uuid_n2k = $1;");
pg_prepare($dbconn_geo, "sql_down_realise", "UPDATE $n2k_suivi_real set gpkg_updated = true where id_uuid_n2k = $1;");
pg_prepare($dbconn_geo, "sql_import_n2k_previ", "select sandbox.import_n2k_previ();");
pg_prepare($dbconn_geo, "sql_import_n2k_realise", "select sandbox.import_n2k_realise();");
pg_prepare($dbconn_geo, "sql_up_previ", "SELECT id, uuid_nx, id_uuid_n2k, importe FROM $n2k_suivi_previ where gpkg_updated is false and uuid_nx = $1 ;");
pg_prepare($dbconn_geo, "sql_up_realise", "SELECT id, uuid_nx, id_uuid_n2k, importe FROM $n2k_suivi_real where gpkg_updated is false and uuid_nx = $1 ;");

while($row = pg_fetch_row($personne))
{
  /* 
  echo 'courriel :'.$row[0].'</br>';
  echo 'gn_user_name :'.$row[1].'</br>';
  echo 'nom_ad :'.$row[2].'</br>';
  echo 'uuid_nx :'.$row[3].'</br>'; 
  */
  $n2k_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg';
  if (file_exists($n2k_gpkg)) {

    echo '</br>Import des données N2K PREVI de '.$row[0].' - uuid_nx : '.$row[3].'</br>';
    $cmd_polygone='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_previ -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$n2k_previ_polygone_gpkg.'  where importe is null" 2>&1';
    $cmd_point='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_previ -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$n2k_previ_point_gpkg.'  where importe is null" 2>&1';
    $cmd_ligne='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_previ -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$n2k_previ_ligne_gpkg.'  where importe is null" 2>&1';
    echo '</br>'.$cmd_polygone.'</br>';
    echo '</br>'.$cmd_point.'</br>';
    echo '</br>'.$cmd_ligne.'</br>';

    $output_polygone=[];
    $output_point=[];
    $output_ligne=[];
    $return_var_polygone=0;
    $return_var_point=0;
    $return_var_ligne=0;
    exec($cmd_polygone, $output_polygone, $return_var_polygone);
    exec($cmd_point, $output_point, $return_var_point);
    exec($cmd_ligne, $output_ligne, $return_var_ligne);
    if ($return_var_polygone !== 2) {
        $out = pg_execute($dbconn_geo, "sql_import_n2k_previ",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up_previ",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'id_uuid_n2k :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    echo '</br>Import des données N2K PREVI POLYGONE de '.$row[0].' - uuid_nx : '.$row[3].' - uuid_n2k : '.$row_[2].'</br>';
                    $results_write_gpkg = $db->query("UPDATE $n2k_previ_polygone_gpkg set importe = datetime('now') where importe is null and id_uuid_n2k = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down_previ",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    }
    else {
        echo '</br>FAILED polygone</br>';
    }
    if ($return_var_point !== 2) {
        $out = pg_execute($dbconn_geo, "sql_import_n2k_previ",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up_previ",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'id_uuid_n2k :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    echo '</br>Import des données N2K PREVI POINT de '.$row[0].' - uuid_nx : '.$row[3].' - uuid_n2k : '.$row_[2].'</br>';
                    $results_write_gpkg = $db->query("UPDATE $n2k_previ_point_gpkg set importe = datetime('now') where importe is null and id_uuid_n2k = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down_previ",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    }
    else {
        echo '</br>FAILED point</br>';
    }
    if ($return_var_ligne !== 2) {
        $out = pg_execute($dbconn_geo, "sql_import_n2k_previ",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up_previ",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'id_uuid_n2k :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    echo '</br>Import des données N2K PREVI LIGNE de '.$row[0].' - uuid_nx : '.$row[3].' - uuid_n2k : '.$row_[2].'</br>';
                    $results_write_gpkg = $db->query("UPDATE $n2k_previ_ligne_gpkg set importe = datetime('now') where importe is null and id_uuid_n2k = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down_previ",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    } 
    else {
        echo '</br>FAILED ligne</br>';
    }
    echo '</br>Import des données N2K REALISE de '.$row[0].' - uuid_nx : '.$row[3].'</br>';
    $cmd_polygone='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_realise -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$n2k_real_polygone_gpkg.'  where importe is null or up_date > importe" 2>&1';
    $cmd_point='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_realise -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$n2k_real_point_gpkg.'  where importe is null or up_date > importe" 2>&1';
    $cmd_ligne='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg -nln sandbox.n2k_realise -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$n2k_real_ligne_gpkg.'  where importe is null or up_date > importe" 2>&1';
    //echo '</br>'.$cmd_polygone.'</br>';
    $output_polygone=[];
    $output_point=[];
    $output_ligne=[];
    $return_var_polygone=0;
    $return_var_point=0;
    $return_var_ligne=0;
    exec($cmd_polygone, $output_polygone, $return_var_polygone);
    exec($cmd_point, $output_point, $return_var_point);
    exec($cmd_ligne, $output_ligne, $return_var_ligne);
    if ($return_var_polygone !== 2) {
        $out = pg_execute($dbconn_geo, "sql_import_n2k_realise",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up_realise",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'id_uuid_n2k :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    $results_write_gpkg = $db->query("UPDATE $n2k_real_polygone_gpkg set importe = datetime('now') where importe is null and id_uuid_n2k = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down_realise",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    }
    else {
        echo '</br>FAILED polygone</br>';
    }
    if ($return_var_point !== 2) {
        $out = pg_execute($dbconn_geo, "sql_import_n2k_realise",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up_realise",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'id_uuid_n2k :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    $results_write_gpkg = $db->query("UPDATE $n2k_real_point_gpkg set importe = datetime('now') where importe is null and id_uuid_n2k = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down_realise",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    }
    else {
        echo '</br>FAILED point</br>';
    }
    if ($return_var_ligne !== 2) {
        $out = pg_execute($dbconn_geo, "sql_import_n2k_realise",array()) or die ( pg_last_error());
        if ($out) {
            $to_up = pg_execute($dbconn_geo, "sql_up_realise",array($row[3])) or die ( pg_last_error());
            while($row_ = pg_fetch_row($to_up))
                {
                    echo 'id_uuid_n2k :'.$row_[2].' - date_import : '.$row_[3].' uuid_nx : '.$row_[1].'</br>';
                    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg');
                    $db->loadExtension('mod_spatialite.so');
                    $results_write_gpkg = $db->query("UPDATE $n2k_real_ligne_gpkg set importe = datetime('now') where importe is null and id_uuid_n2k = '".$row_[2]."';"); //
                    if ($results_write_gpkg) {
                        echo $db->changes();
                        pg_execute($dbconn_geo, "sql_down_realise",array($row_[2])) or die ( pg_last_error());
                    }
                    $db->close();
                }
        }
    } 
    else {
        echo '</br>FAILED ligne</br>';
    }


  }
}

pg_close($dbconn_geo);




?>