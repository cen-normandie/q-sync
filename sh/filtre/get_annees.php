<?php
include '../../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");

$annee = $_POST['annee'];
$site = $_POST['site'];
$type_suivi = $_POST["type_suivi"];
$table = ($type_suivi == "carre_contact") ? "sh.carres_contact" : "sh.releve_phyto";

$select = pg_prepare($conn, "sql_select", "SELECT annee FROM $table WHERE site = $1 and annee = $2 ORDER BY 1");
$result = pg_execute($conn, "sql_select",array( $site, $annee)) or die ( pg_last_error());
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['annee'], 'label' => $row['annee']];
}
echo json_encode($data);
?>