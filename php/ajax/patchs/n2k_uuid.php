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
        $db = new SQLite3($n2k_gpkg);
        $db->loadExtension('mod_spatialite.so');

        // Vérifier si le champ id_uuid_n2k existe dans la table n2k_realise_polygone
        $check_field_query = $db->query("PRAGMA table_info(n2k_realise_polygone)");
        $has_id_uuid_n2k = false;
        $field_name = 'id_uuid_n2k'; // Nom par défaut

        while ($column = $check_field_query->fetchArray(SQLITE3_ASSOC)) {
            if ($column['name'] === 'id_uuid_n2k') {
                $has_id_uuid_n2k = true;
                $field_name = 'id_uuid_n2k';
                break;
            } elseif ($column['name'] === 'id_uuid_n2k_up') {
                $has_id_uuid_n2k = true;
                $field_name = 'id_uuid_n2k_up';
                break;
            }
        }

        if (!$has_id_uuid_n2k) {
            $db->close();
            continue; // Passer à l'utilisateur suivant si le champ n'existe pas
        }

        // 1. Vérifier les polygones avec id_uuid_n2k vide mais importe renseigné
        $query_empty_uuid_with_importe = "
            SELECT COUNT(*) as count
            FROM n2k_realise_polygone
            WHERE ($field_name IS NULL OR $field_name = '')
            AND importe IS NOT NULL
        ";
        $result = $db->query($query_empty_uuid_with_importe);
        $row_count = $result->fetchArray(SQLITE3_ASSOC);
        $count_empty_uuid_with_importe = $row_count['count'];

        // 2. Vérifier les doublons dans id_uuid_n2k avec importe renseigné
        $query_duplicates_with_importe = "
            SELECT $field_name, COUNT(*) as count
            FROM n2k_realise_polygone
            WHERE $field_name IS NOT NULL AND $field_name <> ''
            GROUP BY $field_name
            HAVING COUNT(*) > 1
        ";
        $result_duplicates = $db->query($query_duplicates_with_importe);
        $duplicates_with_importe = 0;

        while ($duplicate_row = $result_duplicates->fetchArray(SQLITE3_ASSOC)) {
            $id_uuid = $duplicate_row[$field_name];
            $query_check_importe = "
                SELECT COUNT(*) as count_with_importe
                FROM n2k_realise_polygone
                WHERE $field_name = '" . SQLite3::escapeString($id_uuid) . "'
                AND importe IS NOT NULL
            ";
            $result_importe = $db->query($query_check_importe);
            $importe_row = $result_importe->fetchArray(SQLITE3_ASSOC);
            if ($importe_row['count_with_importe'] > 0) {
                $duplicates_with_importe += 1;
            }
        }

        // Écrire les erreurs dans le fichier
        if ($count_empty_uuid_with_importe > 0) {
            $line = "user $gn_user_name $uuid_nx : $nom_ad nombre de polygones avec $field_name vide mais importe renseigné : $count_empty_uuid_with_importe\n";
            fwrite($output_file, $line);
        }

        if ($duplicates_with_importe > 0) {
            $line = "user $gn_user_name $uuid_nx : $nom_ad nombre de doublons $field_name avec importe renseigné : $duplicates_with_importe\n";
            fwrite($output_file, $line);
        }

        $db->close();
    }
}

fclose($output_file);
pg_close($dbconn_geo);

echo "Le fichier errors_n2k.txt a été généré avec succès.";
?>