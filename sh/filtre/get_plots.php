<?php
include '../../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");
$site = $_POST['site'] ?? '';
$annee = $_POST['annee'] ?? '';
$result = pg_query_params($conn, "SELECT DISTINCT plot FROM releves WHERE site=$1 AND annee=$2 ORDER BY plot", [$site, $annee]);
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['plot'], 'label' => $row['plot']];
}
header('Content-Type: application/json');
echo json_encode($data);
?>