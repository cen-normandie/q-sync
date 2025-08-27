<?php
include '../properties.php';


$table = $_POST['table_name'];
if (empty($table)) {
    echo "Table name is required.";
    exit();
}
$gpkg = $_POST['gpkg_name'];
if (empty($gpkg)) {
    echo "GPKG name is required.";
    exit();
}

// Création de la sauvegarde d'une table
$db = new SQLite3('/var/www/html/q-sync/_qfield_skeleton/'. $gpkg , SQLITE3_OPEN_READWRITE);
$fichier = "/var/www/html/q-sync/_qfield_skeleton/dumps/".$table.".sql";
$handle = fopen($fichier, 'w');
// Exporter la structure
$structure = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='$table'");
fwrite($handle, "-- Structure de la table\n");
fwrite($handle, "DROP TABLE IF EXISTS $table;\n");
fwrite($handle, $structure['sql'] . ";\n\n");

// Exporter les données
fwrite($handle, "-- Données de la table\n");
$results = $db->query("SELECT * FROM $table");

while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $columns = array_keys($row);
    $values = array_map(function($value) {
        return "'" . SQLite3::escapeString($value) . "'";
    }, array_values($row));

    $insert = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ");\n";
    fwrite($handle, $insert);
}
fclose($handle);
$db->close();

echo "Dump SQL créé dans le fichier $fichier\n";


$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$dbconn_nx = pg_connect("hostaddr=$DBHOST_nextcloud port=$PORT_nextcloud dbname=$DBNAME_nextcloud user=$LOGIN_nextcloud password=$PASS_nextcloud") or die ('Connexion impossible :'. pg_last_error());

$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $path_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/'. $gpkg;
  if (file_exists($path_gpkg)) {

    echo '</br>#########</br>' .$row[0].'</br>';
    $db_user = new SQLite3($path_gpkg );
    // Lire le contenu du dump
    $dump = file_get_contents("/var/www/html/q-sync/_qfield_skeleton/dumps/".$table.".sql");
    // Exécuter les commandes SQL
    $db_user->exec($dump);
    $db_user->close();
    echo "Table ".$table."intégrée avec succès dans la nouvelle base.\n";
  }
}


pg_close($dbconn_geo);
pg_close($dbconn_nx);






?>
