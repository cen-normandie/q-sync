<?php
include '../properties.php';

$dbconn_geo = pg_connect("hostaddr=$DBHOST_geonature port=$PORT_geonature dbname=$DBNAME_geonature user=$LOGIN_geonature password=$PASS_geonature") or die ('Connexion impossible :'. pg_last_error());
$dbconn_nx = pg_connect("hostaddr=$DBHOST_nextcloud port=$PORT_nextcloud dbname=$DBNAME_nextcloud user=$LOGIN_nextcloud password=$PASS_nextcloud") or die ('Connexion impossible :'. pg_last_error());

///////////////////////////////////////////////////////////////////
// A. Insertion des nouveaux utilisateurs dans la table nextcloud.users
///////////////////////////////////////////////////////////////////

$delete = pg_prepare($dbconn_geo, "sql", "DELETE FROM $nx_users;");
$delete = pg_execute($dbconn_geo, "sql",array()) or die ( pg_last_error());

// suppression des données sur dashboard
$delete = pg_prepare($dbconn_geo, "sql_dashboard", "DELETE FROM $nx_dashboard;");
$delete = pg_execute($dbconn_geo, "sql_dashboard",array()) or die ( pg_last_error());

// INSERTION DANS LA TABLE NEXTCLOUD.DASHBOARD
$insert_dashboard = pg_prepare($dbconn_geo, "sql_insert_dashboard", "INSERT INTO $nx_dashboard (uuid, personne, obs_faune, obs_flore, update, obs_cc, version) VALUES ($1, $2, $3,$4,$5,$6,$7);");

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
  //echo $row[0].' - '.$row[1].' - '.$row[2].'</br>';
}

///////////////////////////////////////////////////////////////////
// B. Mise à jour du champ gn_user_name dans la table nextcloud.users
///////////////////////////////////////////////////////////////////
$update = pg_prepare($dbconn_geo, "sql_update", "
with a_ as (select courriel, nom_ad, id_role, email from nextcloud.users 
left join utilisateurs.t_roles on t_roles.email = courriel
group by 1,2,3,4
order by 1
)
update nextcloud.users set gn_user_name = a_.id_role::text from a_ where a_.email = users.courriel;
");
$update_ = pg_execute($dbconn_geo, "sql_update",array()) or die ( pg_last_error());

///////////////////////////////////////////////////////////////////
// C. Mise à jour du champ uuid_nx dans la table suivi_faune
///////////////////////////////////////////////////////////////////

$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  //if file_exists
  if (file_exists($observations_gpkg)) {
    echo '</br>' .$row[0]. ' - ' . date('d-m-Y', filemtime($observations_gpkg)) . '</br>';

    $db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
    $db->loadExtension('mod_spatialite.so');
    $results_obs_faune_gpkg = $db->query("select fid, id_dataset, id_digitiser from  obs_faune where date_import is null;"); //
    $i_faune= 0;
    while ($row_ = $results_obs_faune_gpkg->fetchArray()) {
            //var_dump($row_);
            $i_faune++;
    }
    echo '</br> nb faune :' . $i_faune . '</br>';
    $results_obs_flore_gpkg = $db->query("select fid, id_dataset, id_digitiser from  obs_flore where date_import is null;"); //
    $i_flore= 0;
    while ($row_ = $results_obs_flore_gpkg->fetchArray()) {
            //var_dump($row_);
            $i_flore++;
    }
    echo '</br> nb flore :' . $i_flore . '</br>';
    $insert_dashboard = pg_execute($dbconn_geo, "sql_insert_dashboard",array($row[3], $row[1], $i_faune, $i_flore, date('d-m-Y', filemtime($observations_gpkg)), 0, '0.0' )) or die ( pg_last_error());
  }


}

/* $file_size = pg_prepare($dbconn_geo, "sql_insert", "SELECT ");
$observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  //if file_exists
  if (file_exists($observations_gpkg)) {
  } */

/* 						$db = new SQLite3('/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg');
						$db->loadExtension('mod_spatialite.so');
						echo '</br>'."UPDATE obs_faune set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';".'</br>'; //
						$results_write_gpkg = $db->query("UPDATE obs_faune set date_import = datetime('now') where date_import is null and uuid_obs = '".$row_[2]."';"); //
						pg_prepare($dbconn, "sql_down", "UPDATE $suivi_faune set gpkg_updated = true where uuid_obs = $1;");
						if ($results_write_gpkg) {
							pg_execute($dbconn, "sql_down",array($row_[2])) or die ( pg_last_error());
							$cmd2 = 'sudo -u www-data php /var/www/html/nextcloud/occ files:scan -p "'.$row[3].'"';
							exec($cmd2, $output);
						} */



pg_close($dbconn_geo);
pg_close($dbconn_nx);




?>