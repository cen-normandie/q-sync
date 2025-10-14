<?php
include '../../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");
$site = $_POST['site'] ?? '';
$result = pg_query_params($conn, "SELECT DISTINCT annee FROM sh.releve_phyto WHERE site=$1 ORDER BY annee", [$site]);
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['annee'], 'label' => $row['annee']];
}
header('Content-Type: application/json');
echo json_encode($data);
?>