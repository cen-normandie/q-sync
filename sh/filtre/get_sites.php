<?php
include '../../php/properties.php';

$type_suivi = $_POST["type_suivi"] ?? "phyto"; // Pour forcer le type de suivi phyto

$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");

$select = pg_prepare($conn, "sql_select", "SELECT site FROM sh.sites_view WHERE type_suivi = $1 ORDER BY site");
$result = pg_execute($conn, "sql_select",array($type_suivi)) or die ( pg_last_error());
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['site'], 'label' => $row['site']];
}
header('Content-Type: application/json');
echo json_encode($data);
?>