<?php
include 'properties.php';




//SKELETON
echo '</br><h2>SKELETON</h2></br>';
echo '</br><h4>NOMENCLATURE</h4></br>';
$cmd='ogr2ogr -f "GPKG" /var/www/html/skeleton/_qfield/observations.gpkg PG:"dbname=geonature host=192.168.1.244 port=5432 user=postgres password=conservatoire" -lco OVERWRITE=YES -nln \'nomenclature\' -sql "SELECT * FROM _qfield.nomenclature;" 2>&1';
echo '</br>'.$cmd.'</br>';
exec($cmd, $output);

	if (!$output) {
		echo '<span style="font-weight:900;color:#056e15">DONE</span></br>';
	} else {
		echo '<span style="font-weight:900;color:#bc2020">FAILED</span></br>';
		print_r($output);
	}
echo '</br><h4>OBSERVATEURS</h4></br>';
$cmd='ogr2ogr -f "GPKG" /var/www/html/skeleton/_qfield/observations.gpkg PG:"dbname=geonature host=192.168.1.244 port=5432 user=postgres password=conservatoire" -lco OVERWRITE=YES -nln \'observateurs\' -sql "SELECT * FROM _qfield.observateurs;" 2>&1';
echo '</br>'.$cmd.'</br>';
exec($cmd, $output);

	if (!$output) {
		echo '<span style="font-weight:900;color:#056e15">DONE</span></br>';
	} else {
		echo '<span style="font-weight:900;color:#bc2020">FAILED</span></br>';
		print_r($output);
	}





?>