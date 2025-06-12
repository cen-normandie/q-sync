<?php
include 'properties.php';

//REVERSE SYNCHRO
// WRITE NOMENCLATURE OBSERVATEUR HABITAT SITES etc.
echo '</br>REVERSE NOMENCLATURE</br>';
    $dbconn = pg_connect("hostaddr=$DBHOST port=$PORT dbname=$DBNAME user=$LOGIN password=$PASS") or die ('Connexion impossible :'. pg_last_error());
    $result = pg_prepare($dbconn, "sql", "SELECT id, courriel, gn_user_name, uuid_nx, active FROM $nx_users;");
    $personne = pg_execute($dbconn, "sql",array()) or die ( pg_last_error());
while($row = pg_fetch_row($personne))
{
	echo $row[0].' - '.$row[1].' - '.$row[2].' - '.$row[3].' - '.$row[4];
$cmd='echo "cenN2024!" | sudo -S ogr2ogr -f "GPKG" /var/www/html/nextcloud/data/'.$row[3].'/files/_qfield/observations.gpkg PG:"dbname=geonature host=192.168.1.244 port=5432 user=postgres password=conservatoire" -nln \'habitats\' -sql "SELECT * FROM _qfield.habitats;" -overwrite -update 2>&1';
echo '</br>'.$cmd.'</br>';
exec($cmd, $output);
	if (!$output) {
		echo '<span style="font-weight:900;color:#056e15">DONE</span></br>';
	} else {
		echo '<span style="font-weight:900;color:#bc2020">FAILED</span></br>';
		print_r($output);
	}
}
    pg_close($dbconn);


?>