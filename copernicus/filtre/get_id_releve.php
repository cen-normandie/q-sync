<?php
include '../../php/properties.php';
$conn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature");
$site = $_POST['site'];
$type_suivi = $_POST["type_suivi"];
$annee = $_POST["annees"]; // tableau des années
$str_annee = '{' . implode(',', $annee) . '}'; // conversion en chaîne pour la requête SQL

$table = ($type_suivi == "carre_contact") ? "sh.carre_contact" : "sh.releve_phyto";
$data = [];
/* 
echo "SELECT id_releve FROM $table WHERE site = $1 and annee = ANY($2) GROUP BY 1 ORDER BY 1";
 */
/*
$select = pg_prepare($conn, "sql_select", "SELECT id_releve FROM $table WHERE site = $1 and annee = $2 GROUP BY 1 ORDER BY 1");
 foreach ($annee as $key => $value) {
    $annee[$key] = strval($value);
    $result = pg_execute($conn, "sql_select",array( $site, $annee[$key])) or die ( pg_last_error());
    while ($row = pg_fetch_assoc($result)) {
        $data[] = ['value' => $row['id_releve'], 'label' => $row['id_releve']];
    }
} 
*/
$select = pg_prepare($conn, "sql_select", "SELECT id_releve FROM $table WHERE site = $1 and annee = ANY($2) GROUP BY 1 ORDER BY 1");
$result = pg_execute($conn, "sql_select",array( $site, $str_annee )) or die ( pg_last_error());
while ($row = pg_fetch_assoc($result)) {
    $data[] = ['value' => $row['id_releve'], 'label' => $row['id_releve']];
}


echo json_encode($data);
?>