<?php
include '../../properties.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



   
$db = new SQLite3('/var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/_qfield/observations.gpkg');
$db->loadExtension('mod_spatialite.so');

$results_write_photo_gpkg = $db->query("SELECT photo_url FROM obs_flore WHERE date_import IS NULL AND photo_url IS NOT NULL;");
echo '</br>Import des photos associées aux observations flore :</br>';

if ($results_write_photo_gpkg) {
    echo '</br>Début de la copie des photos...</br>';
    while ($row_photo = $results_write_photo_gpkg->fetchArray(SQLITE3_ASSOC)) {
        echo 'Traitement de la photo : ' . $row_photo['photo_url'] . '</br>';
        $photo_file_name = explode("photo/", $row_photo['photo_url'])[1];
        if ($photo_file_name != '' && file_exists('/var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/_qfield/photo/' . $photo_file_name)) {
            $cmd_copy_photo = 'cp /var/www/html/nextcloud/data/8ACA0A1C-6E6E-4912-8511-DB8A02F1CA67/files/_qfield/photo/' . $photo_file_name . ' /home/geoa/geonature/backend/media/attachments/5/' . $photo_file_name;
            echo '</br>' . $cmd_copy_photo . '</br>';
            $output_cp = [];
            $return_var_cp = 0;
            exec($cmd_copy_photo, $output_cp, $return_var_cp);
            if ($return_var_cp == 0) {
                echo $cmd_copy_photo . '</br>';
                echo 'Photo copiée avec succès : ' . $photo_file_name . ' </br>';
            } else {
                echo 'Erreur lors de la copie de la photo ! </br>';
            }
        }
    }
} else {
    echo "Erreur sur le gpkg : " . $db->lastErrorMsg();
}

/* $results_write_gpkg = $db->query("UPDATE obs_flore SET date_import = datetime('now') WHERE date_import IS NULL;");
if ($results_write_gpkg) {
    echo $db->changes();
    echo ' données mises à jour dans le gpkg ( obs_flore )</br>';
} else {
    echo "Erreur sur le gpkg : " . $db->lastErrorMsg();
} */

$db->close();



?>