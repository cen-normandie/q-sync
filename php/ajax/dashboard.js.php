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
	obs_faune || '/' || obs_faune_imported as obs_faune,
	obs_flore || '/' || obs_flore_imported as obs_flore,
	obs_cc || '/' || obs_cc_imported as obs_cc,
	update,
	version
	FROM $nx_dashboard 
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