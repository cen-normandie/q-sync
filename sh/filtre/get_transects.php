<?php
include '../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");
$site = $_POST['site'] ?? '';
$annee = $_POST['annee'] ?? '';
$plot = $_POST['plot'] ?? '';
$result = pg_query_params($conn, "SELECT DISTINCT transect FROM releves WHERE site=$1 AND annee=$2 AND plot=$3 ORDER BY transect", [$site, $annee, $plot]);
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['transect'], 'label' => $row['transect']];
}
header('Content-Type: application/json');
echo json_encode($data);
?>