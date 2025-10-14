<?php
include '../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");
$result = pg_query($conn, "SELECT DISTINCT site FROM releves ORDER BY site");
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['site'], 'label' => $row['site']];
}
header('Content-Type: application/json');
echo json_encode($data);
?>