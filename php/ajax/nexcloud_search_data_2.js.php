<?php
include '../properties.php';

$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$dbconn_nx = pg_connect("hostaddr=$DBHOST_nextcloud port=$PORT_nextcloud dbname=$DBNAME_nextcloud user=$LOGIN_nextcloud password=$PASS_nextcloud") or die ('Connexion impossible :'. pg_last_error());


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Observations
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////
// A. Insertion des nouveaux utilisateurs dans la table nextcloud.users
///////////////////////////////////////////////////////////////////
$setval = pg_prepare($dbconn_geo, "sql_setval_a", "SELECT setval( 'nextcloud.users_id_seq', 1);");
$setval = pg_execute($dbconn_geo, "sql_setval_a",array()) or die ( pg_last_error());
$setval = pg_prepare($dbconn_geo, "sql_setval_b", "SELECT setval( 'nextcloud.dashboard_id_seq', 1);");
$setval = pg_execute($dbconn_geo, "sql_setval_b",array()) or die ( pg_last_error());


$delete = pg_prepare($dbconn_geo, "sql", "DELETE FROM $nx_users;");
$delete = pg_execute($dbconn_geo, "sql",array()) or die ( pg_last_error());


$update_users = pg_prepare($dbconn_geo, "update_users", "UPDATE $nx_users set observations_gpkg = $1, n2k_gpkg = $2 where uuid_nx= $3;");
$insert_s = pg_prepare($dbconn_nx, "sql", "
with a_ as (
SELECT id, uid, value as name_
	FROM $nx_account
	where name = 'displayname'
)
, b_ as (
SELECT id, uid, lower(value) as email
	FROM $nx_account
	where name = 'email' and value like '%@cen-normandie.fr'
)
select a_.uid, a_.name_, b_.email from a_ left join b_ on a_.uid = b_.uid
where b_.email is not null
group by 1,2,3
order by 3
");
//PREPARE ONE INSERT
$insert = pg_prepare($dbconn_geo, "sql_insert", "INSERT INTO $nx_users (uuid_nx, nom_ad, courriel) VALUES ($1, $2, $3);");
//EXECUTE LES INSERT
$insert_s = pg_execute($dbconn_nx, "sql",array()) or die ( pg_last_error());
while($row = pg_fetch_row($insert_s))
{
  $insert = pg_execute($dbconn_geo, "sql_insert",array($row[0], $row[1], $row[2])) or die ( pg_last_error());
}

///////////////////////////////////////////////////////////////////
// B. Mise à jour du champ gn_user_name dans la table nextcloud.users
///////////////////////////////////////////////////////////////////
$update = pg_prepare($dbconn_geo, "sql_update", "
with a_ as (select courriel, nom_ad, id_role, email from $nx_users
left join utilisateurs.t_roles on t_roles.email = courriel
group by 1,2,3,4
order by 1
)
update $nx_users set gn_user_name = a_.id_role::text from a_ where a_.email = users.courriel;
");
$update_ = pg_execute($dbconn_geo, "sql_update",array()) or die ( pg_last_error());

//////////////////////////////////////////////////////////////////////////
// C. Mise à jour du champ uuid_nx dans la table suivi_faune du geopackage
//////////////////////////////////////////////////////////////////////////

$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users where active is true;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  $n2k_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg';
  $o_gpkg = '';
  $n2k_gpkg = '';
  if (file_exists($observations_gpkg)) {
    $o_gpkg=date('Y-m-d', filemtime($observations_gpkg));
  }
  if (file_exists($n2k_gpkg)) {
    $n2k_gpkg=date('Y-m-d', filemtime($n2k_gpkg));
  }

  $update_users_ = pg_execute($dbconn_geo, "update_users",array($o_gpkg, $n2k_gpkg, $row[3])) or die ( pg_last_error());
}

pg_close($dbconn_geo);
pg_close($dbconn_nx);

?>