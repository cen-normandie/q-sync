<?php
include '../../properties.php';

// Activer les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à PostgreSQL
$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature")
    or die('Erreur PostgreSQL : ' . pg_last_error());

// Récupérer les utilisateurs avec des données n2k
$select_users = pg_prepare($dbconn_geo, "sql_select_users", "
    SELECT courriel, gn_user_name, nom_ad, uuid_nx
    FROM $nx_users
    WHERE n2k_gpkg <> ''
    AND n2k_realise_polygone > 0
");
$users = pg_execute($dbconn_geo, "sql_select_users", array())
    or die('Erreur requête PostgreSQL : ' . pg_last_error());

// Ouvrir le fichier de sortie
$output_file = fopen('errors_n2k.txt', 'w')
    or die("Impossible de créer errors_n2k.txt");

$total_errors = 0;

while ($row = pg_fetch_row($users)) {
    $courriel = $row[0];
    $gn_user_name = $row[1];
    $nom_ad = $row[2];
    $uuid_nx = $row[3];

    $n2k_gpkg = '/var/www/html/nextcloud/data/' . $uuid_nx . '/files/_qfield/n2k.gpkg';

    if (!file_exists($n2k_gpkg)) {
        echo "[DEBUG] GeoPackage introuvable pour $uuid_nx\n";
        continue;
    }

    echo "[DEBUG] Traitement de $gn_user_name ($uuid_nx)\n";

    $db = new SQLite3($n2k_gpkg);
    $db->loadExtension('mod_spatialite.so');

    // Vérifier que la table n2k_realise_polygone existe
    $check_table = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='n2k_realise_polygone'");
    if (!$check_table->fetchArray()) {
        echo "[DEBUG] Table n2k_realise_polygone introuvable pour $uuid_nx\n";
        $db->close();
        continue;
    }

    // Trouver le nom exact du champ (id_uuid_n2k ou id_uuid_n2K)
    $check_field = $db->query("PRAGMA table_info(n2k_realise_polygone)");
    $field_name = null;
    while ($column = $check_field->fetchArray(SQLITE3_ASSOC)) {
        if (strtolower($column['name']) === 'id_uuid_n2k') {
            $field_name = $column['name'];
            break;
        }
    }

    if (!$field_name) {
        echo "[DEBUG] Champ id_uuid_n2k/id_uuid_n2K introuvable dans n2k_realise_polygone pour $uuid_nx\n";
        $db->close();
        continue;
    }

    echo "[DEBUG] Champ détecté : $field_name\n";

    // =============================================
    // 1. Lister TOUS les id_uuid_n2K SANS données dans importe
    // =============================================
    $query_no_importe = "
        SELECT $field_name, COUNT(*) as count
        FROM n2k_realise_polygone
        WHERE ($field_name IS NOT NULL AND $field_name <> '')
        AND (importe IS NULL OR importe = '')
        GROUP BY $field_name
    ";
    $result_no_importe = $db->query($query_no_importe);
    $no_importe_list = [];
    while ($row_no_importe = $result_no_importe->fetchArray(SQLITE3_ASSOC)) {
        $no_importe_list[] = $row_no_importe[$field_name] . " (x" . $row_no_importe['count'] . ")";
    }

    if (!empty($no_importe_list)) {
        $line = "user $gn_user_name $uuid_nx : $nom_ad | id_uuid_n2K sans importe : " . implode(', ', $no_importe_list) . "\n";
        fwrite($output_file, $line);
        echo "[DEBUG] $line";
        $total_errors += count($no_importe_list);
    }

    // =============================================
    // 2. Lister les DOUBLONS de id_uuid_n2K
    // =============================================
    $query_duplicates = "
        SELECT $field_name, COUNT(*) as count
        FROM n2k_realise_polygone
        WHERE $field_name IS NOT NULL AND $field_name <> ''
        GROUP BY $field_name
        HAVING COUNT(*) > 1
    ";
    $result_duplicates = $db->query($query_duplicates);
    $duplicates_list = [];
    while ($row_duplicates = $result_duplicates->fetchArray(SQLITE3_ASSOC)) {
        $duplicates_list[] = $row_duplicates[$field_name] . " (x" . $row_duplicates['count'] . ")";
    }

    if (!empty($duplicates_list)) {
        $line = "user $gn_user_name $uuid_nx : $nom_ad | doublons id_uuid_n2K : " . implode(', ', $duplicates_list) . "\n";
        fwrite($output_file, $line);
        echo "[DEBUG] $line";
        $total_errors += count($duplicates_list);
    }

    // =============================================
    // 3. Lister les données avec importe mais SANS id_uuid_n2K
    // =============================================
    $query_importe_no_uuid = "
        SELECT COUNT(*) as count
        FROM n2k_realise_polygone
        WHERE (importe IS NOT NULL AND importe <> '')
        AND ($field_name IS NULL OR $field_name = '')
    ";
    $result_importe_no_uuid = $db->query($query_importe_no_uuid);
    $row_importe_no_uuid = $result_importe_no_uuid->fetchArray(SQLITE3_ASSOC);
    $count_importe_no_uuid = $row_importe_no_uuid['count'];

    if ($count_importe_no_uuid > 0) {
        $line = "user $gn_user_name $uuid_nx : $nom_ad | nombre de polygones avec importe mais sans id_uuid_n2K : $count_importe_no_uuid\n";
        fwrite($output_file, $line);
        echo "[DEBUG] $line";
        $total_errors += $count_importe_no_uuid;
    }

    // =============================================
    // 4. Lister TOUS les id_uuid_n2K VIDES (NULL ou chaîne vide)
    // =============================================
    $query_empty_uuid = "
        SELECT COUNT(*) as count
        FROM n2k_realise_polygone
        WHERE $field_name IS NULL OR $field_name = ''
    ";
    $result_empty_uuid = $db->query($query_empty_uuid);
    $row_empty_uuid = $result_empty_uuid->fetchArray(SQLITE3_ASSOC);
    $count_empty_uuid = $row_empty_uuid['count'];

    if ($count_empty_uuid > 0) {
        $line = "user $gn_user_name $uuid_nx : $nom_ad | nombre de polygones avec id_uuid_n2K vide : $count_empty_uuid\n";
        fwrite($output_file, $line);
        echo "[DEBUG] $line";
        $total_errors += $count_empty_uuid;
    }

    $db->close();
}

fclose($output_file);
pg_close($dbconn_geo);

echo "Fichier errors_n2k.txt généré. Total des erreurs : $total_errors\n";
?>