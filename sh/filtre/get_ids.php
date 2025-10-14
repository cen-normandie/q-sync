<?php
include '../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");
$site = $_POST['site'] ?? '';
$annee = $_POST['annee'] ?? '';
$plot = $_POST['plot'] ?? '';
$transect = $_POST['transect'] ?? '';
$result = pg_query_params($conn, "SELECT DISTINCT id_releve FROM releves WHERE site=$1 AND annee=$2 AND plot=$3 AND transect=$4 ORDER BY id_releve", [$site, $annee, $plot, $transect]);
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['id_releve'], 'label' => $row['id_releve']];
}
header('Content-Type: application/json');
echo json_encode($data);
?>