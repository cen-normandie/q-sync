<?php
include '../../properties.php';

$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users where w_gpkg <> '' and w_gpkg <> 'ø' and gn_user_name is not null ;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());

$tables_w = array('w_points', 'w_lignes', 'w_polygones');

while($row = pg_fetch_row($personne))
{
  echo '</br>----------------------------------------------------------</br>';
  echo 'Import des données travaux (w_) pour l\'utilisateur : <span class="text-danger">'.$row[2].'</span> ('.$row[0].' - '.$row[3].')</br>';
  $w_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_ge/w_.gpkg';
  if (file_exists($w_gpkg)) {
    foreach ($tables_w as $table) {
        $cmd_='ogr2ogr -f PostgreSQL "PG:user='.$LOGIN_geonature.' host='.$DBHOST_geonature.' dbname='.$DBNAME_geonature.' password='.$PASS_geonature.'" '.$w_gpkg.' -nln sandbox.w_ -append -sql "SELECT *, \''.$row[0].'\' as courriel, \''.$row[3].'\' as uuid_nx from '.$table.'  where import is null" 2>&1';
        $output_=[];
        $return_var=0;
        exec($cmd_, $output_, $return_var);
        if ($return_var == 0) {
            $db = new SQLite3($w_gpkg);
            $db->loadExtension('mod_spatialite.so');
            $results_write_gpkg = $db->query("UPDATE $table set import = datetime('now') where import is null ;");
            if ($results_write_gpkg) {
                echo $db->changes();
                echo ' données mises à jour dans le gpkg ( '.$table.' )</br>';
            } else {echo "Erreur sur le gpkg : " . $db->lastErrorMsg(); }
            $db->close();
        }
        else {
            echo '</br>FAILED try run : '.$cmd_.'</br>';
        }

    }

    // Copie des photos présentes dans le dossier _ge de l'utilisateur vers /var/www/html/q-sync/photos
    // nom de destination : uuid_parent || photo_url (uuid_parent retrouvé dans w_photos via le nom du fichier)
    $photos_dir = '/var/www/html/q-sync/photos';
    if (!is_dir($photos_dir)) { @mkdir($photos_dir, 0777, true); }
    if (!is_writable($photos_dir)) {
      echo 'ERREUR : le dossier '.$photos_dir.' n\'est pas accessible en écriture par le serveur web</br>';
    }
    $ge_dir = '/var/www/html/nextcloud/data/'.$row[3].'/files/_ge';
    $db_photos = new SQLite3($w_gpkg);
    $photos_files = array_merge(
      (array) glob($ge_dir.'/*.jpg'), (array) glob($ge_dir.'/*.jpeg'), (array) glob($ge_dir.'/*.png'),
      (array) glob($ge_dir.'/*.JPG'), (array) glob($ge_dir.'/*.JPEG'), (array) glob($ge_dir.'/*.PNG')
    );
    foreach ($photos_files as $src) {
      $photo_url = basename($src);
      $uuid_parent = $db_photos->querySingle("SELECT uuid_parent FROM w_photos WHERE photo_url = '".SQLite3::escapeString($photo_url)."' LIMIT 1;");
      $dest_name = ($uuid_parent) ? $uuid_parent.$photo_url : $photo_url;
      $dest = $photos_dir.'/'.$dest_name;
      if (copy($src, $dest)) {
        echo 'photo copiée : '.$dest_name.'</br>';
        if (unlink($src)) {
          echo 'photo supprimée côté utilisateur : '.$src.'</br>';
        } else {
          echo 'ERREUR suppression photo côté utilisateur : '.$src.'</br>';
        }
      } else {
        echo 'ERREUR copie photo : '.$src.'</br>';
      }
    }
    $db_photos->close();

  }
}

pg_close($dbconn_geo);

?>
