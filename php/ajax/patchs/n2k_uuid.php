<?php
include '../../properties.php';

$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die('Connexion impossible : ' . pg_last_error());

// Requête pour récupérer les utilisateurs concernés
$select_users = pg_prepare($dbconn_geo, "sql_select_users", "
    SELECT courriel, gn_user_name, nom_ad, uuid_nx
    FROM $nx_users
    WHERE n2k_gpkg <> ''
    AND (n2k_realise_point > 0 OR n2k_realise_ligne > 0 OR n2k_realise_polygone > 0)
");
$users = pg_execute($dbconn_geo, "sql_select_users", array()) or die(pg_last_error());

// Ouvrir le fichier de sortie
$output_file = fopen('errors_n2k.txt', 'w') or die("Impossible de créer le fichier errors_n2k.txt");

while ($row = pg_fetch_row($users)) {
    $courriel = $row[0];
    $gn_user_name = $row[1];
    $nom_ad = $row[2];
    $uuid_nx = $row[3];

    $n2k_gpkg = '/var/www/html/nextcloud/data/' . $uuid_nx . '/files/_qfield/n2k.gpkg';

    if (file_exists($n2k_gpkg)) {
        // Compter les polygones sans uuid_n2k
        $db = new SQLite3($n2k_gpkg);
        $db->loadExtension('mod_spatialite.so');

        $query = "SELECT COUNT(*) as count FROM n2k_realise_polygone WHERE id_uuid_n2k IS NULL OR id_uuid_n2k = ''";
        $result = $db->query($query);

        if ($result) {
            $row_count = $result->fetchArray(SQLITE3_ASSOC);
            $count = $row_count['count'];

            if ($count > 0) {
                $line = "user $gn_user_name $uuid_nx : $nom_ad nombre de polygones_realises_sans_uuid : $count\n";
                fwrite($output_file, $line);
            }
        } else {
            echo "Erreur sur la requête : " . $db->lastErrorMsg();
        }

        $db->close();
    }
}

fclose($output_file);
pg_close($dbconn_geo);

echo "Le fichier errors_n2k.txt a été généré avec succès.";
?>