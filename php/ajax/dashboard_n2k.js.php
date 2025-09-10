<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
SELECT 
	uuid,
	personne, 
	n2k_previ_polygone || ' / ' || n2k_previ_polygone_imported as n2k_previ_polygone,
	n2k_previ_ligne || ' / ' || n2k_previ_ligne_imported as n2k_previ_ligne,
	n2k_previ_point || ' / ' || n2k_previ_point_imported as n2k_previ_point,
	update,
	version
	FROM $nx_dashboard_n2k 
) t
"
);

$result = pg_execute($dbconn, "sql", array()) or die ('Connexion impossible :'. pg_last_error());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>