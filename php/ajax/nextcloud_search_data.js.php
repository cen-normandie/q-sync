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


$delete = pg_prepare($dbconn_geo, "sql", "DELETE FROM $nx_users;");
$delete = pg_execute($dbconn_geo, "sql",array()) or die ( pg_last_error());


$update_users = pg_prepare($dbconn_geo, "update_users", 
  "UPDATE $nx_users set 
    observations_gpkg = $1, 
    n2k_gpkg = $2, 
    obs_flore = $3, 
    obs_flore_polygone = $4, 
    obs_faune = $5, 
    obs_cc = $6 ,
    n2k_previ_point = $7,
    n2k_previ_ligne = $8,
    n2k_previ_polygone = $9,
    n2k_realise_point = $10,
    n2k_realise_ligne = $11,
    n2k_realise_polygone = $12
    where uuid_nx= $13;");
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

$select = pg_prepare($dbconn_geo, "sql_select", "select courriel, gn_user_name, nom_ad, uuid_nx from $nx_users where active is true ;");
$personne = pg_execute($dbconn_geo, "sql_select",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
  $observations_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg';
  $n2k_gpkg = '/var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/n2k.gpkg';
  $o_gpkg = '';
  $f_n2k_gpkg = '';
  if (file_exists($n2k_gpkg)) {
    $f_n2k_gpkg=date('Y-m-d', filemtime($n2k_gpkg));
    $db_n2k = new SQLite3($n2k_gpkg);
    $db_n2k->loadExtension('mod_spatialite.so');
    $p_p = (int) $db_n2k->query("SELECT coalesce(count(*), 0) as c from n2k_previ_point where importe is null ;");
    echo 'Prévi point non importées : '.$p_p.'</br>';
    $p_l = (int) $db_n2k->query("SELECT coalesce(count(*), 0) as c from n2k_previ_ligne where importe is null ;");
    echo 'Prévi ligne non importées : '.$p_l.'</br>';
    $p_pol = (int) $db_n2k->query("SELECT coalesce(count(*), 0) as c from n2k_previ_polygone where importe is null ;");
    echo 'Prévi polygone non importées : '.$p_pol.'</br>';
    $r_p = (int) $db_n2k->query("SELECT coalesce(count(*), 0) as c from n2k_realise_point where importe is null ;");
    echo 'Réalisé point non importées : '.$r_p.'</br>';
    $r_l = (int) $db_n2k->query("SELECT coalesce(count(*), 0) as c from n2k_realise_ligne where importe is null ;");
    echo 'Réalisé ligne non importées : '.$r_l.'</br>';
    $r_pol = (int) $db_n2k->query("SELECT coalesce(count(*), 0) as c from n2k_realise_polygone where importe is null ;");
    echo 'Réalisé polygone non importées : '.$r_pol.'</br>';
    $db_n2k->close();
  } else {
    $f_n2k_gpkg = 'ø';
    $p_p = 0;
    $p_l = 0;
    $p_pol = 0;
    $r_p = 0;
    $r_l = 0;
    $r_pol = 0;
  }
  if (file_exists($observations_gpkg)) {
    $o_gpkg=date('Y-m-d', filemtime($observations_gpkg));
    
    $db = new SQLite3($observations_gpkg);
    $db->loadExtension('mod_spatialite.so');
    
    

    $db->query("UPDATE meta_qsync set obs_flore = (select count(*) from obs_flore where date_import is null ) ;");
    $db->query("UPDATE meta_qsync set obs_flore_polygone = (select count(*) from obs_flore_polygone where date_import is null ) ;");
    $db->query("UPDATE meta_qsync set obs_faune = (select count(*) from obs_faune where date_import is null ) ;");
    $db->query("UPDATE meta_qsync set obs_cc = (select count(*) from carre_contact where date_import is null ) ;");

    echo 'UPDATE meta_qsync set "n2k_previ_point" = '.$p_p.' ;'.'</br>';
    $db->query('UPDATE meta_qsync set "n2k_previ_point" = CAST('.$p_p.' AS INTEGER) ;');
    $db->query('UPDATE meta_qsync set "n2k_previ_ligne" = CAST('.$p_l.' AS INTEGER) ;');
    $db->query('UPDATE meta_qsync set "n2k_previ_polygone" = CAST('.$p_pol.' AS INTEGER) ;');
    $db->query('UPDATE meta_qsync set "n2k_realise_point" = CAST('.$r_p.' AS INTEGER) ;');
    $db->query('UPDATE meta_qsync set "n2k_realise_ligne" = CAST('.$r_l.' AS INTEGER) ;');
    $db->query('UPDATE meta_qsync set "n2k_realise_polygone" = CAST('.$r_pol.' AS INTEGER) ;');

    $db->query("SELECT * from meta_qsync ;");
    $result = $db->query("SELECT * from meta_qsync ;");
    while ($meta_row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo '</br>#########' .$row[0].'</br>';
        echo 'Données dans le gpkg ( meta_qsync ) : </br>';
        echo ' - obs_flore : '.$meta_row['obs_flore'].'</br>';
        echo ' - obs_flore_polygone : '.$meta_row['obs_flore_polygone'].'</br>';
        echo ' - obs_faune : '.$meta_row['obs_faune'].'</br>';
        echo ' - obs_cc : '.$meta_row['obs_cc'].'</br>';
        // Traitement des résultats
        $obs_flore = $meta_row['obs_flore'];
        $obs_flore_polygone = $meta_row['obs_flore_polygone'];
        $obs_faune = $meta_row['obs_faune'];
        $obs_cc = $meta_row['obs_cc'];
    }
    $db->close();
  } else {
    $o_gpkg = 'ø';
    $obs_flore = 0;
    $obs_flore_polygone = 0;
    $obs_faune = 0;
    $obs_cc = 0;
  }

  $update_users_ = pg_execute($dbconn_geo, "update_users",array($o_gpkg, $f_n2k_gpkg, $obs_flore, $obs_flore_polygone, $obs_faune, $obs_cc, $p_p, $p_l, $p_pol, $r_p, $r_l, $r_pol, $row[3])) or die ( pg_last_error());
  //$update_users_ = pg_execute($dbconn_geo, "update_users",array($o_gpkg, $f_n2k_gpkg, $row[3])) or die ( pg_last_error());
}

pg_close($dbconn_geo);
pg_close($dbconn_nx);

?>