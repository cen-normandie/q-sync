<?php
include '../properties.php';

$dbconn = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature")
or die ('Connexion impossible :'. pg_last_error());
$result = pg_prepare($dbconn, "sql", 
"
SELECT array_to_json(array_agg(row_to_json(t))) FROM 
(
SELECT 
	uuid_nx,
	nom_ad as personne, 
	observations_gpkg,
	n2k_gpkg,
	obs_flore as flore,
	obs_faune as faune,
	obs_flore_polygone as flore_polygone,
	obs_cc as carre_contact
	FROM $nx_users 
    where observations_gpkg <> $1 or n2k_gpkg <> $2
	and gn_user_name is not null
) t
"
);

$result = pg_execute($dbconn, "sql", array('', '')) or die ('Connexion impossible :'. pg_last_error());
while($row = pg_fetch_row($result))
{
  echo trim($row[0]);
}
pg_close($dbconn);
?>