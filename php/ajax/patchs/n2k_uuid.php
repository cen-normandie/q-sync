<?php
include '../../properties.php';

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données PostgreSQL
$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature")
    or die('Connexion impossible à PostgreSQL : ' . pg_last_error());

// Requête pour récupérer les utilisateurs concernés
$select_users = pg_prepare($dbconn_geo, "sql_select_users", "
    SELECT courriel, gn_user_name, nom_ad, uuid_nx
    FROM $nx_users
    WHERE n2k_gpkg <> ''
    AND (n2k_realise_point > 0 OR n2k_realise_ligne > 0 OR n2k_realise_polygone > 0)
");
$users = pg_execute($dbconn_geo, "sql_select_users", array())
    or die('Erreur lors de l\'exécution de la requête PostgreSQL : ' . pg_last_error());

// Ouvrir le fichier de sortie
$output_file = fopen('errors_n2k.txt', 'w')
    or die("Impossible de créer le fichier errors_n2k.txt");

// Initialiser un compteur global pour les erreurs
$total_errors = 0;

// Parcourir chaque utilisateur
while ($row = pg_fetch_row($users)) {
    $courriel = $row[0];
    $gn_user_name = $row[1];
    $nom_ad = $row[2];
    $uuid_nx = $row[3];

    $n2k_gpkg = '/var/www/html/nextcloud/data/' . $uuid_nx . '/files/_qfield/n2k.gpkg';

    if (!file_exists($n2k_gpkg)) {
        echo "[DEBUG] Fichier GeoPackage introuvable pour $uuid_nx : $n2k_gpkg\n";
        continue;
    }

    echo "[DEBUG] Traitement de l'utilisateur : $gn_user_name ($uuid_nx)\n";

    // Ouvrir le GeoPackage
    $db = new SQLite3($n2k_gpkg);
    $db->loadExtension('mod_spatialite.so');

    // Liste des tables à vérifier
    $tables_to_check = ['n2k_realise_polygone', 'n2k_realise_ligne', 'n2k_realise_point'];

    foreach ($tables_to_check as $table) {
        echo "[DEBUG] Vérification de la table : $table\n";

        // Vérifier si la table existe
        $check_table = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        if (!$check_table->fetchArray()) {
            echo "[DEBUG] Table $table introuvable dans le GeoPackage de $uuid_nx\n";
            continue;
        }

        // Vérifier si le champ id_uuid_n2k ou id_uuid_n2k_up existe
        $check_field = $db->query("PRAGMA table_info($table)");
        $field_name = null;
        while ($column = $check_field->fetchArray(SQLITE3_ASSOC)) {
            if ($column['name'] === 'id_uuid_n2k' || $column['name'] === 'id_uuid_n2k_up') {
                $field_name = $column['name'];
                break;
            }
        }

        if (!$field_name) {
            echo "[DEBUG] Champ id_uuid_n2k ou id_uuid_n2k_up introuvable dans la table $table\n";
            continue;
        }

        echo "[DEBUG] Champ utilisé : $field_name\n";

        // 1. Vérifier les lignes avec id_uuid_n2k vide mais importe renseigné
        $query_empty_uuid_with_importe = "
            SELECT COUNT(*) as count
            FROM $table
            WHERE ($field_name IS NULL OR $field_name = '')
            AND importe IS NOT NULL
        ";
        $result = $db->query($query_empty_uuid_with_importe);
        $row_count = $result->fetchArray(SQLITE3_ASSOC);
        $count_empty_uuid_with_importe = $row_count['count'];

        if ($count_empty_uuid_with_importe > 0) {
            $line = "user $gn_user_name $uuid_nx : $nom_ad nombre de $table avec $field_name vide mais importe renseigné : $count_empty_uuid_with_importe\n";
            fwrite($output_file, $line);
            $total_errors += $count_empty_uuid_with_importe;
            echo "[DEBUG] $line";
        }

        // 2. Vérifier les doublons dans id_uuid_n2k avec importe renseigné
        $query_duplicates = "
            SELECT $field_name, COUNT(*) as count
            FROM $table
            WHERE $field_name IS NOT NULL AND $field_name <> ''
            GROUP BY $field_name
            HAVING COUNT(*) > 1
        ";
        $result_duplicates = $db->query($query_duplicates);
        $duplicates_with_importe = 0;

        while ($duplicate_row = $result_duplicates->fetchArray(SQLITE3_ASSOC)) {
            $id_uuid = $duplicate_row[$field_name];
            $query_check_importe = "
                SELECT COUNT(*) as count_with_importe
                FROM $table
                WHERE $field_name = '" . SQLite3::escapeString($id_uuid) . "'
                AND importe IS NOT NULL
            ";
            $result_importe = $db->query($query_check_importe);
            $importe_row = $result_importe->fetchArray(SQLITE3_ASSOC);
            if ($importe_row['count_with_importe'] > 0) {
                $duplicates_with_importe += 1;
            }
        }

        if ($duplicates_with_importe > 0) {
            $line = "user $gn_user_name $uuid_nx : $nom_ad nombre de doublons $field_name dans $table avec importe renseigné : $duplicates_with_importe\n";
            fwrite($output_file, $line);
            $total_errors += $duplicates_with_importe;
            echo "[DEBUG] $line";
        }
    }

    $db->close();
}

fclose($output_file);
pg_close($dbconn_geo);

echo "Le fichier errors_n2k.txt a été généré avec succès. Total des erreurs détectées : $total_errors\n";
?>